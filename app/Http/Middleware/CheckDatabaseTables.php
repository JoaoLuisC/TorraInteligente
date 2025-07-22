<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class CheckDatabaseTables
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            // Verificar se as tabelas essenciais existem
            $tablesStatus = [
                'usuarios' => DB::getSchemaBuilder()->hasTable('usuarios'),
                'torras' => DB::getSchemaBuilder()->hasTable('torras'),
                'solicitacoes_prova' => DB::getSchemaBuilder()->hasTable('solicitacoes_prova'),
                'analise_sensorial' => DB::getSchemaBuilder()->hasTable('analise_sensorial'),
            ];

            // Verificar colunas essenciais na tabela torras
            $torrasColumns = [];
            if ($tablesStatus['torras']) {
                $torrasColumns = [
                    'status' => DB::getSchemaBuilder()->hasColumn('torras', 'status'),
                    'observacoes_produtor' => DB::getSchemaBuilder()->hasColumn('torras', 'observacoes_produtor'),
                ];
            }

            // Verificar colunas essenciais na tabela usuarios
            $usuariosColumns = [];
            if ($tablesStatus['usuarios']) {
                $usuariosColumns = [
                    'imagem' => DB::getSchemaBuilder()->hasColumn('usuarios', 'imagem'),
                ];
            }

            // Compartilhar informações do banco com todas as views
            View::share('dbTables', $tablesStatus);
            View::share('torrasColumns', $torrasColumns);
            View::share('usuariosColumns', $usuariosColumns);

        } catch (\Exception $e) {
            // Se houver erro na verificação, assumir que as tabelas não existem
            View::share('dbTables', [
                'usuarios' => false,
                'torras' => false,
                'solicitacoes_prova' => false,
                'analise_sensorial' => false,
            ]);
            View::share('torrasColumns', []);
            View::share('usuariosColumns', []);
        }

        return $next($request);
    }
}
