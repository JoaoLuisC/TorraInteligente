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
        // Verificar se os usuários já existem antes de criar
        $usuarios = [
            [
                'nome' => 'Admin',
                'sobrenome' => 'Sistema',
                'tipo' => 'Administrador',
                'email' => 'admin@torrainteligente.com',
                'senha' => Hash::make('admin123'),
            ],
            [
                'nome' => 'João',
                'sobrenome' => 'Analista',
                'tipo' => 'Analista',
                'email' => 'analista@torrainteligente.com',
                'senha' => Hash::make('analista123'),
            ],
            [
                'nome' => 'Maria',
                'sobrenome' => 'Produtora',
                'tipo' => 'Produtor',
                'email' => 'produtor@torrainteligente.com',
                'senha' => Hash::make('produtor123'),
            ]
        ];

        foreach ($usuarios as $usuario) {
            // Verificar se o usuário já existe
            $existe = DB::table('usuarios')->where('email', $usuario['email'])->first();
            
            if (!$existe) {
                DB::table('usuarios')->insert([
                    'nome' => $usuario['nome'],
                    'sobrenome' => $usuario['sobrenome'],
                    'tipo' => $usuario['tipo'],
                    'email' => $usuario['email'],
                    'senha' => $usuario['senha'],
                    'criado_em' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                echo "✅ Usuário {$usuario['tipo']} criado: {$usuario['email']}\n";
            } else {
                echo "ℹ️  Usuário {$usuario['tipo']} já existe: {$usuario['email']}\n";
            }
        }

        $this->command->info('✅ Dados iniciais criados com sucesso!');
        $this->command->info('👤 Admin: admin@torrainteligente.com / admin123');
        $this->command->info('🔬 Analista: analista@torrainteligente.com / analista123');
        $this->command->info('🌱 Produtor: produtor@torrainteligente.com / produtor123');
    }
}
