<?php

declare(strict_types=1);

namespace Survey\Infrastructure\Cryptography\JwtAdapter;

use Survey\Application\Interfaces\Cryptography\EncrypterInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtAdapter implements EncrypterInterface
{
    public function __construct(protected string $secretKey)
    {
    }

    public function encrypt(string $plaintext): string
    {
        $payload = [
            'iss' => getenv('APP_URL'),
            'iat' => time(),
            'exp' => time() + 3600,
            'user_id' => $plaintext,
        ];

        return JWT::encode($payload, $this->secretKey, 'HS256');
    }

    public function decrypt(string $ciphertext): string
    {
        $decoded = JWT::decode($ciphertext, new Key($this->secretKey, 'HS256'));
        return $decoded->user_id;
    }
}
