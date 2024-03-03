<?php

declare(strict_types=1);

namespace Survey\Ui\Api\Middlewares;

use Survey\Application\Interfaces\Cryptography\EncrypterInterface;
use Survey\Domain\Repository\AccountRepositoryInterface;
use Survey\Ui\Api\Adapter\Http\HttpHelper;
use Survey\Ui\Api\Adapter\Http\HttpResponse;

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(
        protected AccountRepositoryInterface $accountRepository,
        protected EncrypterInterface $encrypter
    ) {
    }

    public function __invoke(object $request): HttpResponse
    {
        try {
            if ( isset($request->Authorization) ) {
                $accessToken = $this->removeBearer($request->Authorization[0]);
                if ( $this->accountRepository->checkByToken(token: $accessToken) ) {
                    $userId = $this->encrypter->decrypt(ciphertext: $accessToken);
                    return HttpHelper::ok(['user_id' => $userId]);
                }
            }
            return HttpHelper::forbiden(error: 'Access Denied');
        } catch (\Exception $exception) {
            return HttpHelper::serverError(error: $exception);
        }
    }

    private function removeBearer(string $token): string
    {
        $accessToken = $token;
        if ( str_starts_with($accessToken, 'Bearer ') ) {
            $accessToken = substr($accessToken, 7);
        }
        return $accessToken;
    }
}
