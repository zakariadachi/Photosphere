<?php

declare(strict_types=1);

namespace App\Traits;

use DateTime;
use DateTimeInterface;

trait TimestampableTrait
{

    protected ?DateTimeInterface $createdAt = null;
    protected ?DateTimeInterface $updatedAt = null;

    public function initializeTimestamps(): void
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    public function updateTimestamps(): void
    {
        $this->updatedAt = new DateTime();
    }

    public function getCreatedAt(?string $format = null)
    {
        if ($format != null) {
            return $this->createdAt->format($format);
        } else {
            return $this->createdAt;
        }
    }

    public function getUpdatedAt(?string $format = null)
    {
        if ($this->updatedAt == null) {
            return null;
        }

        if ($format != null) {
            return $this->updatedAt->format($format);
        } else {
            return $this->updatedAt;
        }
    }

    public function setCreatedAt($date): void
    {
        if (is_string($date)) {
            $this->createdAt = new DateTime($date);
        } else {
            $this->createdAt = $date;
        }
    }

    public function setUpdatedAt($date): void
    {
        if ($date === null) {
            $this->updatedAt = null;
        } elseif (is_string($date)) {
            $this->updatedAt = new DateTime($date);
        } else {
            $this->updatedAt = $date;
        }
    }
}
