<?php

namespace App\Console\Commands;

use App\Models\BackendUser;
use Illuminate\Console\Command;

class CreateBackendUserCommand extends Command
{
    protected $signature = 'create:backend-user';
    protected $description = 'Create a new backend user';

    public function handle(): int
    {
        $name            = $this->ask('Name?');
        $email           = $this->ask('eMail?');
        $password        = $this->secret('Password?');
        $passwordConfirm = $this->secret('Confirm this password');

        if ($password !== $passwordConfirm) {
            $this->error('Password and confirmation are not equal!');

            return 1;
        }

        BackendUser::create([
            'name'              => $name,
            'email'             => $email,
            'email_verified_at' => now(),
            'password'          => bcrypt($password),
        ]);
        $this->info(sprintf('created user %s (email %s)', $name, $email));

        return 0;
    }
}
