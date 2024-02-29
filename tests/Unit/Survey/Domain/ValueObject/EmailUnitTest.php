<?php

declare(strict_types=1);

namespace Tests\Unit\Survey\Domain\ValueObject;

use Survey\Domain\Exception\EmailException;
use Survey\Domain\ValueObject\Email;
use Tests\TestCase;

class EmailUnitTest extends TestCase
{
    public function test_throws_error_when_given_an_invalid_email()
    {
        $objectId = 'invalid';
        $this->expectExceptionObject(EmailException::itemInvalid(value: $objectId));

        new Email(value: $objectId);
    }

    public function test_can_be_created_email()
    {
        $value = 'valid@mail.com';
        $email = new Email(value: $value);

        $this->assertEquals($value, $email->value());
    }
}
