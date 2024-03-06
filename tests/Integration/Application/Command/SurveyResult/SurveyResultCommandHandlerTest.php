<?php

declare(strict_types=1);

namespace Tests\Integration\Application\Command\SurveyResult;

use Survey\Application\Command\SurveyResult\SaveSurveyResultCommand;
use Survey\Application\Command\SurveyResult\SaveSurveyResultCommandHandler;
use Survey\Infrastructure\Persistence\MongoDb\Helpers\MongoHelper;
use Survey\Infrastructure\Persistence\MongoDb\Repository\SurveyResultRepository;
use Tests\RefreshDatabaseMongoDb;
use Tests\TestCase;

class SurveyResultCommandHandlerTest extends TestCase
{
    use RefreshDatabaseMongoDb;

    public function test_can_be_save_result()
    {
        $collection = MongoHelper::getCollection('accounts');
        $collection->insertOne([
            'first_name' => 'Matheus',
            'last_name' => 'Jose',
            'email' => 'matheus.jose@gmail.com',
            'password' => password_hash('123456789', PASSWORD_DEFAULT)
        ]);
        $account = $collection->findOne(['email' => 'matheus.jose@gmail.com']);
        $userId = (string)$account->_id;

        $collectionSurvey = MongoHelper::getCollection('surveys');
        $collectionSurvey->insertOne([
            'question' => 'Question',
            'survey_answers' => [['answer' => 'answer']]
        ]);
        $survey = $collectionSurvey->findOne(['question' => 'Question']);
        $surveyId = (string)$survey->_id;

        $command = new SaveSurveyResultCommand(
            userId: $userId,
            surveyId: $surveyId,
            answer: 'answer'
        );

        $commandHandler = new SaveSurveyResultCommandHandler(surveyResultRepository: new SurveyResultRepository());

        $result = ($commandHandler)(command: $command);

        $this->assertIsArray($result);
    }
}
