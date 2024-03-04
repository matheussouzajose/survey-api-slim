<?php

declare(strict_types=1);

namespace Tests\Integration\Application\Command\Survey;

use Survey\Application\Command\Survey\AddSurveyCommand;
use Survey\Application\Command\Survey\AddSurveyCommandHandler;
use Survey\Infrastructure\Persistence\MongoDb\Repository\SurveyRepository;
use Tests\RefreshDatabaseMongoDb;
use Tests\TestCase;

class SurveyCommandHandlerTest extends TestCase
{
    use RefreshDatabaseMongoDb;

    public function test_should_be_return_false_when_there_survey_answers_is_empty()
    {
        $surveyRepository = new SurveyRepository();

        $command = new AddSurveyCommand(
            question: 'Question',
            answers: []
        );

        $commandHandler = new AddSurveyCommandHandler(surveyRepository: $surveyRepository);

        $result = ($commandHandler)(command: $command);

        $this->assertFalse($result['success']);
        $this->assertEquals('Answers is required', $result['message']);
    }

    public function test_should_be_return_false_when_there_answer_is_empty()
    {
        $surveyRepository = new SurveyRepository();

        $command = new AddSurveyCommand(
            question: 'Question',
            answers: [
                []
            ]
        );

        $commandHandler = new AddSurveyCommandHandler(surveyRepository: $surveyRepository);

        $result = ($commandHandler)(command: $command);

        $this->assertFalse($result['success']);
        $this->assertEquals('Answers is required', $result['message']);
    }

    public function test_can_be_add_survey()
    {
        $surveyRepository = new SurveyRepository();

        $command = new AddSurveyCommand(
            question: 'Question',
            answers: [
                [
                    'answer' => 'PHP',
                    'image' => '/php.jpeg',

                ],
                [
                    'answer' => 'JS'
                ]
            ]
        );

        $commandHandler = new AddSurveyCommandHandler(surveyRepository: $surveyRepository);

        $result = ($commandHandler)(command: $command);

        $this->assertTrue($result['success']);
        $this->assertEquals('Survey created successfully', $result['message']);
        $this->assertIsArray($result['result']);
    }
}
