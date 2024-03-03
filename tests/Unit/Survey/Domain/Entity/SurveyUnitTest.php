<?php

declare(strict_types=1);

namespace Tests\Unit\Survey\Domain\Entity;

use Survey\Domain\Entity\Survey;
use Survey\Domain\Exception\NotificationErrorException;
use Survey\Domain\ValueObject\Image;
use Survey\Domain\ValueObject\SurveyAnswer;
use Tests\TestCase;

class SurveyUnitTest extends TestCase
{
    /**
     * @dataProvider providerInvalidAttributes
     */
    public function test_throws_error_when_given_an_invalid_data(string $question, array $surveyAnswer)
    {
        $this->expectException(NotificationErrorException::class);

        new Survey(
            question: $question,
            surveyAnswer: $surveyAnswer
        );
    }

    public static function providerInvalidAttributes(): array
    {
        return [
            ['', [new SurveyAnswer(answer: 'answer', image: new Image(''))]],
            ['Question', ['ss']],
        ];
    }

    public function test_can_be_created_survey()
    {
        $survey = $this->createSurvey();

        $this->assertNotEmpty($survey->id());
        $this->assertNotEmpty($survey->createdAt());
        $this->assertEquals('Question One', $survey->question());
        $this->assertCount(1, $survey->surveyAnswer());
        $this->assertFalse($survey->isAnswered());
        $this->assertNull($survey->updatedAt());
    }

    public function test_can_be_change_question()
    {
        $survey = $this->createSurvey();

        $question = 'New question';
        $survey->changeQuestion(question: $question);

        $this->assertEquals($question, $survey->question());
        $this->assertNotEmpty($survey->updatedAt());
    }

    public function test_can_be_add_survey_answer()
    {
        $survey = $this->createSurvey();

        $survey->addSurveyAnswer(surveyAnswer: new SurveyAnswer('new answer'));

        $this->assertCount(2, $survey->surveyAnswer());
        $this->assertNotEmpty($survey->updatedAt());
    }

    public function test_can_be_remove_survey_answer()
    {
        $survey = $this->createSurvey();

        $surveyAnswer = new SurveyAnswer('new answer');
        $survey->addSurveyAnswer(surveyAnswer: $surveyAnswer);

        $this->assertCount(2, $survey->surveyAnswer());

        $survey->removeSurveyAnswer(surveyAnswer: $surveyAnswer);

        $this->assertCount(1, $survey->surveyAnswer());
        $this->assertNotEmpty($survey->updatedAt());
    }

    public function test_can_be_answer()
    {
        $survey = $this->createSurvey();
        $survey->answer();

        $this->assertTrue($survey->isAnswered());
    }

    public function test_can_be_no_answer()
    {
        $survey = $this->createSurvey();
        $survey->answer();

        $this->assertTrue($survey->isAnswered());

        $survey->noAnswer();
        $this->assertFalse($survey->isAnswered());
    }

    /**
     * @throws NotificationErrorException
     */
    private function createSurvey(): Survey
    {
        return new Survey(question: 'Question One', surveyAnswer: [new SurveyAnswer('answer')]);
    }

    protected function tearDown(): void
    {
        \Mockery::close();

        parent::tearDown();
    }
}
