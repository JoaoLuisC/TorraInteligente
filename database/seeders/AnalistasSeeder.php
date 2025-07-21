<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AnalistasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar se já existem analistas
        $analistas = DB::table('usuarios')->where('tipo', 'Analista')->count();

        if ($analistas > 0) {
            $this->command->info('Já existem analistas na base de dados.');
            return;
        }

        $analistas = [
            [
                'nome' => 'Dr. Carlos',
                'sobrenome' => 'Silva',
                'tipo' => 'Analista',
                'email' => 'carlos.analista@michelangelo.com',
                'senha' => Hash::make('123456'),
                'criado_em' => Carbon::now(),
            ],
            [
                'nome' => 'Dra. Maria',
                'sobrenome' => 'Santos',
                'tipo' => 'Analista',
                'email' => 'maria.analista@michelangelo.com',
                'senha' => Hash::make('123456'),
                'criado_em' => Carbon::now(),
            ],
            [
                'nome' => 'Prof. João',
                'sobrenome' => 'Oliveira',
                'tipo' => 'Analista',
                'email' => 'joao.analista@michelangelo.com',
                'senha' => Hash::make('123456'),
                'criado_em' => Carbon::now(),
            ],
        ];

        DB::table('usuarios')->insert($analistas);

        $this->command->info('Analistas de exemplo criados com sucesso!');
        $this->command->info('Senhas padrão: 123456');
    }
}
