<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TorrasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Primeiro, vamos verificar se há usuários na tabela
        $usuarios = DB::table('usuarios')->get();

        if ($usuarios->isEmpty()) {
            $this->command->error('Não há usuários na tabela. Execute o seeder de usuários primeiro.');
            return;
        }

        // Pegar alguns usuários produtores para associar as torras
        $produtores = DB::table('usuarios')->where('tipo', 'Produtor')->pluck('id');

        if ($produtores->isEmpty()) {
            $this->command->error('Não há produtores na tabela. Criando com usuários existentes...');
            $produtores = $usuarios->pluck('id');
        }

        $torras = [
            [
                'usuario_id' => $produtores->random(),
                'nome' => 'Torra Clara Bourbon',
                'variedade' => 'Bourbon',
                'densidade' => 0.75,
                'fermentacao' => 'Natural',
                'finalidade' => 'Espresso',
                'avaliada' => false,
                'avaliador_id' => null,
                'avaliada_em' => null,
                'criado_em' => Carbon::now()->subDays(5),
            ],
            [
                'usuario_id' => $produtores->random(),
                'nome' => 'Torra Média Arábico',
                'variedade' => 'Arábico',
                'densidade' => 0.68,
                'fermentacao' => 'CD',
                'finalidade' => 'Filtro',
                'avaliada' => false,
                'avaliador_id' => null,
                'avaliada_em' => null,
                'criado_em' => Carbon::now()->subDays(3),
            ],
            [
                'usuario_id' => $produtores->random(),
                'nome' => 'Torra Fermentada Bourbon',
                'variedade' => 'Bourbon',
                'densidade' => 0.82,
                'fermentacao' => 'Fermentado',
                'finalidade' => 'Amostra',
                'avaliada' => false,
                'avaliador_id' => null,
                'avaliada_em' => null,
                'criado_em' => Carbon::now()->subDays(1),
            ],
            [
                'usuario_id' => $produtores->random(),
                'nome' => 'Torra Especial Arábico',
                'variedade' => 'Arábico',
                'densidade' => 0.71,
                'fermentacao' => 'Natural',
                'finalidade' => 'Espresso',
                'avaliada' => true, // Esta já foi avaliada
                'avaliador_id' => DB::table('usuarios')->where('tipo', 'Analista')->first()->id ?? null,
                'avaliada_em' => Carbon::now()->subDays(2),
                'criado_em' => Carbon::now()->subDays(7),
            ],
        ];

        DB::table('torras')->insert($torras);

        $this->command->info('Torras de exemplo criadas com sucesso!');
    }
}
