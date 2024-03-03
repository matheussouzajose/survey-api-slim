<?php

declare(strict_types=1);

namespace Tests\Integration\Ui\Api\Controller\Survey;

use Survey\Infrastructure\Persistence\MongoDb\Helpers\MongoHelper;
use Survey\Infrastructure\Persistence\MongoDb\Repository\SurveyRepository;
use Survey\Ui\Api\Adapter\Http\HttpResponse;
use Survey\Ui\Api\Controller\Survey\LoadSurveysController;
use Tests\RefreshDatabaseMongoDb;
use Tests\TestCase;

class LoadSurveysControllerTest extends TestCase
{
    use RefreshDatabaseMongoDb;

    public function test_should_return_no_content()
    {
        $controller = $this->createController();

        $request = new \stdClass();
        $request->user_id = '65e4b36d2470cfbefc0738b2';

        $result = ($controller)(request: $request);

        $this->assertInstanceOf(HttpResponse::class, $result);
        $this->assertEquals(204, $result->getStatusCode());
    }

    public function test_should_return_ok()
    {
        $collectionAccount = MongoHelper::getCollection('accounts');
        $collectionAccount->insertOne([
            'first_name' => 'Matheus',
            'last_name' => 'Jose',
            'email' => 'matheus.jose@gmail.com',
            'password' => password_hash('123456789', PASSWORD_DEFAULT)
        ]);
        $account = $collectionAccount->findOne(['email' => 'matheus.jose@gmail.com']);

        $collectionSurvey = MongoHelper::getCollection('surveys');
        $collectionSurvey->insertOne([
            'question' => 'Question 1',
            'survey_answers' => [['answer' => 'Answer 1']]
        ]);
        $collectionSurvey->insertOne([
            'question' => 'Question 2',
            'survey_answers' => [['answer' => 'Answer 2']]
        ]);

        $controller = $this->createController();

        $request = new \stdClass();
        $request->user_id = (string)$account->_id;

        $result = ($controller)(request: $request);

        $this->assertInstanceOf(HttpResponse::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    private function createController(): LoadSurveysController
    {
        return new LoadSurveysController(
            surveyRepository: new SurveyRepository()
        );
    }
}
