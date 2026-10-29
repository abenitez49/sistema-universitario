<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Persona;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crear el rol de Administrador
        $rolAdmin = Role::create(['name' => 'Administrador']);

        // 2. Crear la Persona base
        $persona = Persona::create([
            'documento' => '1234567', // Ajustado a "documento" según tu migración
            'nombres' => 'Alexis',    // Ajustado a "nombres" según tu migración
            'apellidos' => 'Benítez', // Ajustado a "apellidos" según tu migración
            'fecha_nacimiento' => '1995-01-01', // Agregado porque tu migración lo exige
        ]);

        // 3. Crear el Usuario enlazado a la persona (sin el campo name)
        $user = User::factory()->create([
            'persona_id' => $persona->id,
            'email' => 'admin@admin.com',   
        ]);

        // 4. Asignar el rol al usuario
        $user->assignRole($rolAdmin);
    }
}