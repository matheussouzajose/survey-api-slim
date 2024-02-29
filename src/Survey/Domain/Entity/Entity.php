<?php

declare(strict_types=1);

namespace Survey\Domain\Entity;

use Survey\Domain\NotificationPattern\NotificationError;
use Survey\Domain\ValueObject\ObjectId;

abstract class Entity
{
    protected ?ObjectId $id = null;

    protected ?\DateTimeInterface $createdAt = null;
    protected ?\DateTimeInterface $updatedAt = null;
    public NotificationError $notificationErrors;

    public function __construct()
    {
        $this->notificationErrors = new NotificationError();
    }

    public function id(): string
    {
        return (string)$this->id;
    }

    public function createdAt(): string
    {
        return $this->createdAt->format('Y-m-d H:i:s');
    }

    public function updatedAt(): ?string
    {
        return $this->updatedAt?->format('Y-m-d H:i:s');
    }
}
