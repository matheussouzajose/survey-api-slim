<?php

declare(strict_types=1);

namespace Tests\Integration\Infrastrucure\Persistence\MongoDb\Repository;

use Survey\Domain\Entity\Survey;
use Survey\Domain\ValueObject\SurveyAnswer;
use Survey\Infrastructure\Persistence\MongoDb\Repository\SurveyRepository;
use Tests\RefreshDatabaseMongoDb;
use Tests\TestCase;

class SurveyRepositoryTest extends TestCase
{
    use RefreshDatabaseMongoDb;

    public function test_should_be_return_false_when_there_is_no_exists_email()
    {
        $survey = new Survey(
            question: 'Question',
            answers: [new SurveyAnswer(answer: 'Answer')]
        );

        $surveyRepository = new SurveyRepository();
        $result = $surveyRepository->add(entity: $survey);

        $this->assertNotEmpty($result->id());
        $this->assertNotEmpty($result->createdAt());
        $this->assertEquals('Question', $result->question());
        $this->assertInstanceOf(SurveyAnswer::class, $result->answers()[0]);
        $this->assertFalse($result->isAnswered());
    }

//    public function test_can_be_load_all()
//    {
//        $collection = MongoHelper::getCollection('accounts');
//        $collection->insertOne([
//            'first_name' => 'Matheus',
//            'last_name' => 'Jose',
//            'email' => 'matheus.jose@mail.com',
//            'password' => password_hash('123456789', PASSWORD_DEFAULT)
//        ]);
//
//        $account = $collection->findOne(['email' => 'matheus.jose@mail.com']);
//        $userId = (string)$account->_id;
//
//        $collectionSurvey = MongoHelper::getCollection('surveys');
//        $collectionSurvey->insertOne([
//            'question' => 'Question 1',
//            'survey_answers' => [['answer' => 'Answer 1']]
//        ]);
//        $survey = $collectionSurvey->findOne(['question' => 'Question 1']);
//        $surveyId = (string)$survey->_id;
//
//        $collectionSurveyResult = MongoHelper::getCollection('surveyResults');
//        $collectionSurveyResult->insertOne([
//            'survey_id' => $surveyId,
//            'user_id' => $userId,
//            'answer' => 'Answer 1',
//            'created_at' => date('Y-m-d')
//        ]);
//
//        $surveyRepository = new SurveyRepository();
//        $result = $surveyRepository->loadAll(userId: $userId);
//    }
}
