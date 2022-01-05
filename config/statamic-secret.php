<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'key' => [
        /**
         * The RSA Key Length
         */
        'length' => env('STATAMIC_SECRET_KEY_LENGTH', 4096),

        /**
         * The filename for the RSA public key
         */
        'public' => env('STATAMIC_SECRET_KEY_PUBLIC', 'statamic_secret.pub'),

        /**
         * The filename for the RSA private key
         */
        'private' => env('STATAMIC_SECRET_KEY_PRIVATE', 'statamic_secret'),
    ],

    /**
     * The path in Storage where the keys should be saved.
     * DO NOT SET THIS UNDER app/public or anywhere publicly accessible.
     */
    'disk' => env('STATAMIC_SECRET_DISK', 'local'),
];
