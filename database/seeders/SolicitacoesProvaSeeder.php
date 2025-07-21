<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SolicitacoesProvaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar se há dados necessários
        $usuarios = DB::table('usuarios')->get();
        $analistas = DB::table('usuarios')->where('tipo', 'Analista')->get();
        $torras = DB::table('torras')->get();

        if ($usuarios->isEmpty() || $analistas->isEmpty() || $torras->isEmpty()) {
            $this->command->error('Execute primeiro os seeders de usuários e torras.');
            return;
        }

        // Criar algumas solicitações de exemplo com diferentes status
        $solicitacoes = [
            [
                'solicitante_id' => $usuarios->first()->id,
                'analista_id' => $analistas->first()->id,
                'torra_id' => $torras->first()->id,
                'notas' => 'Primeira análise - verificar acidez e corpo',
                'status' => 'Em Análise',
                'criado_em' => Carbon::now()->subDays(2),
            ],
            [
                'solicitante_id' => $usuarios->first()->id,
                'analista_id' => $analistas->skip(1)->first()->id ?? $analistas->first()->id,
                'torra_id' => $torras->skip(1)->first()->id ?? $torras->first()->id,
                'notas' => 'Análise para certificação - importante verificar defeitos',
                'status' => 'Concluída',
                'criado_em' => Carbon::now()->subDays(5),
            ],
            [
                'solicitante_id' => $usuarios->first()->id,
                'analista_id' => $analistas->first()->id,
                'torra_id' => $torras->skip(2)->first()->id ?? $torras->first()->id,
                'notas' => 'Torra experimental - foco na doçura',
                'status' => 'Em Análise',
                'criado_em' => Carbon::now()->subDays(1),
            ],
            [
                'solicitante_id' => $usuarios->skip(1)->first()->id ?? $usuarios->first()->id,
                'analista_id' => $analistas->first()->id,
                'torra_id' => $torras->skip(3)->first()->id ?? $torras->first()->id,
                'notas' => null, // Sem notas
                'status' => 'Concluída',
                'criado_em' => Carbon::now()->subDays(7),
            ],
        ];

        // Verificar se já existem solicitações
        $existentes = DB::table('solicitacoes_prova')->count();

        if ($existentes > 0) {
            $this->command->info('Já existem solicitações na tabela. Limpando e recriando...');
            DB::table('solicitacoes_prova')->truncate();
        }

        DB::table('solicitacoes_prova')->insert($solicitacoes);

        $this->command->info('Solicitações de prova de exemplo criadas com sucesso!');
        $this->command->info('- 2 solicitações "Em Análise"');
        $this->command->info('- 2 solicitações "Concluída"');
    }
}
