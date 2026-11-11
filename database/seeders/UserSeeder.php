<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Crea los tres usuarios de prueba del resort (contraseña: password).
     */
    public function run(): void
    {
        /** @var array<int, array{name: string, email: string, age: int, is_admin: bool}> $users */
        $users = [
            ['name' => 'Directora del Resort', 'email' => 'admin@resort.test', 'age' => 40, 'is_admin' => true],
            ['name' => 'Cliente Adulto', 'email' => 'adulto@resort.test', 'age' => 25, 'is_admin' => false],
            ['name' => 'Cliente Menor', 'email' => 'menor@resort.test', 'age' => 15, 'is_admin' => false],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [...$user, 'password' => 'password'],
            );
        }
    }
}
