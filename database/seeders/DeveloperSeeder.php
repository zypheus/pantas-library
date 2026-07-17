<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DeveloperSeeder extends Seeder
{
    /**
     * Seed the PANTAS developer console account.
     */
    public function run(): void
    {
        $password = env('DEVELOPER_SEED_PASSWORD', 'password');

        User::updateOrCreate(
            ['email' => env('DEVELOPER_SEED_EMAIL', 'developer@example.com')],
            [
                'fname' => env('DEVELOPER_SEED_FNAME', 'PANTAS'),
                'lname' => env('DEVELOPER_SEED_LNAME', 'Developer'),
                'password' => Hash::make($password),
                'role' => 'developer',
            ]
        );

        $this->command?->info(
            'Developer account ready: '
            .env('DEVELOPER_SEED_EMAIL', 'developer@example.com')
            .' / '.$password
            .' → /developer'
        );
    }
}
