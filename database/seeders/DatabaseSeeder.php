<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Category::truncate();
        Category::create([
            'name' => 'Otros Gastos',
            'description' => 'Categoría general para gastos sin especificar',
            'type' => 'spending', // o 'income'
        ]);
        Category::create([
            'name' => 'Otros Ingresos',
            'description' => 'Categoría general para ingresos sin especificar',
            'type' => 'income',
        ]);
        Category::create([
            'name' => 'Salario',
            'description' => 'Ingreso por trabajo',
            'type' => 'income',
        ]);
        Category::create([
            'name' => 'Cuentas por cobrar',
            'description' => 'Ingreso por préstamos realizados',
            'type' => 'income',
        ]);
        Category::create([
            'name' => 'Ingreso por transferencia entre cuentas',
            'description' => 'Ingreso por transferencia entre cuentas',
            'type' => 'income',
        ]);
        Category::create([
            'name' => 'Mercado',
            'description' => 'Gastos relacionados a compras en el mercado',
            'type' => 'spending',
        ]);
        Category::create([
            'name' => 'Mascotas',
            'description' => 'Gastos relacionados las mascotas: alimentación, veterinario, etc.',
            'type' => 'spending',
        ]);
        Category::create([
            'name' => 'Cuentas por pagar',
            'description' => 'Gastos relacionados a pagos pendientes a otras personas o empresas',
            'type' => 'spending',
        ]);
        Category::create([
            'name' => 'Alquiler',
            'description' => 'Gastos relacionados al alquiler de vivienda y/o oficina',
            'type' => 'spending',
        ]);
        Category::create([
            'name' => 'Internet y telefonía',
            'description' => 'Gastos relacionados a servicios de internet y telefonía',
            'type' => 'spending',
        ]);
        Category::create([
            'name' => 'Suscripciones',
            'description' => 'Gastos relacionados a suscripciones a servicios digitales: spotify, netflix, etc.',
            'type' => 'spending',
        ]);
        Category::create([
            'name' => 'Salud',
            'description' => 'Gastos relacionados a salud: medicamentos, consultas médicas, etc.',
            'type' => 'spending',
        ]);
        Category::create([
            'name' => 'Belleza y cuidado personal',
            'description' => 'Gastos relacionados a belleza y cuidado personal: peluquería, cosméticos, etc.',
            'type' => 'spending',
        ]);
        Category::create([
            'name' => 'Transferencia entre cuentas',
            'description' => 'Transacciones de Transferencia entre cuentas',
            'type' => 'transfer',
        ]);
    }
}
