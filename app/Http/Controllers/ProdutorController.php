<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProdutorController extends Controller
{
    public function dashboard()
    {
        if (!Auth::check() || Auth::user()->tipo !== 'Produtor') {
            return redirect()->route('login');
        }

        $produtorId = Auth::id();

        try {
            // Verificar se a tabela torras existe e tem a coluna status
            $tabelaExiste = DB::getSchemaBuilder()->hasTable('torras');
            $colunaStatusExiste = $tabelaExiste ? DB::getSchemaBuilder()->hasColumn('torras', 'status') : false;

            // Estatísticas básicas do produtor
            $estatisticas = [
                'total_torras' => $tabelaExiste ? DB::table('torras')
                    ->where('usuario_id', $produtorId)
                    ->count() : 0,

                'torras_avaliadas' => ($tabelaExiste && $colunaStatusExiste) ? DB::table('torras')
                    ->where('usuario_id', $produtorId)
                    ->where('status', 'avaliada')
                    ->count() : 0,

                'torras_nao_avaliadas' => ($tabelaExiste && $colunaStatusExiste) ? DB::table('torras')
                    ->where('usuario_id', $produtorId)
                    ->where('status', 'nao_avaliada')
                    ->count() : 0,

                'torras_aguardando' => ($tabelaExiste && $colunaStatusExiste) ? DB::table('torras')
                    ->where('usuario_id', $produtorId)
                    ->where('status', 'aguardando_avaliacao')
                    ->count() : 0,
            ];

            // Últimas torras (5 mais recentes) - versão segura
            $ultimasTorras = collect();
            if ($tabelaExiste) {
                $ultimasTorras = DB::table('torras as t')
                    ->where('t.usuario_id', $produtorId)
                    ->select(
                        't.id',
                        't.nome',
                        't.variedade',
                        't.fermentacao',
                        't.finalidade',
                        $colunaStatusExiste ? 't.status' : DB::raw("'nao_avaliada' as status"),
                        't.criado_em'
                    )
                    ->orderBy('t.criado_em', 'desc')
                    ->limit(5)
                    ->get();
            }

            // Melhores torras - só se tiver tabelas de análise
            $melhoresTorras = collect();
            $solicitacoesRecentes = collect();
            $distribuicaoVariedades = collect();

            if ($tabelaExiste && DB::getSchemaBuilder()->hasTable('solicitacoes_prova') && 
                DB::getSchemaBuilder()->hasTable('analise_sensorial')) {
                
                try {
                    // 5 melhores torras avaliadas (com maior nota final)
                    $melhoresTorras = DB::table('torras as t')
                        ->join('solicitacoes_prova as sp', 't.id', '=', 'sp.torra_id')
                        ->join('analise_sensorial as a', 'sp.id', '=', 'a.solicitacao_id')
                        ->where('t.usuario_id', $produtorId)
                        ->where('sp.status', 'concluida')
                        ->select(
                            't.nome as torra_nome',
                            't.variedade',
                            't.fermentacao',
                            'a.nota_final',
                            'sp.criado_em as data_avaliacao'
                        )
                        ->orderBy('a.nota_final', 'desc')
                        ->limit(5)
                        ->get();

                    // Solicitações de análise recentes (últimas 5)
                    $solicitacoesRecentes = DB::table('solicitacoes_prova as sp')
                        ->join('torras as t', 'sp.torra_id', '=', 't.id')
                        ->leftJoin('usuarios as analista', 'sp.analista_id', '=', 'analista.id')
                        ->where('t.usuario_id', $produtorId)
                        ->select(
                            'sp.*',
                            't.nome as torra_nome',
                            't.variedade',
                            't.finalidade',
                            'analista.nome as analista_nome',
                            'analista.sobrenome as analista_sobrenome'
                        )
                        ->orderBy('sp.criado_em', 'desc')
                        ->limit(5)
                        ->get();
                } catch (\Exception $e) {
                    // Se der erro nas queries avançadas, continua com dados básicos
                    \Log::warning('Erro nas queries avançadas do dashboard: ' . $e->getMessage());
                }
            }

            // Distribuição de variedades - versão segura
            if ($tabelaExiste) {
                try {
                    $distribuicaoVariedades = DB::table('torras')
                        ->where('usuario_id', $produtorId)
                        ->select('variedade', DB::raw('count(*) as total'))
                        ->groupBy('variedade')
                        ->get();
                } catch (\Exception $e) {
                    \Log::warning('Erro na query de distribuição de variedades: ' . $e->getMessage());
                }
            }

        } catch (\Exception $e) {
            \Log::error('Erro no dashboard do produtor: ' . $e->getMessage());
            
            // Em caso de erro, retornar dados vazios
            $estatisticas = [
                'total_torras' => 0,
                'torras_avaliadas' => 0,
                'torras_nao_avaliadas' => 0,
                'torras_aguardando' => 0,
            ];
            $ultimasTorras = collect();
            $melhoresTorras = collect();
            $solicitacoesRecentes = collect();
            $distribuicaoVariedades = collect();
        }

        return view('dashboard.produtor', compact(
            'estatisticas',
            'ultimasTorras',
            'melhoresTorras',
            'solicitacoesRecentes',
            'distribuicaoVariedades'
        ));
    }
            ->select('variedade', DB::raw('count(*) as total'))
            ->groupBy('variedade')
            ->get();

        // Remover funcionalidade de média por mês para simplificar
        // (removendo a query que estava causando erro)

        // Analistas disponíveis para solicitação
        $analistas = DB::table('usuarios')
            ->where('tipo', 'Analista')
            ->select('id', 'nome', 'sobrenome')
            ->orderBy('nome')
            ->get();

        return view('usuarios.dashboard', compact(
            'estatisticas',
            'ultimasTorras',
            'melhoresTorras',
            'solicitacoesRecentes',
            'distribuicaoVariedades',
            'analistas'
        ));
    }

    public function solicitarAnalise(Request $request)
    {
        if (!Auth::check() || Auth::user()->tipo !== 'Produtor') {
            return redirect()->route('login');
        }

        $request->validate([
            'torra_id' => 'required|exists:torras,id',
            'analista_id' => 'required|exists:usuarios,id',
            'notas' => 'nullable|string|max:500'
        ]);

        // Verificar se a torra pertence ao produtor logado
        $torra = DB::table('torras')
            ->where('id', $request->torra_id)
            ->where('usuario_id', Auth::id())
            ->first();

        if (!$torra) {
            return back()->with('error', 'Torra não encontrada ou não pertence a você.');
        }

        // Verificar se já existe uma solicitação pendente para esta torra
        $solicitacaoExistente = DB::table('solicitacoes_prova')
            ->where('torra_id', $request->torra_id)
            ->whereIn('status', ['Pendente', 'Em Análise'])
            ->first();

        if ($solicitacaoExistente) {
            return back()->with('error', 'Já existe uma solicitação pendente para esta torra.');
        }

        // Criar nova solicitação
        DB::table('solicitacoes_prova')->insert([
            'solicitante_id' => Auth::id(),
            'analista_id' => $request->analista_id,
            'torra_id' => $request->torra_id,
            'notas' => $request->notas,
            'status' => 'Pendente',
            'criado_em' => now(),
            'atualizado_em' => now()
        ]);

        // Atualizar status da torra para 'aguardando_avaliacao'
        DB::table('torras')
            ->where('id', $request->torra_id)
            ->update([
                'status' => 'aguardando_avaliacao'
            ]);

        return back()->with('success', 'Solicitação de análise enviada com sucesso!');
    }
}
