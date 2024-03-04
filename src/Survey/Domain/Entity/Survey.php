<?php

declare(strict_types=1);

namespace Survey\Domain\Entity;

use Survey\Domain\Exception\NotificationErrorException;
use Survey\Domain\Factory\SurveyValidatorFactory;
use Survey\Domain\Validator\Survey\SurveyValidator;
use Survey\Domain\ValueObject\ObjectId;
use Survey\Domain\ValueObject\SurveyAnswer;

class Survey extends Entity
{
    /**
     * @param SurveyAnswer[] $answers
     * @throws NotificationErrorException
     */
    public function __construct(
        protected string $question,
        protected array $answers = [],
        protected bool $didAnswer = false,
        protected ?ObjectId $id = null,
        protected ?\DateTimeInterface $createdAt = null,
        protected ?\DateTimeInterface $updatedAt = null,
    ) {
        parent::__construct();

        $this->id = $this->id ?? ObjectId::random();
        $this->createdAt = $this->createdAt ?? new \DateTime();

        $this->validation();
    }

    /**
     * @throws NotificationErrorException
     */
    private function validation(): void
    {
        SurveyValidatorFactory::create()->validate(entity: $this);

        if ( $this->notificationErrors->hasErrors() ) {
            throw NotificationErrorException::messages(
                message: $this->notificationErrors->messages(SurveyValidator::CONTEXT)
            );
        }
    }

    public function question(): string
    {
        return $this->question;
    }

    public function answers(): array
    {
        return $this->answers;
    }

    public function isAnswered(): bool
    {
        return $this->didAnswer;
    }

    /**
     * @throws NotificationErrorException
     */
    public function changeQuestion(string $question): void
    {
        $this->question = $question;
        $this->updatedAt = new \DateTime();

        $this->validation();
    }

    /**
     * @throws NotificationErrorException
     */
    public function addSurveyAnswer(SurveyAnswer $surveyAnswer): void
    {
        $this->answers[] = $surveyAnswer;
        $this->updatedAt = new \DateTime();

        $this->validation();
    }

    /**
     * @throws NotificationErrorException
     */
    public function removeSurveyAnswer(SurveyAnswer $surveyAnswer): void
    {
        $this->answers = array_filter($this->answers, fn($item) => $item !== $surveyAnswer);
        $this->updatedAt = new \DateTime();

        $this->validation();
    }


    /**
     * @throws NotificationErrorException
     */
    public function answer(): void
    {
        $this->didAnswer = true;
        $this->updatedAt = new \DateTime();

        $this->validation();
    }

    /**
     * @throws NotificationErrorException
     */
    public function noAnswer(): void
    {
        $this->didAnswer = false;
        $this->updatedAt = new \DateTime();

        $this->validation();
    }

    public function toArray(): array
    {
        return [
            '_id' => $this->id(),
            'question' => $this->question(),
            'did_answer' => $this->isAnswered(),
            'answers' => array_map(function ($item) {
                return [
                    'answer' => $item->answer(),
                    'image' => $item->image()?->path(),
                ];
            }, $this->answers()),
            'created_at' => $this->createdAt(),
            'updated_at' => $this->updatedAt(),
        ];
    }
}
