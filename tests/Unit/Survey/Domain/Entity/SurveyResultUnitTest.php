<?php

declare(strict_types=1);

namespace Tests\Unit\Survey\Domain\Entity;

use Survey\Domain\Entity\SurveyResult;
use Survey\Domain\Exception\NotificationErrorException;
use Survey\Domain\ValueObject\Image;
use Survey\Domain\ValueObject\SurveyResultAnswer;
use Tests\TestCase;

class SurveyResultUnitTest extends TestCase
{
    /**
     * @dataProvider providerInvalidAttributes
     */
    public function test_throws_error_when_given_an_invalid_data(
        string $surveyId,
        string $question,
        array $surveyAnswer
    ) {
        $this->expectException(NotificationErrorException::class);

        new SurveyResult(
            surveyId: $surveyId,
            question: $question,
            answers: $surveyAnswer
        );
    }

    public static function providerInvalidAttributes(): array
    {
        return [
            [
                '',
                'Question',
                [
                    new SurveyResultAnswer(
                        answer: 'answer',
                        count: 1,
                        percent: 100,
                        isCurrentUserAnswer: false,
                        image: new Image('')
                    )
                ]
            ],
            [
                'user_id',
                '',
                [
                    new SurveyResultAnswer(
                        answer: 'answer',
                        count: 1,
                        percent: 100,
                        isCurrentUserAnswer: false,
                        image: new Image('')
                    )
                ]
            ],
            [
                'user_id',
                'Question',
                ['']
            ]
        ];
    }

    public function test_can_be_created_survey()
    {
        $survey = $this->createSurveyResult();

        $this->assertNotEmpty($survey->id());
        $this->assertNotEmpty($survey->createdAt());
        $this->assertEquals('Question One', $survey->question());
        $this->assertCount(1, $survey->answers());
        $this->assertNull($survey->updatedAt());
    }

    public function test_can_be_change_question()
    {
        $survey = $this->createSurveyResult();

        $question = 'New question';
        $survey->changeQuestion(question: $question);

        $this->assertEquals($question, $survey->question());
        $this->assertNotEmpty($survey->updatedAt());
    }

    public function test_can_be_add_survey_answer()
    {
        $survey = $this->createSurveyResult();

        $survey->addSurveyResultAnswer(
            surveyResultAnswer: new SurveyResultAnswer(
                answer: 'answer',
                count: 1,
                percent: 100,
                isCurrentUserAnswer: false,
                image: new Image('')
            )
        );

        $this->assertCount(2, $survey->answers());
        $this->assertNotEmpty($survey->updatedAt());
    }

    public function test_can_be_remove_survey_answer()
    {
        $survey = $this->createSurveyResult();

        $surveyResultAnswer = new SurveyResultAnswer(
            answer: 'answer',
            count: 1,
            percent: 100,
            isCurrentUserAnswer: false,
            image: new Image('')
        );
        $survey->addSurveyResultAnswer(surveyResultAnswer: $surveyResultAnswer);

        $this->assertCount(2, $survey->answers());

        $survey->removeSurveyAnswer(surveyResultAnswer: $surveyResultAnswer);

        $this->assertCount(1, $survey->answers());
        $this->assertNotEmpty($survey->updatedAt());
    }

    /**
     * @throws NotificationErrorException
     */
    private function createSurveyResult(): SurveyResult
    {
        return new SurveyResult(
            surveyId: 'user_id',
            question: 'Question One',
            answers: [
                new SurveyResultAnswer(
                    answer: 'answer',
                    count: 1,
                    percent: 100,
                    isCurrentUserAnswer: false,
                    image: new Image('')
                )
            ]
        );
    }

    protected function tearDown(): void
    {
        \Mockery::close();

        parent::tearDown();
    }
}
