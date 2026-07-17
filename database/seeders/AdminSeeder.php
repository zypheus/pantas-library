<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Seed the library admin account (staff catalog / admin dashboard).
     */
    public function run(): void
    {
        $password = env('ADMIN_SEED_PASSWORD', 'password');

        User::updateOrCreate(
            ['email' => env('ADMIN_SEED_EMAIL', 'admin@example.com')],
            [
                'fname' => env('ADMIN_SEED_FNAME', 'Library'),
                'lname' => env('ADMIN_SEED_LNAME', 'Admin'),
                'password' => Hash::make($password),
                'role' => 'admin',
            ]
        );

        $this->command?->info(
            'Admin account ready: '
            .env('ADMIN_SEED_EMAIL', 'admin@example.com')
            .' / '.$password
            .' → /book (catalog dashboard)'
        );
    }
}
