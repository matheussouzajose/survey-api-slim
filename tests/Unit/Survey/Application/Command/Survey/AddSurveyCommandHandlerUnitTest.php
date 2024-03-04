<?php

declare(strict_types=1);

namespace Tests\Unit\Survey\Application\Command\Survey;

use Survey\Application\Command\Survey\AddSurveyCommand;
use Survey\Application\Command\Survey\AddSurveyCommandHandler;
use Survey\Domain\Entity\Survey;
use Survey\Domain\Repository\SurveyRepositoryInterface;
use Survey\Domain\ValueObject\ObjectId;
use Survey\Domain\ValueObject\SurveyAnswer;
use Tests\TestCase;

class AddSurveyCommandHandlerUnitTest extends TestCase
{
    private function mockEntity(string $objectId)
    {
        $mockEntity = \Mockery::mock(Survey::class, [
            'Question',
            [new SurveyAnswer('Answer')],
            false,
            new ObjectId(value: $objectId)
        ]);

        $mockEntity->shouldReceive('id')->andReturn($objectId);
        $mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));
        $mockEntity->shouldReceive('toArray')->andReturn([
            '_id' => $objectId,
            'question' => 'Question',
            'did_answer' => false,
            'survey_answers' => [
                [
                    'answer' => 'PHP',
                    'image' => '/',
                ]
            ],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return $mockEntity;
    }

    private function mockRepository(string $objectId, int $timesCalled = 1)
    {
        $mockRepository = \Mockery::mock(\stdClass::class, SurveyRepositoryInterface::class);
        $mockRepository->shouldReceive('add')
            ->times($timesCalled)
            ->andReturn($this->mockEntity($objectId));

        return $mockRepository;
    }

    public function test_should_be_return_false_when_there_survey_answers_is_empty()
    {
        $command = new AddSurveyCommand(
            question: 'Matheus',
            answers: []
        );

        $surveyRepository = $this->mockRepository(objectId: (string)ObjectId::random(), timesCalled: 0);

        $commandHandler = new AddSurveyCommandHandler(surveyRepository: $surveyRepository);

        $result = ($commandHandler)(command: $command);

        $this->assertFalse($result['success']);
        $this->assertEquals('Answers is required', $result['message']);
    }

    public function test_should_be_return_false_when_there_answer_is_empty()
    {
        $command = new AddSurveyCommand(
            question: 'Matheus',
            answers: [
                [],
                [
                    'answer' => 'PHP',
                    'image' => '/',
                ]
            ]
        );

        $surveyRepository = $this->mockRepository(objectId: (string)ObjectId::random(), timesCalled: 0);

        $commandHandler = new AddSurveyCommandHandler(surveyRepository: $surveyRepository);

        $result = ($commandHandler)(command: $command);

        $this->assertFalse($result['success']);
        $this->assertEquals('Answers is required', $result['message']);
    }

    public function test_can_be_add_survey()
    {
        $command = new AddSurveyCommand(
            question: 'Matheus',
            answers: [
                [
                    'answer' => 'PHP',
                    'image' => '/',
                ],
                [
                    'answer' => 'JS'
                ]
            ]
        );

        $surveyRepository = $this->mockRepository(objectId: (string)ObjectId::random(), timesCalled: 1);

        $commandHandler = new AddSurveyCommandHandler(surveyRepository: $surveyRepository);

        $result = ($commandHandler)(command: $command);

        $this->assertTrue($result['success']);
        $this->assertEquals('Survey created successfully', $result['message']);
        $this->assertIsArray($result['result']);
    }

    protected function tearDown(): void
    {
        \Mockery::close();

        parent::tearDown();
    }
}
