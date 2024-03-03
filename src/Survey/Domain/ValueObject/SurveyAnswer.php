<?php

declare(strict_types=1);

namespace Survey\Domain\ValueObject;

class SurveyAnswer
{
    public function __construct(protected string $answer, protected ?Image $image = null)
    {
    }

    public function answer(): string
    {
        return $this->answer;
    }

    public function image(): ?Image
    {
        return $this->image ?? null;
    }
}
