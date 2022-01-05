<?php

namespace Jezzdk\StatamicSecret\Filesystem;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Jezzdk\StatamicSecret\Contracts\RsaKeyHandler;
use Jezzdk\StatamicSecret\Exceptions\RsaKeyFileMissing;
use Statamic\Facades\Addon;

class RsaKeyStorageHandler implements RsaKeyHandler
{
    /**
     * Storage path for the Public Key File
     *
     * @var string
     */
    protected $public_key_path;

    /**
     * Storage path for the Private Key File
     *
     * @var string
     */
    protected $private_key_path;

    /**
     * Disk name for file storage
     *
     * @var string
     */
    protected $disk;

    /**
     * ApplicationKey constructor.
     */
    public function __construct()
    {
        $this->public_key_path = Config::get('statamic-secret.key.public', 'statamic_secret.pub');
        $this->private_key_path = Config::get('statamic-secret.key.private', 'statamic_secret');
        $this->disk = $this->getDisk();
    }

    /**
     * Have any RSA keys been generated
     *
     * @return bool
     */
    public function exists()
    {
        return $this->hasPrivateKey() && $this->hasPublicKey();
    }

    /**
     * A Private key file exists
     *
     * @return bool
     */
    public function hasPrivateKey()
    {
        return Storage::disk($this->disk)->exists($this->private_key_path);
    }

    /**
     * A Public key file exists
     *
     * @return bool
     */
    public function hasPublicKey()
    {
        return Storage::disk($this->disk)->exists($this->public_key_path);
    }

    /**
     * Save the generated RSA key to the storage location
     *
     * @param $public
     * @param $private
     */
    public function saveKey($public, $private)
    {
        Storage::disk($this->disk)->put($this->public_key_path, $public);
        Storage::disk($this->disk)->put($this->private_key_path, $private);
    }

    /**
     * Get the contents of the public key file
     *
     * @return string
     * @throws RsaKeyFileMissing
     */
    public function getPublicKey()
    {
        if (!$this->hasPublicKey()) {
            throw new RsaKeyFileMissing();
        }

        return Storage::disk($this->disk)->get($this->public_key_path);
    }

    /**
     * Get the contents of the private key file
     *
     * @return string
     * @throws RsaKeyFileMissing
     */
    public function getPrivateKey()
    {
        if (!$this->hasPrivateKey()) {
            throw new RsaKeyFileMissing();
        }

        return Storage::disk($this->disk)->get($this->private_key_path);
    }

    /**
     * Only the Pro edition can use any other disk than 'local'
     *
     * @return string
     */
    private function getDisk()
    {
        $addon = Addon::get('jezzdk/statamic-secret');

        if ($addon->edition() !== 'pro') {
            return 'local';
        }

        return Config::get('statamic-secret.disk', 'local');
    }
}
