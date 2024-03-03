<?php

declare(strict_types=1);

namespace Tests\Unit\Survey\Domain\ValueObject;

use Survey\Domain\ValueObject\Image;
use Tests\TestCase;

class ImageUnitTest extends TestCase
{
    public function test_can_be_created_image()
    {
        $value = '/path';
        $image = new Image(path: $value);

        $this->assertEquals($value, $image->path());
    }
}
