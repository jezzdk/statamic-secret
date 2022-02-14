# Statamic Secret

> With this addon you can store text that will be encrypted using generated RSA keys. The text will be decrypted in the control panel and when augmented, but no one will have a clue what the value is just by looking at the content files.

## Features

This addon:

- Adds a fieldtype that enables the user to store encrypted single- or multiline text
- Provides a command for generating RSA keys
- Provides a way to store the RSA keys in any disk that has been configured in `config/filesystem.php` (Pro only)

## How to Install

You can search for this addon in the `Tools > Addons` section of the Statamic control panel and click **install**, or run the following command from your project root:

```bash
composer require jezzdk/statamic-secret
```

You can set some .env variables to change options (the values below are the defaults):

```
// The RSA key length in bytes
STATAMIC_SECRET_KEY_LENGTH=4096 

// The path and filename for the public key
STATAMIC_SECRET_KEY_PUBLIC=statamic_secret.pub

// The path and filename for the private key
STATAMIC_SECRET_KEY_PRIVATE=statamic_secret 

// The filesystem disk to use as storage (Pro only)
STATAMIC_SECRET_DISK=local
```

You can publish the config file with:

```
php artisan vendor:publish --tag=statamic-secret-config
```

## How to Use

After installing the addon, you can generate the keys with the command below. You can use the same command to re-generate them for any reason:

```
php please secret:generate
```

You will be prompted to overwrite the keys if files with the configured names already exist.

> You should NOT commit the keys to version control. Nor should you place them anywhere publicly accessible.

Once installed, a new `Secret` fieldtype is selectable from the field picker.

***

Disclaimer: I've borrowed some code regarding key generation from the excellent [Eloquent Encryption](https://github.com/RichardStyles/EloquentEncryption) package by [@RichardStyles](https://github.com/RichardStyles). Go check it out!
