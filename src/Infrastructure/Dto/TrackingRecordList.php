<?php

declare(strict_types=1);

namespace App\Infrastructure\Dto;

class TrackingRecordList
{
    /**
     * @var int $id
     */
    protected $id;

    /**
     * @var string $deviceId
     */
    protected $deviceId;

    /**
     * @var string $startDate
     */
    protected $startDate;

    /**
     * @var string $endDate
     */
    protected $endDate;

    /**
     * @var bool $finished
     */
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

    public function getDeviceId(): ?string
    {
        return $this->deviceId;
    }

    public function setDeviceId(?string $deviceId): self
    {
        $this->deviceId = $deviceId;

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

}
