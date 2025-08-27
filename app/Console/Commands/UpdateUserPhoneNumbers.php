<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class UpdateUserPhoneNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-user-phone-numbers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all user phone numbers with random numbers.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            $phoneNumber = '01' . substr(str_shuffle('0123456789'), 0, 9);
            $user->phone_number = $phoneNumber;
            $user->save();
            $this->info('Updated user ' . $user->email . ' with phone number ' . $phoneNumber);
        }

        $this->info('All user phone numbers updated successfully.');
    }
}
