<?php

namespace App\Console\Commands;

use App\Models\User;
use Database\Factories\UserFactory;
use Exception;
use Illuminate\Console\Command;

class UpdateUserDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:update-details {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Updates a user's firstname, lastname, and timezone to new ransom ones.";

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        try {
            $userId = (int) $this->argument('user');

            $newUserDetails = UserFactory::randomDetails();

            User::whereId($userId)->update($newUserDetails);
        } catch (Exception $exception) {
            report($exception);

            throw new Exception('System Error');
        }
    }
}
