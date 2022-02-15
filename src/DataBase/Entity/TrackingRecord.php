<?php

declare(strict_types=1);

namespace App\DataBase\Entity;

use App\DataBase\Repository\TrackingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TrackingRepository::class)]
class TrackingRecord
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Assert\Type("int")]
    #[Assert\NotBlank]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Type("string")]
    private $deviceId;

    #[ORM\Column(type: 'datetime')]    
    private $startDate;

    #[ORM\Column(type: 'datetime')]
    private $endDate;

    #[ORM\Column(type: 'json')]
    private $initialCoordinate = [];

    #[ORM\Column(type: 'json')]
    private $finalCoordinate = [];

    #[ORM\Column(type: 'json', nullable: true)]
    private $tracking = [];

    #[ORM\Column(type: 'json', nullable: true)]
    private $trackingInfo = [];

    #[ORM\Column(type: 'boolean')]
    private $finished;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getDeviceId(): ?string
    {
        return $this->deviceId;
    }

    public function setDeviceId(?string $deviceId): self
    {
        $this->deviceId = $deviceId;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getInitialCoordinate(): ?array
    {
        return $this->initialCoordinate;
    }

    public function setInitialCoordinate(array $initialCoordinate): self
    {
        $this->initialCoordinate = $initialCoordinate;

        return $this;
    }

    public function getFinalCoordinate(): ?array
    {
        return $this->finalCoordinate;
    }

    public function setFinalCoordinate(array $finalCoordinate): self
    {
        $this->finalCoordinate = $finalCoordinate;

        return $this;
    }

    public function getTracking(): ?array
    {
        return $this->tracking;
    }

    public function setTracking(array $tracking): self
    {
        $this->tracking = $tracking;

        return $this;
    }

    public function getTrackingInfo(): ?array
    {
        return $this->trackingInfo;
    }

    public function setTrackingInfo(?array $trackingInfo): self
    {
        $this->trackingInfo = $trackingInfo;

        return $this;
    }

    public function getFinished(): ?bool
    {
        return $this->finished;
    }

    public function setFinished(bool $finished): self
    {
        $this->finished = $finished;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
