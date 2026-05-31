<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'secretaria@tjdfunec.com.br'],
            [
                'name' => 'Secretaria TJD',
                'password' => Hash::make('tjd@2026'),
                'level' => 'super_admin',
            ]
        );

        User::where('email', 'secretaria@tjdfunec.com.br')->update(['level' => 'super_admin']);

        $this->call([
            ProcessoSeeder::class,
        ]);
    }
}
