<?php

declare(strict_types=1);

namespace Survey\Infrastructure\Persistence\MongoDb\Repository;

use MongoDB\Collection;
use Survey\Domain\Entity\Survey;
use Survey\Domain\Exception\NotificationErrorException;
use Survey\Domain\Repository\SurveyRepositoryInterface;
use Survey\Domain\ValueObject\Image;
use Survey\Domain\ValueObject\ObjectId;
use Survey\Domain\ValueObject\SurveyAnswer;
use Survey\Infrastructure\Persistence\MongoDb\Helpers\MongoHelper;
use Survey\Infrastructure\Persistence\MongoDb\Helpers\QueryBuilder;

class SurveyAnswerRepository implements SurveyRepositoryInterface
{
    public Collection $collection;

    public function __construct()
    {
        $this->collection = MongoHelper::getCollection('surveys');
    }

    /**
     * @throws NotificationErrorException
     */
    public function add(Survey $entity): Survey
    {
        $this->collection->insertOne($entity->toArray());

        $result = $this->collection->findOne(['_id' => $entity->id()]);

        return $this->createEntity($result);
    }

    /**
     * @throws NotificationErrorException
     * @throws \Exception
     */
    private function createEntity(object $entity): Survey
    {
        $surveyAnswers = [];
        foreach ($entity->answers as $surveyAnswer) {
            $surveyAnswers[] = new SurveyAnswer(
                answer: $surveyAnswer->answer,
                image: $surveyAnswer->image ? new Image($surveyAnswer->image) : null
            );
        }

        return new Survey(
            question: $entity->question,
            answers: $surveyAnswers,
            didAnswer: $entity->did_answer,
            id: new ObjectId((string)$entity->_id),
            createdAt: isset($entity->created_at) ? new \DateTime($entity->created_at) : null,
            updatedAt: isset($entity->updated_at) ? new \DateTime($entity->updated_at) : null
        );
    }

    public function loadAll(string $userId): array
    {
        $query = new QueryBuilder();
        $query->lookup([
            'from' => 'surveyResults',
            'foreignField' => 'survey_id',
            'localField' => '_id',
            'as' => 'result'
        ]);
        $query->project([
            '_id' => 1,
            'question' => 1,
            'answers' => 1,
            'created_at' => 1,
            'did_answer' => [
                '$gte' => [
                    [
                        '$size' => [
                            '$filter' => [
                                'input' => '$result',
                                'as' => 'item',
                                'cond' => [
                                    '$eq' => ['$$item.user_id', MongoHelper::objectId(id: $userId)]
                                ]
                            ]
                        ]
                    ],
                    1
                ]
            ]
        ]);

        $result = $this->collection->aggregate($query->build());
        return $result->toArray();
    }
}
