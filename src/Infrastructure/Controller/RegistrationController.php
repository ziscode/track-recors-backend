<?php

namespace App\Infrastructure\Controller;

use App\Infrastructure\Dto\UserRegister;
use App\DataBase\Entity\User;
use App\Infrastructure\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use App\Infrastructure\Assemblers\RegisterUserAssembler;
use App\Domain\UseCases\RegistrationUserUseCases;
use App\Infrastructure\Services\SerializerValidator;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;
    private $assembler;
    private $registrationUc;
    private $service;

    public function __construct(EmailVerifier $emailVerifier, RegisterUserAssembler $assembler, 
        SerializerValidator $service, RegistrationUserUseCases $registrationUc)
    {
        $this->service = $service;
        $this->emailVerifier = $emailVerifier;
        $this->assembler = $assembler;
        $this->registrationUc = $registrationUc;
    }

    #[Route('/api/register', name: 'api_register')]
    public function register(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {

        $resource = $this->service->validate($request->getContent(), UserRegister::class);

        if ($resource instanceof UserRegister) {
            $response = ['success'=>true];

            $entity = $this->assembler->registerResourceToEntity($resource);
            
            if ($entity->getId() === null) {
                $result = $this->registrationUc->register($entity);

                if (isset($result['error'])) {
                    $response = ['errors' => ['validationError' => $result['error']]];                    
                } else {
                    $this->emailVerifier->sendEmailConfirmation('api_verify_email', $entity ,
                        (new TemplatedEmail())
                            ->from(new Address($_ENV['FROM_MAILER'], 'Mail Bot'))
                            ->to($entity->getEmail())
                            ->subject('Please Confirm your Email')
                            ->htmlTemplate('registration/confirmation_email.html.twig')
                    );
                }
            } else {
                $result = $this->registrationUc->update($entity);

                if (isset($result['error'])) {
                    $response = ['errors' => ['validationError' => $result['error']]];                    
                }                
            }

            return new JsonResponse($response);
        }
        
        return new JsonResponse($resource);

    }

    #[Route('/api/verify/email', name: 'api_verify_email')]
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('api_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('api_register');
    }
}
