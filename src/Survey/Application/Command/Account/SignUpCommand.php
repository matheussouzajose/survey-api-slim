<?php

declare(strict_types=1);

namespace Survey\Application\Command\Account;

class SignUpCommand
{
    public function __construct(
        protected string $firstName,
        protected string $lastName,
        protected string $email,
        protected string $password,
        protected string $passwordConfirmation,
    ) {
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getPasswordConfirmation(): string
    {
        return $this->passwordConfirmation;
    }
}
