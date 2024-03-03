<?php

declare(strict_types=1);

namespace Survey\Domain\Entity;

use Survey\Domain\Exception\NotificationErrorException;
use Survey\Domain\Factory\AccountValidatorFactory;
use Survey\Domain\Validator\Account\AccountValidator;
use Survey\Domain\ValueObject\Email;
use Survey\Domain\ValueObject\ObjectId;

class Account extends Entity
{
    /**
     * @throws NotificationErrorException
     */
    public function __construct(
        protected string $firstName,
        protected string $lastName,
        protected Email $email,
        protected string $password,
        protected ?string $accessToken = null,
        protected ?ObjectId $id = null,
        protected ?\DateTimeInterface $createdAt = null,
        protected ?\DateTimeInterface $updatedAt = null,
    ) {
        parent::__construct();

        $this->id = $this->id ?? ObjectId::random();
        $this->createdAt = $this->createdAt ?? new \DateTime();

        $this->validation();
    }

    /**
     * @throws NotificationErrorException
     */
    private function validation(): void
    {
        AccountValidatorFactory::create()->validate(entity: $this);

        if ( $this->notificationErrors->hasErrors() ) {
            throw NotificationErrorException::messages(
                message: $this->notificationErrors->messages(AccountValidator::CONTEXT)
            );
        }
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function accessToken(): ?string
    {
        return $this->accessToken;
    }

    /**
     * @throws NotificationErrorException
     */
    public function changeName(string $firstName, string $lastName): void
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->updatedAt = new \DateTime();

        $this->validation();
    }

    /**
     * @throws NotificationErrorException
     */
    public function changeEmail(Email $email): void
    {
        $this->email = $email;
        $this->updatedAt = new \DateTime();

        $this->validation();
    }

    /**
     * @throws NotificationErrorException
     */
    public function changePassword(string $password): void
    {
        $this->password = $password;
        $this->updatedAt = new \DateTime();

        $this->validation();
    }

    /**
     * @throws NotificationErrorException
     */
    public function changeAccessToken(string $token): void
    {
        $this->accessToken = $token;
        $this->updatedAt = new \DateTime();

        $this->validation();
    }

    public function toArray(): array
    {
        return [
            '_id' => $this->id(),
            'first_name' => $this->firstName(),
            'last_name' => $this->lastName(),
            'email' => $this->email()->value(),
            'password' => $this->password(),
            'access_token' => $this->accessToken(),
            'created_at' => $this->createdAt(),
            'updated_at' => $this->createdAt(),
        ];
    }
}
