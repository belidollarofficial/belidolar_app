<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Akun admin bawaan. Dibuat sekali saja; password yang sudah diubah
     * lewat menu Ganti Password tidak akan ditimpa saat seeder dijalankan ulang.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'su-zonapay@gmail.com'],
            [
                'name' => 'Super Admin Beli Dolar',
                'password' => 'adminadmin',
            ],
        );

        if ($admin->email_verified_at === null) {
            $admin->forceFill(['email_verified_at' => now()])->save();
        }
    }
}
