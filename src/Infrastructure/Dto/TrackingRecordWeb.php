<?php

declare(strict_types=1);

namespace App\Infrastructure\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use App\Infrastructure\Dto\TrackingRecord;

class TrackingRecordWeb extends TrackingRecord
{
    #[Assert\Type("int")]
    protected $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }
}
