<?php

declare(strict_types=1);

namespace Survey\Application\Command\Account;

class SignInCommand
{
    public function __construct(protected string $email, protected string $password)
    {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
