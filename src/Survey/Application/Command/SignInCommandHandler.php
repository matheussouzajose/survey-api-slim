<?php

declare(strict_types=1);

namespace Survey\Application\Command;

use Survey\Application\Interfaces\Cryptography\EncrypterInterface;
use Survey\Application\Traits\ResponseTrait;
use Survey\Domain\Repository\AccountRepositoryInterface;

class SignInCommandHandler
{
    use ResponseTrait;
    public function __construct(
        protected AccountRepositoryInterface $accountRepository,
        protected EncrypterInterface $encrypter
    ) {
    }

    public function __invoke(SignInCommand $command): array
    {
        $account = $this->accountRepository->loadByEmail(email: $command->getEmail());
        if ( !$account ) {
            return self::error(message: 'Credentials invalid');
        }


        if ( !password_verify(password: $command->getPassword(), hash: $account->password()) ) {
            return self::error(message: 'Credentials invalid');
        }

        $accessToken = $this->encrypter->encrypt(plaintext: $account->id());
        $account->changeAccessToken(token: $accessToken);

        $this->accountRepository->updateAccessToken(entity: $account);

        return self::success(body: [
            'authentication' => [
                'access_token' => $accessToken,
                'token_type' => 'Bearer'
            ],
            'name' => $account->firstName()
        ]);
    }
}
