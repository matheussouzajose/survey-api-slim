<?php

declare(strict_types=1);

namespace Tests\Integration\Ui\Api\Controller\Survey;

use Survey\Application\Command\Survey\AddSurveyCommandHandler;
use Survey\Infrastructure\Persistence\MongoDb\Repository\SurveyRepository;
use Survey\Infrastructure\Validation\Survey\AddSurveyValidation;
use Survey\Ui\Api\Adapter\Http\HttpResponse;
use Survey\Ui\Api\Controller\Survey\AddSurveyController;
use Tests\RefreshDatabaseMongoDb;
use Tests\TestCase;

class AddSurveyControllerTest extends TestCase
{
    use RefreshDatabaseMongoDb;

    public function test_should_be_return_bad_request()
    {
        $controller = $this->createController();

        $request = new \stdClass();
        $request->question = '';
        $request->answers = [];

        $result = ($controller)(request: $request);

        $this->assertInstanceOf(HttpResponse::class, $result);
        $this->assertEquals(400, $result->getStatusCode());
        $this->assertIsArray($result->getBody());
    }

    public function test_should_return_be_created()
    {
        $controller = $this->createController();

        $request = new \stdClass();
        $request->question = 'Question';
        $request->answers = [
            [
                'answer' => 'Answer',
                'image' => 'image'
            ],
            [
                'answer' => 'Answer 2'
            ]
        ];

        $result = ($controller)(request: $request);

        $this->assertInstanceOf(HttpResponse::class, $result);
        $this->assertEquals(201, $result->getStatusCode());

        $this->assertIsArray($result->getBody());
        $this->assertNotEmpty($result->getBody()['_id']);
        $this->assertNotEmpty($result->getBody()['created_at']);
    }

    private function createController(): AddSurveyController
    {
        $validation = new AddSurveyValidation();
        $commandHandler = new AddSurveyCommandHandler(
            surveyRepository: new SurveyRepository()
        );

        return new AddSurveyController(
            validation: $validation,
            commandHandler: $commandHandler
        );
    }
}
