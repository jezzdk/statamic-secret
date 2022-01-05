<?php

namespace Jezzdk\StatamicSecret;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed decrypt(string $payload, bool $unserialize = true)
 * @method static string decryptString(string $payload)
 * @method static string encrypt(mixed $value, bool $serialize = true)
 * @method static string encryptString(string $value)
 * @method static string exists()
 * @method static string makeEncryptionKeys()
 *
 * @see \Jezzdk\StatamicSecret\StatamicSecret
 */
class StatamicSecretFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'statamic.secret';
    }
}
