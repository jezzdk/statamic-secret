<?php

namespace Jezzdk\StatamicSecret;

use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Support\Facades\Config;
use Jezzdk\StatamicSecret\Contracts\RsaKeyHandler;
use Jezzdk\StatamicSecret\Exceptions\RsaKeyFileMissing;
use Jezzdk\StatamicSecret\Filesystem\RsaKeyStorageHandler;
use phpseclib\Crypt\RSA;

class StatamicSecret implements Encrypter
{
    /**
     * @var RsaKeyHandler
     */
    private $handler;

    /**
     * ApplicationKey constructor.
     */
    public function __construct()
    {
        $this->handler = app()->make(RsaKeyStorageHandler::class);
    }

    /**
     * Have any RSA keys been generated
     *
     * @return bool
     */
    public function exists()
    {
        return $this->handler->exists();
    }

    /**
     * Generate a set of RSA Keys which will be used to encrypt the database fields
     */
    public function makeEncryptionKeys()
    {
        $key = $this->createKey(Config::get('statamic-secret.key.email'));

        $this->handler->saveKey($key['publickey'], $key['privatekey']);
    }

    /**
     * Create a digital set of RSA keys, defaulting to 4096-bit
     *
     * @param string $email
     * @return array
     */
    public function createKey($email = '')
    {
        $rsa = new RSA();
        $rsa->setPublicKeyFormat(RSA::PUBLIC_FORMAT_OPENSSH);
        $rsa->setComment($email);

        return $rsa->createKey(Config::get('statamic-secret.key.length', 4096));
    }

    /**
     * Helper function to ensure RSA options match for encrypting/decrypting
     *
     * @param $key
     * @return RSA
     */
    private function getRsa($key)
    {
        $rsa = new RSA();
        $rsa->loadKey($key);
        $rsa->setEncryptionMode(RSA::ENCRYPTION_OAEP);

        return $rsa;
    }

    /**
     * Encrypt a value using the RSA key
     *
     * @param $value
     * @param bool $serialize
     * @return false|string
     * @throws RsaKeyFileMissing
     */
    public function encrypt($value, $serialize = true)
    {
        return $this->getRsa($this->handler->getPublicKey())->encrypt($serialize ? serialize($value) : $value);
    }

    /**
     * Encrypt a string without serialization.
     *
     * @param  string  $value
     * @return string
     *
     * @throws RsaKeyFileMissing
     */
    public function encryptString($value)
    {
        return $this->encrypt($value, false);
    }

    /**
     * Decrypt a value using the RSA key
     *
     * @param $value
     * @param  bool  $unserialize
     * @return false|string|null
     * @throws RsaKeyFileMissing
     */
    public function decrypt($value, $unserialize = true)
    {
        if (empty($value)) {
            return null;
        }

        $decrypted = $this->getRsa($this->handler->getPrivateKey())->decrypt($value);

        return $unserialize ? unserialize($decrypted) : $decrypted;
    }

    /**
     * Decrypt the given string without unserialization.
     *
     * @param  string  $payload
     * @return string
     *
     * @throws RsaKeyFileMissing
     */
    public function decryptString($payload)
    {
        return $this->decrypt($payload, false);
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->handler, $name)) {
            return $this->handler->{$name}($arguments);
        }
    }
}
