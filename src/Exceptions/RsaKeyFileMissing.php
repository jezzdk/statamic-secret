<?php

namespace Jezzdk\StatamicSecret\Exceptions;

class RsaKeyFileMissing extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'Eloquent Encryption RSA keys cannot be found.';
}
