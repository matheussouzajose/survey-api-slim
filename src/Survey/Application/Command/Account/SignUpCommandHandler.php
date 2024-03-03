<?php

declare(strict_types=1);

namespace Survey\Application\Command\Account;

use Survey\Application\Traits\ResponseTrait;
use Survey\Domain\Entity\Account;
use Survey\Domain\Event\Account\AccountCreatedEvent;
use Survey\Domain\Event\EventDispatcherInterface;
use Survey\Domain\Exception\NotificationErrorException;
use Survey\Domain\Repository\AccountRepositoryInterface;
use Survey\Domain\ValueObject\Email;

class SignUpCommandHandler
{
    use ResponseTrait;

    public function __construct(
        protected AccountRepositoryInterface $accountRepository,
        protected EventDispatcherInterface $eventDispatcher
    ) {
    }

    /**
     * @throws NotificationErrorException
     */
    public function __invoke(SignUpCommand $command): array
    {
        if ( $this->accountRepository->checkByEmail(email: $command->getEmail()) ) {
            return self::error(message: 'Email Already Exists');
        }

        if ( $command->getPassword() !== $command->getPasswordConfirmation() ) {
            return self::error(message: 'The passwords don\'t match');
        }

        $account = $this->addAccount(command: $command);

        $this->eventDispatcher->notify(event: new AccountCreatedEvent(account: $account));

        return self::success(message: 'Account Created');
    }

    /**
     * @throws NotificationErrorException
     */
    private function addAccount(SignUpCommand $command): Account
    {
        $email = new Email(value: $command->getEmail());
        $passwordHashed = password_hash(password: $command->getPassword(), algo: PASSWORD_DEFAULT);

        return $this->accountRepository->add(
            entity: new Account(
                firstName: $command->getFirstName(),
                lastName: $command->getLastName(),
                email: $email,
                password: $passwordHashed
            )
        );
    }
}
