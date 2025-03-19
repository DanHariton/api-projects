<?php

declare(strict_types=1);

namespace App\Service\Security;

use Random\RandomException;

final class PasswordGenerator
{
    private const LOWERCASE = 'abcdefghijklmnopqrstuvwxyz';
    private const UPPERCASE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private const DIGITS = '0123456789';
    private const SPECIAL_CHARS = '!@#$%^&*()-_+=<>?';

    public function generatePassword(int $length = 6, bool $includeDigits = true, bool $includeSpecialChars = false): string
    {
        $characters = self::LOWERCASE . self::UPPERCASE;
        if ($includeDigits) {
            $characters .= self::DIGITS;
        }
        if ($includeSpecialChars) {
            $characters .= self::SPECIAL_CHARS;
        }

        $password = '';
        $maxIndex = strlen($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[random_int(0, $maxIndex)];
        }

        return $password;
    }
}
