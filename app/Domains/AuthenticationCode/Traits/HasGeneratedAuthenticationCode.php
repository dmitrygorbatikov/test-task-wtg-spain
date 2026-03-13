<?php

declare(strict_types=1);

namespace App\Domains\AuthenticationCode\Traits;

use App\Domains\AuthenticationCode\Models\AuthenticationCode;
use RuntimeException;
use Throwable;

trait HasGeneratedAuthenticationCode
{
    public function generateEmailVerificationCode(): string
    {
        try {
            $letters = 'abcdefghijklmnopqrstuvwxyz';
            $numbers = '0123456789';

            $lettersLength = mb_strlen($letters) - 1;
            $numbersLength = mb_strlen($numbers) - 1;
            $code = '';

            for ($i = 0; $i < AuthenticationCode::EMAIL_CODE_SIZE; ++$i) {
                $isNumber = random_int(0, 1);

                if ($isNumber) {
                    $position = random_int(0, $numbersLength);
                    $code .= $numbers[$position];
                } else {
                    $position = random_int(0, $lettersLength);
                    $code .= $letters[$position];
                }
            }

            return $code;
        } catch (Throwable $e) {
            throw new RuntimeException(sprintf(
                'Failed to generate email authentication code of length %d at position %d: %s',
                AuthenticationCode::EMAIL_CODE_SIZE,
                $i ?? 0,
                $e->getMessage()
            ), previous: $e);
        }
    }

    public function generate4DigitCodeForUser(): int
    {
        try {
            return random_int(1000, 9999);
        } catch (Throwable $e) {
            throw new RuntimeException(sprintf(
                'Failed to generate 4-digit authentication code: %s',
                $e->getMessage()
            ), previous: $e);
        }
    }
}
