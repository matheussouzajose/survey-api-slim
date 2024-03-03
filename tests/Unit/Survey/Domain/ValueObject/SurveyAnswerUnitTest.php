<?php

declare(strict_types=1);

namespace Tests\Unit\Survey\Domain\ValueObject;

use Survey\Domain\ValueObject\Image;
use Survey\Domain\ValueObject\SurveyAnswer;
use Tests\TestCase;

class SurveyAnswerUnitTest extends TestCase
{
    public function test_can_be_created_survey_answer()
    {
        $value = 'Answer';
        $surveyAnswer = new SurveyAnswer(answer: $value, image: new Image('/'));

        $this->assertEquals($value, $surveyAnswer->answer());
    }
}
