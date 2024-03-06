<?php

declare(strict_types=1);

namespace Survey\Infrastructure\Persistence\MongoDb\Repository;

use MongoDB\Collection;
use Survey\Domain\Repository\SurveyResultRepositoryInterface;
use Survey\Infrastructure\Persistence\MongoDb\Helpers\MongoHelper;
use Survey\Infrastructure\Persistence\MongoDb\Helpers\QueryBuilder;

class SurveyResultRepository implements SurveyResultRepositoryInterface
{
    public Collection $collection;

    public function __construct()
    {
        $this->collection = MongoHelper::getCollection('surveyResults');
    }

    public function save(string $surveyId, string $userId, string $answer, string $date): void
    {
        $this->collection->findOneAndUpdate([
            'survey_id' => $surveyId,
            'user_id' => $userId,
        ],
            [
                '$set' => [
                    'answer' => $answer,
                    'created_at' => $date
                ]
            ],
            [
                'upsert' => true
            ]
        );
    }

    public function loadBySurveyId(string $surveyId, string $userId): ?array
    {
        $query = new QueryBuilder();
        $query->match(['survey_id' => $surveyId]);
        $query->group([
            '_id' => 0,
            'data' => [
                '$push' => '$$ROOT'
            ],
            'total' => [
                '$sum' => 1
            ],
        ]);
        $query->unwind(['path' => '$data']);
        $query->lookup([
            'from' => 'surveys',
            'foreignField' => '_id',
            'localField' => 'data.survey_id',
            'as' => 'survey'
        ]);
        $query->unwind(['path' => '$survey']);
        $query->group([
            '_id' => [
                'survey_id' => '$survey._id',
                'question' => '$survey.question',
                'created_at' => '$survey.created_at',
                'total' => '$total',
                'answer' => '$data.answer',
                'answers' => '$survey.answers'
            ],
            'count' => [
                '$sum' => 1
            ],
            'current_user_answer' => [
                '$push' => [
                    '$cond' => [['$eq' => ['$data.user_id', $userId]], '$data.answer', '$invalid']
                ]
            ]
        ]);
        $query->project([
            '_id' => 0,
            'survey_id' => '$_id.survey_id',
            'question' => '$_id.question',
            'created_at' => '$_id.created_at',
            'answers' => [
                '$map' => [
                    'input' => '$_id.answers',
                    'as' => 'item',
                    'in' => [
                        '$mergeObjects' => [
                            '$$item',
                            [
                                'count' => [
                                    '$cond' => [
                                        'if' => [
                                            '$eq' => ['$$item.answer', '$_id.answer']
                                        ],
                                        'then' => '$count',
                                        'else' => 0
                                    ]
                                ],
                                'percent' => [
                                    '$cond' => [
                                        'if' => [
                                            '$eq' => ['$$item.answer', '$_id.answer']
                                        ],
                                        'then' => [
                                            '$multiply' => [
                                                [
                                                    '$divide' => ['$count', '$_id.total']
                                                ],
                                                100
                                            ]
                                        ],
                                        'else' => 0
                                    ]
                                ],
                                'is_current_user_answer_count' => [
                                    '$cond' => [
                                        [
                                            '$eq' => [
                                                '$$item.answer',
                                                [
                                                    '$arrayElemAt' => ['$current_user_answer', 0]
                                                ]
                                            ]
                                        ],
                                        1,
                                        0
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]);
        $query->group([
            '_id' => [
                'survey_id' => '$survey_id',
                'question' => '$question',
                'created_at' => '$created_at'
            ],
            'answers' => [
                '$push' => '$answers'
            ]
        ]);
        $query->project([
            '_id' => 0,
            'survey_id' => '$_id.survey_id',
            'question' => '$_id.question',
            'created_at' => '$_id.created_at',
            'answers' => [
                '$reduce' => [
                    'input' => '$answers',
                    'initialValue' => [],
                    'in' => [
                        '$concatArrays' => ['$$value', '$$this']
                    ]
                ]
            ]
        ]);
        $query->unwind(['path' => '$answers']);
        $query->group([
            '_id' => [
                'survey_id' => '$survey_id',
                'question' => '$question',
                'created_at' => '$created_at',
                'answer' => '$answers.answer',
                'image' => '$answers.image'
            ],
            'count' => [
                '$sum' => '$answers.count'
            ],
            'percent' => [
                '$sum' => '$answers.percent'
            ],
            'is_current_user_answer_count' => [
                '$sum' => '$answers.is_current_user_answer_count'
            ]
        ]);
        $query->project([
            '_id' => 0,
            'survey_id' => '$_id.survey_id',
            'question' => '$_id.question',
            'created_at' => '$_id.created_at',
            'answer' => [
                'answer' => '$_id.answer',
                'image' => '$_id.image',
                'count' => '$count',
                'percent' => '$percent',
                'is_current_user_answer' => [
                    '$eq' => ['$is_current_user_answer_count', 1]
                ]
            ]
        ]);
        $query->sort([
            'answer.count' => -1
        ]);
        $query->group([
            '_id' => [
                'survey_id' => '$survey_id',
                'question' => '$question',
                'created_at' => '$created_at'
            ],
            'answers' => [
                '$push' => '$answer'
            ]
        ]);
        $query->project([
            '_id' => 0,
            'survey_id' => [
                '$toString' => '$_id.survey_id'
            ],
            'question' => '$_id.question',
            'created_at' => '$_id.created_at',
            'answers' => '$answers'
        ]);

        $result = ($this->collection->aggregate($query->build()))->toArray();
        return count($result) > 0 ? $result : null;
    }
}
