<?php

namespace Database\Seeders;

use App\Models\Kasir;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KasirSeeder extends Seeder
{
    public function run(): void
    {
        // Akun kasir demo (aman: password di-hash)
        $kasir = Kasir::updateOrCreate(
            ['email' => 'kasir@example.com'],
            [
                'nama_kasir' => 'Kasir Demo',
                'password'   => Hash::make('kasir123'),
            ]
        );

        // (Opsional) Tampilkan cred di console saat seeding
        $this->command?->info("Seeded kasir: {$kasir->email} | password: kasir123");
    }
}
