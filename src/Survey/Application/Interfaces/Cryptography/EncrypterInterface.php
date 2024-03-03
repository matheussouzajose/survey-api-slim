<?php

declare(strict_types=1);

namespace Survey\Application\Interfaces\Cryptography;

interface EncrypterInterface
{
    public function encrypt(string $plaintext): string;

    public function decrypt(string $ciphertext): string;
}
