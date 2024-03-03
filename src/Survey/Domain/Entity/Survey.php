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
     * @param SurveyAnswer[] $surveyAnswer
     * @throws NotificationErrorException
     */
    public function __construct(
        protected string $question,
        protected array $surveyAnswer = [],
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

    public function surveyAnswer(): array
    {
        return $this->surveyAnswer;
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
        $this->surveyAnswer[] = $surveyAnswer;
        $this->updatedAt = new \DateTime();

        $this->validation();
    }

    /**
     * @throws NotificationErrorException
     */
    public function removeSurveyAnswer(SurveyAnswer $surveyAnswer): void
    {
        $this->surveyAnswer = array_filter($this->surveyAnswer, fn($item) => $item !== $surveyAnswer);
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
            'survey_answers' => array_map(function ($item) {
                return [
                    'answer' => $item->answer(),
                    'image' => $item->image()?->path(),
                ];
            }, $this->surveyAnswer()),
            'created_at' => $this->createdAt(),
            'updated_at' => $this->updatedAt(),
        ];
    }
}
