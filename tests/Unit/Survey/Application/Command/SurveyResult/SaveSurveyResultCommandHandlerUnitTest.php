<?php

declare(strict_types=1);

namespace Tests\Unit\Survey\Application\Command\SurveyResult;

use Survey\Application\Command\SurveyResult\SaveSurveyResultCommand;
use Survey\Application\Command\SurveyResult\SaveSurveyResultCommandHandler;
use Survey\Domain\Entity\SurveyResult;
use Survey\Domain\Repository\SurveyResultRepositoryInterface;
use Survey\Domain\ValueObject\ObjectId;
use Survey\Domain\ValueObject\SurveyResultAnswer;
use Tests\TestCase;

class SaveSurveyResultCommandHandlerUnitTest extends TestCase
{
    private function mockEntity(string $objectId)
    {
        $mockEntity = \Mockery::mock(SurveyResult::class, [
            'user_id',
            'Question',
            [new SurveyResultAnswer(answer: 'Answer', count: 1, percent: 100, isCurrentUserAnswer: true)],
            new ObjectId(value: $objectId)
        ]);

        $mockEntity->shouldReceive('id')->andReturn($objectId);
        $mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));
        $mockEntity->shouldReceive('toArray')->andReturn([
            '_id' => $objectId,
            'question' => 'Question',
            'answers' => [
                'answer' => 'answer',
                'image' => '/image.png',
                'count' => 1,
                'percent' => 100,
                'isCurrentUserAnswer' => true,
            ],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return $mockEntity;
    }

    private function mockRepository(string $objectId, int $timesCalled = 1)
    {
        $mockRepository = \Mockery::mock(\stdClass::class, SurveyResultRepositoryInterface::class);
        $mockRepository->shouldReceive('save')
            ->times($timesCalled)
            ->andReturn($this->mockEntity($objectId));

        $mockRepository->shouldReceive('loadBySurveyId')->times(1)->andReturn(['a']);

        return $mockRepository;
    }

    public function test_can_be_add_survey_result()
    {
        $surveyResultRepository = $this->mockRepository(objectId: (string)ObjectId::random());

        $command = new SaveSurveyResultCommand(userId: 'user_id', surveyId: 'survey_id', answer: 'answer');
        $commandHandler = new SaveSurveyResultCommandHandler(surveyAnswerRepository: $surveyResultRepository);

        $result = ($commandHandler)(command: $command);

        $this->assertIsArray($result);
    }

    protected function tearDown(): void
    {
        \Mockery::close();

        parent::tearDown();
    }
}
