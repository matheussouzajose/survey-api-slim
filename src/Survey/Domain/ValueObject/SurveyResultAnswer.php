<?php

declare(strict_types=1);

namespace Survey\Domain\ValueObject;

class SurveyResultAnswer
{
    public function __construct(
        protected string $answer,
        protected int $count,
        protected float $percent,
        protected bool $isCurrentUserAnswer,
        protected ?Image $image = null
    ) {
    }

    public function answer(): string
    {
        return $this->answer;
    }

    public function count(): int
    {
        return $this->count;
    }

    public function percent(): float
    {
        return $this->percent;
    }

    public function isCurrentUserAnswer(): bool
    {
        return $this->isCurrentUserAnswer;
    }

    public function image(): ?Image
    {
        return $this->image;
    }
}
