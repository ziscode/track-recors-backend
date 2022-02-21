<?php

declare(strict_types=1);

namespace App\Infrastructure\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class TrackingRecord
{
    #[Assert\Type("int")]
    protected $id;

    #[Assert\Type("string")]
    #[Assert\NotBlank]
    #[Assert\DateTime(format:"Y-m-d H:i")]
    protected $startDate;

    #[Assert\Type("string")]
    #[Assert\NotBlank]
    #[Assert\DateTime(format:"Y-m-d H:i")]
    protected $endDate;

    #[Assert\Type("numeric")]
    #[Assert\Range(
        min: -90,
        max: 90,
    )]
    #[Assert\NotBlank]
    protected $startLatitude;

    #[Assert\Type("numeric")]
    #[Assert\Range(
        min: -180,
        max: 180,
    )]
    #[Assert\NotBlank]
    protected $startLongitude;

    #[Assert\Type("numeric")]
    #[Assert\Range(
        min: -90,
        max: 90,
    )]
    #[Assert\NotBlank]
    protected $endLatitude;

    #[Assert\Type("numeric")]
    #[Assert\Range(
        min: -180,
        max: 180,
    )]
    #[Assert\NotBlank]
    protected $endLongitude;

    #[ORM\Column(type: 'bool')]
    protected $finished;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getStartDate(): ?string
    {
        return $this->startDate;
    }

    public function setStartDate(string $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?string
    {
        return $this->endDate;
    }

    public function setEndDate(string $endDate): self
    {
        $this->endDate = $endDate;

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

    public function getStartLatitude(): ?float
    {
        return $this->startLatitude;
    }

    public function setStartLatitude(float $startLatitude): self
    {
        $this->startLatitude = $startLatitude;

        return $this;
    }

    public function getStartLongitude(): ?float
    {
        return $this->startLongitude;
    }

    public function setStartLongitude(float $startLongitude): self
    {
        $this->startLongitude = $startLongitude;

        return $this;
    }

    public function getEndLatitude(): ?float
    {
        return $this->endLatitude;
    }

    public function setEndLatitude(float $endLatitude): self
    {
        $this->endLatitude = $endLatitude;

        return $this;
    }

    public function getEndLongitude(): ?float
    {
        return $this->endLongitude;
    }

    public function setEndLongitude(float $endLongitude): self
    {
        $this->endLongitude = $endLongitude;

        return $this;
    }
}
