<?php

namespace Jezzdk\StatamicSecret\Console\Commands;

use Illuminate\Console\Command;
use Jezzdk\StatamicSecret\StatamicSecretFacade;
use Statamic\Console\RunsInPlease;

class GenerateKeys extends Command
{
    use RunsInPlease;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'secret:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This generates the application level RSA keys which are used to encrypt the application data at rest';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // check for existing keys - do not overwrite
        if (StatamicSecretFacade::exists()) {
            $this->warn('Application RSA keys are already set');
            $this->warn('**********************************************************************');
            $this->warn('* If you reset your keys you will lose access to any encrypted data. *');
            $this->warn('**********************************************************************');

            if ($this->confirm('Do you wish to reset your encryption keys?') === false) {
                $this->info('RSA Keys have not been overwritten');
                return;
            }
        }

        $this->info('Creating RSA Keys for Application');

        StatamicSecretFacade::makeEncryptionKeys();
    }
}
