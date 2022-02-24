<?php

namespace App\Infrastructure\Controller;

use App\DataBase\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;
use App\Infrastructure\Dto\ResetPasswordRequest;
use App\Infrastructure\Dto\ChangePassword;
use App\Infrastructure\Services\SerializerValidator;

#[Route('/reset-password')]
class ResetPasswordController extends AbstractController
{
    use ResetPasswordControllerTrait;

    private $resetPasswordHelper;
    private $service;
    
    public function __construct(ResetPasswordHelperInterface $resetPasswordHelper, 
        SerializerValidator $service)
    {
        $this->resetPasswordHelper = $resetPasswordHelper;
        $this->service = $service;
    }

    /**
     * Display & process form to request a password reset.
     */
    #[Route('', name: 'app_forgot_password_request')]
    public function request(Request $request, MailerInterface $mailer): Response
    {
        $resource = $this->service->validate($request->getContent(), ResetPasswordRequest::class);

        if ($resource instanceof ResetPasswordRequest) {
            $this->processSendingPasswordResetEmail(
                $resource->getEmail(),
                $mailer
            );

            return new JsonResponse(['success'=>true]);
        }

        return new JsonResponse($resource);
    }

    /**
     * Validates and process the reset URL that the user clicked in their email.
     */
    #[Route('/reset/{token}', name: 'app_reset_password')]
    public function reset(Request $request, UserPasswordHasherInterface $userPasswordHasher, string $token = null): Response
    {
        if (null === $token) {
            return new JsonResponse(['errors'=>['validationError'=>'No reset password token found in the URL or in the session.']]);
        }

        try {
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface $e) {
            return new JsonResponse(['errors'=>['validationError' => sprintf(
                'There was a problem validating your reset request - %s',
                $e->getReason()
            )]]);
        }

        $resource = $this->service->validate($request->getContent(), ChangePassword::class);

        if ($resource instanceof ChangePassword) {
            $this->resetPasswordHelper->removeResetRequest($token);

            // Encode(hash) the plain password, and set it.
            $encodedPassword = $userPasswordHasher->hashPassword(
                $user,
                $resource->getPlainPassword()
            );

            $user->setPassword($encodedPassword);
            $this->getDoctrine()->getManager()->flush();

            // The session is cleaned up after the password has been changed.
            $this->cleanSessionAfterReset();

            return new JsonResponse(['success'=>true]);
        }

        return new JsonResponse($resource);
    }

    private function processSendingPasswordResetEmail(string $emailFormData, MailerInterface $mailer): void
    {
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy([
            'email' => $emailFormData,
        ]);

        if ($user) {
            
            try {
                $resetToken = $this->resetPasswordHelper->generateResetToken($user);
    
                $email = (new TemplatedEmail())
                    ->from(new Address($_ENV['FROM_MAILER'], 'Mail Bot'))
                    ->to($user->getEmail())
                    ->subject('Your password reset request')
                    ->htmlTemplate('reset_password/email.html.twig')
                    ->context([
                        'resetToken' => $resetToken,
                        'appUrl' => $_ENV['APP_URL']
                    ]);
    
                $mailer->send($email);
    
                $this->setTokenObjectInSession($resetToken);
            } catch (ResetPasswordExceptionInterface $e) {
                //TODO: check what can do here
            }

        }       

    }

}
