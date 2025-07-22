<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário administrador
        DB::table('usuarios')->insert([
            'nome' => 'Admin',
            'sobrenome' => 'Sistema',
            'tipo' => 'Administrador',
            'email' => 'admin@torrainteligente.com',
            'senha' => Hash::make('admin123'),
            'criado_em' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Criar um analista de exemplo
        DB::table('usuarios')->insert([
            'nome' => 'João',
            'sobrenome' => 'Analista',
            'tipo' => 'Analista',
            'email' => 'analista@torrainteligente.com',
            'senha' => Hash::make('analista123'),
            'criado_em' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Criar um produtor de exemplo
        DB::table('usuarios')->insert([
            'nome' => 'Maria',
            'sobrenome' => 'Produtora',
            'tipo' => 'Produtor',
            'email' => 'produtor@torrainteligente.com',
            'senha' => Hash::make('produtor123'),
            'criado_em' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('✅ Dados iniciais criados com sucesso!');
        $this->command->info('👤 Admin: admin@torrainteligente.com / admin123');
        $this->command->info('🔬 Analista: analista@torrainteligente.com / analista123');
        $this->command->info('🌱 Produtor: produtor@torrainteligente.com / produtor123');
    }
}
