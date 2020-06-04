<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ug:create-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a User Account';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->ask('Please enter the User\'s name?');
        $email = $this->ask('Please enter the User\'s email?');
        $password = $this->secret('Please enter a password for the User?');

        $validator = Validator::make([
            'email' => $email,
        ], [
            'email' => 'unique:users,email',
        ]);

        if ($validator->fails()) {
            $this->error('A user with that email address already exists.');
            return;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
        ]);

        $this->info('User "' . $user->name . '" was created.');
    }
}
