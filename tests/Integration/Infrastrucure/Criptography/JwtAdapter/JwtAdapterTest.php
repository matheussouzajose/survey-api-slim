<?php

declare(strict_types=1);

namespace Tests\Integration\Infrastrucure\Criptography\JwtAdapter;

use Survey\Infrastructure\Cryptography\JwtAdapter\JwtAdapter;
use Tests\TestCase;

class JwtAdapterTest extends TestCase
{
    public function test_should_be_return_token_encrypted()
    {
        $jwtAdapter = new JwtAdapter(secretKey: 'SECRET');
        $token = $jwtAdapter->encrypt(plaintext: 'user_id');

        $this->assertNotEquals('user_id', $token);
    }

    public function test_should_be_return_token_decrypted()
    {
        $jwtAdapter = new JwtAdapter(secretKey: 'SECRET');
        $token = $jwtAdapter->encrypt(plaintext: 'user_id');

        $decrypted = $jwtAdapter->decrypt(ciphertext: $token);

        $this->assertEquals('user_id', $decrypted);
    }
}
