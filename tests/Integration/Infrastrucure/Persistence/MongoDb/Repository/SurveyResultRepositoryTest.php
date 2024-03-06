<?php

declare(strict_types=1);

namespace Tests\Integration\Infrastrucure\Persistence\MongoDb\Repository;

use Survey\Domain\ValueObject\ObjectId;
use Survey\Infrastructure\Persistence\MongoDb\Helpers\MongoHelper;
use Survey\Infrastructure\Persistence\MongoDb\Repository\SurveyResultRepository;
use Tests\RefreshDatabaseMongoDb;
use Tests\TestCase;

class SurveyResultRepositoryTest extends TestCase
{
    use RefreshDatabaseMongoDb;

    public function test_should_be_return_false_when_there_is_no_exists_email()
    {
        $surveyId = (string)ObjectId::random();
        $userId = (string)ObjectId::random();

        $surveyResultRepository = new SurveyResultRepository();
        $surveyResultRepository->save(
            surveyId: $surveyId,
            userId: $userId,
            answer: 'Answer',
            date: new \DateTime()
        );

        $collection = MongoHelper::getCollection('surveyResults');
        $result = $collection->findOne([
            'user_id' => MongoHelper::objectId($userId),
            'survey_id' => MongoHelper::objectId($surveyId)
        ]);

        $this->assertNotEmpty($result);
    }

    public function test_can_be_load_by_survey_id()
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

        $surveyResultRepository = new SurveyResultRepository();
        $surveyResultRepository->save(
            surveyId: $surveyId,
            userId: $userId,
            answer: 'answer',
            date: new \DateTime()
        );

        $result = $surveyResultRepository->loadBySurveyId(surveyId: $surveyId, userId: $userId);

        $this->assertIsArray($result);
    }
}
