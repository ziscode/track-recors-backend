<?php

declare(strict_types=1);

namespace App\Infrastructure\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class TrackingRecordApp
{
    #[Assert\Type("string")]
    #[Assert\NotBlank]
    protected $deviceId;

    #[Assert\Type("int")]
    #[Assert\NotBlank]
    protected $startDate;

    #[Assert\Type("int")]
    #[Assert\NotBlank]
    protected $endDate;

    #[Assert\Type("array")]
    #[Assert\NotBlank]
    protected $initialCoordinate = [];

    #[Assert\Type("array")]
    #[Assert\NotBlank]
    protected $finalCoordinate = [];

    #[Assert\Type("array")]
    protected $tracking = [];

    #[Assert\Type("array")]
    protected $trackingInfo = [];

    #[ORM\Column(type: 'bool')]
    protected $finished;

    public function getDeviceId(): ?string
    {
        return $this->deviceId;
    }

    public function setDeviceId(?string $deviceId): self
    {
        $this->deviceId = $deviceId;

        return $this;
    }

    public function getStartDate(): ?int
    {
        return $this->startDate;
    }

    public function setStartDate(int $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?int
    {
        return $this->endDate;
    }

    public function setEndDate(int $endDate): self
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
}
