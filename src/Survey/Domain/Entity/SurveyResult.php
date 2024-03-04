<?php

declare(strict_types=1);

namespace Survey\Domain\Entity;

use Survey\Domain\Exception\NotificationErrorException;
use Survey\Domain\Factory\SurveyResultValidatorFactory;
use Survey\Domain\Validator\Survey\SurveyResultValidator;
use Survey\Domain\ValueObject\ObjectId;
use Survey\Domain\ValueObject\SurveyResultAnswer;

class SurveyResult extends Entity
{
    /**
     * @param SurveyResultAnswer[] $answers
     * @throws NotificationErrorException
     */
    public function __construct(
        protected string $surveyId,
        protected string $question,
        protected array $answers = [],
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
        SurveyResultValidatorFactory::create()->validate(entity: $this);

        if ( $this->notificationErrors->hasErrors() ) {
            throw NotificationErrorException::messages(
                message: $this->notificationErrors->messages(SurveyResultValidator::CONTEXT)
            );
        }
    }

    public function surveyId(): string
    {
        return $this->surveyId;
    }

    public function question(): string
    {
        return $this->question;
    }

    public function answers(): array
    {
        return $this->answers;
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
    public function addSurveyResultAnswer(SurveyResultAnswer $surveyResultAnswer): void
    {
        $this->answers[] = $surveyResultAnswer;
        $this->updatedAt = new \DateTime();

        $this->validation();
    }

    /**
     * @throws NotificationErrorException
     */
    public function removeSurveyAnswer(SurveyResultAnswer $surveyResultAnswer): void
    {
        $this->answers = array_filter($this->answers, fn($item) => $item !== $surveyResultAnswer);
        $this->updatedAt = new \DateTime();

        $this->validation();
    }

    public function toArray(): array
    {
        return [
            '_id' => $this->id(),
            'question' => $this->question(),
            'answers' => array_map(function ($item) {
                return [
                    'answer' => $item->answer(),
                    'image' => $item->image()?->path(),
                    'count' => $item->count(),
                    'percent' => $item->percent(),
                    'isCurrentUserAnswer' => $item->isCurrentUserAnswer(),
                ];
            }, $this->answers()),
            'created_at' => $this->createdAt(),
            'updated_at' => $this->updatedAt(),
        ];
    }
}
