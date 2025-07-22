<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Torra;

class AnaliseController extends Controller
{
    public function dashboard()
    {
        if (!Auth::check() || Auth::user()->tipo !== 'Analista') {
            return redirect()->route('login');
        }

        $analistaId = Auth::id();

        try {
            // Verificar se as tabelas existem
            $solicitacoesExiste = DB::getSchemaBuilder()->hasTable('solicitacoes_prova');
            $analisesExiste = DB::getSchemaBuilder()->hasTable('analise_sensorial');
            $torrasExiste = DB::getSchemaBuilder()->hasTable('torras');

            // Estatísticas com verificação de tabelas
            $estatisticas = [
                'pendentes' => ($solicitacoesExiste && DB::getSchemaBuilder()->hasColumn('solicitacoes_prova', 'status')) ?
                    DB::table('solicitacoes_prova')->where('status', 'Pendente')->count() : 0,

                'em_analise' => ($solicitacoesExiste && DB::getSchemaBuilder()->hasColumn('solicitacoes_prova', 'status') && DB::getSchemaBuilder()->hasColumn('solicitacoes_prova', 'analista_id')) ?
                    DB::table('solicitacoes_prova')
                        ->where('status', 'Em Análise')
                        ->where('analista_id', $analistaId)
                        ->count() : 0,

                'concluidas' => ($solicitacoesExiste && DB::getSchemaBuilder()->hasColumn('solicitacoes_prova', 'status') && DB::getSchemaBuilder()->hasColumn('solicitacoes_prova', 'analista_id')) ?
                    DB::table('solicitacoes_prova')
                        ->where('status', 'Concluída')
                        ->where('analista_id', $analistaId)
                        ->count() : 0,
            ];

            // Solicitações pendentes (últimas 5) - só se as tabelas existirem
            $solicitacoesPendentes = collect();
            if ($solicitacoesExiste && $torrasExiste && DB::getSchemaBuilder()->hasColumn('solicitacoes_prova', 'status')) {
                try {
                    $solicitacoesPendentes = DB::table('solicitacoes_prova as sp')
                        ->join('torras as t', 'sp.torra_id', '=', 't.id')
                        ->join('usuarios as produtor', 't.usuario_id', '=', 'produtor.id')
                        ->where('sp.status', 'Pendente')
                        ->select(
                            'sp.*',
                            't.nome as torra_nome',
                            't.variedade',
                            't.finalidade',
                            'produtor.nome as produtor_nome'
                        )
                        ->orderBy('sp.criado_em', 'desc')
                        ->limit(5)
                        ->get();
                } catch (\Exception $e) {
                    \Log::warning('Erro nas solicitações pendentes: ' . $e->getMessage());
                }
            }

            // Análises recentes (últimas 5) - só se as tabelas existirem
            $analisesRecentes = collect();
            if ($analisesExiste && $solicitacoesExiste && $torrasExiste && DB::getSchemaBuilder()->hasColumn('solicitacoes_prova', 'analista_id')) {
                try {
                    $analisesRecentes = DB::table('analise_sensorial as a')
                        ->join('solicitacoes_prova as sp', 'a.solicitacao_id', '=', 'sp.id')
                        ->join('torras as t', 'sp.torra_id', '=', 't.id')
                        ->join('usuarios as produtor', 't.usuario_id', '=', 'produtor.id')
                        ->where('sp.analista_id', $analistaId)
                        ->select(
                            'a.*',
                            't.nome as torra_nome',
                            't.variedade',
                            't.finalidade',
                            'produtor.nome as produtor_nome',
                            'a.created_at as data_analise'
                        )
                        ->orderBy('a.created_at', 'desc')
                        ->limit(5)
                        ->get();
                } catch (\Exception $e) {
                    \Log::warning('Erro nas análises recentes: ' . $e->getMessage());
                }
            }

        } catch (\Exception $e) {
            \Log::error('Erro no dashboard do analista: ' . $e->getMessage());

            // Em caso de erro, dados básicos
            $estatisticas = [
                'pendentes' => 0,
                'em_analise' => 0,
                'concluidas' => 0,
            ];
            $solicitacoesPendentes = collect();
            $analisesRecentes = collect();
        }

        return view('analise.dashboard', compact(
            'estatisticas',
            'solicitacoesPendentes',
            'analisesRecentes'
        ));
    }

    public function pendentes()
    {
        if (!Auth::check() || Auth::user()->tipo !== 'Analista') {
            return redirect()->route('login');
        }

        try {
            // Verificar se as tabelas existem
            if (!DB::getSchemaBuilder()->hasTable('solicitacoes_prova') ||
                !DB::getSchemaBuilder()->hasTable('torras') ||
                !DB::getSchemaBuilder()->hasColumn('solicitacoes_prova', 'status')) {

                // Se as tabelas não existem, retornar vista vazia
                $solicitacoesPendentes = collect()->paginate(10);
                return view('analise.pendentes', compact('solicitacoesPendentes'));
            }

            // Buscar solicitações pendentes (sem analista atribuído ou para este analista)
            $solicitacoesPendentes = DB::table('solicitacoes_prova as sp')
                ->join('torras as t', 'sp.torra_id', '=', 't.id')
                ->join('usuarios as produtor', 't.usuario_id', '=', 'produtor.id')
                ->where('sp.status', 'Pendente')
                ->select(
                    'sp.*',
                    't.nome as torra_nome',
                    't.variedade as torra_variedade',
                    't.fermentacao as torra_fermentacao',
                    't.finalidade as torra_finalidade',
                    't.densidade as torra_densidade',
                    'produtor.nome as produtor_nome',
                    'produtor.sobrenome as produtor_sobrenome'
                )
                ->orderBy('sp.criado_em', 'asc')
                ->paginate(10);

        } catch (\Exception $e) {
            \Log::error('Erro ao buscar solicitações pendentes: ' . $e->getMessage());
            $solicitacoesPendentes = collect()->paginate(10);
        }

        return view('analise.pendentes', compact('solicitacoesPendentes'));
    }

    public function historico()
    {
        if (!Auth::check() || Auth::user()->tipo !== 'Analista') {
            return redirect()->route('login');
        }

        try {
            // Verificar se as tabelas existem
            if (!DB::getSchemaBuilder()->hasTable('analise_sensorial') ||
                !DB::getSchemaBuilder()->hasTable('solicitacoes_prova') ||
                !DB::getSchemaBuilder()->hasTable('torras') ||
                !DB::getSchemaBuilder()->hasColumn('solicitacoes_prova', 'analista_id')) {

                // Se as tabelas não existem, retornar vista vazia
                $analises = collect()->paginate(10);
                return view('analise.historico', compact('analises'));
            }

            // Buscar histórico de análises realizadas
            $analises = DB::table('analise_sensorial as a')
                ->join('solicitacoes_prova as sp', 'a.solicitacao_id', '=', 'sp.id')
                ->join('torras as t', 'sp.torra_id', '=', 't.id')
                ->join('usuarios as produtor', 't.usuario_id', '=', 'produtor.id')
                ->where('sp.analista_id', Auth::id())
                ->select(
                    'a.*',
                    'sp.id as solicitacao_id',
                    't.nome as torra_nome',
                    't.variedade as torra_variedade',
                    't.finalidade as torra_finalidade',
                    'produtor.nome as produtor_nome',
                    'produtor.sobrenome as produtor_sobrenome'
                )
                ->orderBy('a.created_at', 'desc')
                ->paginate(10);

        } catch (\Exception $e) {
            \Log::error('Erro ao buscar histórico de análises: ' . $e->getMessage());
            $analises = collect()->paginate(10);
        }

        return view('analise.historico', compact('analises'));
    }
                't.fermentacao as torra_fermentacao',
                't.finalidade as torra_finalidade',
                't.densidade as torra_densidade',
                'produtor.nome as produtor_nome',
                'produtor.sobrenome as produtor_sobrenome',
                'a.created_at as data_analise'
            )
            ->orderBy('a.created_at', 'desc')
            ->paginate(10);

        // Converter criado_em para Carbon
        $analises->getCollection()->transform(function ($analise) {
            $analise->data_analise = \Carbon\Carbon::parse($analise->data_analise);
            return $analise;
        });

        return view('analise.historico', compact('analises'));
    }

    public function analisar($id)
    {
        if (!Auth::check() || Auth::user()->tipo !== 'Analista') {
            return redirect()->route('login');
        }

        // Buscar solicitação específica
        $solicitacao = DB::table('solicitacoes_prova as sp')
            ->join('torras as t', 'sp.torra_id', '=', 't.id')
            ->join('usuarios as produtor', 't.usuario_id', '=', 'produtor.id')
            ->where('sp.id', $id)
            ->select(
                'sp.*',
                't.nome as torra_nome',
                't.variedade as torra_variedade',
                't.fermentacao as torra_fermentacao',
                't.finalidade as torra_finalidade',
                't.densidade as torra_densidade',
                'produtor.nome as produtor_nome',
                'produtor.sobrenome as produtor_sobrenome'
            )
            ->first();

        if (!$solicitacao) {
            return redirect()->route('analise.pendentes')
                ->with('error', 'Solicitação não encontrada.');
        }

        // Se a solicitação estiver Pendente, marcar como "Em Análise"
        if ($solicitacao->status === 'Pendente') {
            DB::table('solicitacoes_prova')
                ->where('id', $id)
                ->update([
                    'status' => 'Em Análise',
                    'analista_id' => Auth::id(),
                    'atualizado_em' => now()
                ]);

            // Recarregar dados da solicitação com o status atualizado
            $solicitacao->status = 'Em Análise';
        }

        // Verificar se já existe análise sensorial
        $analiseExistente = DB::table('analise_sensorial')
            ->where('solicitacao_id', $id)
            ->first();

        return view('analise.realizar', compact('solicitacao', 'analiseExistente'));
    }

    public function salvarAnalise(Request $request, $id)
    {
        if (!Auth::check() || Auth::user()->tipo !== 'Analista') {
            return redirect()->route('login');
        }

        $request->validate([
            'aroma_po' => 'required|numeric|min:0|max:10',
            'fragrancia_cafe' => 'required|numeric|min:0|max:10',
            'sabor' => 'required|numeric|min:0|max:10',
            'acidez' => 'required|numeric|min:0|max:10',
            'corpo' => 'required|numeric|min:0|max:10',
            'retro_gosto' => 'required|numeric|min:0|max:10',
            'equilibrio' => 'required|numeric|min:0|max:10',
            'docura' => 'required|numeric|min:0|max:10',
            'uniformidade' => 'required|numeric|min:0|max:10',
            'defeitos' => 'required|numeric|min:0|max:10',
            'balanceamento' => 'required|numeric|min:0|max:10',
        ]);

        // Buscar solicitação
        $solicitacao = DB::table('solicitacoes_prova')
            ->where('id', $id)
            ->first();

        if (!$solicitacao) {
            return redirect()->route('analise.pendentes')
                ->with('error', 'Solicitação não encontrada.');
        }

        try {
            DB::beginTransaction();

            // Calcular nota final
            $aromaFinal = ($request->aroma_po + $request->fragrancia_cafe) / 2;
            $notaFinal = $aromaFinal + $request->sabor + $request->acidez +
                        $request->corpo + $request->retro_gosto + $request->equilibrio +
                        $request->docura + $request->uniformidade + $request->defeitos +
                        $request->balanceamento;

            // Inserir ou atualizar análise sensorial
            DB::table('analise_sensorial')->updateOrInsert(
                ['solicitacao_id' => $id],
                [
                    'aroma_po' => $request->aroma_po,
                    'fragrancia_cafe' => $request->fragrancia_cafe,
                    'sabor' => $request->sabor,
                    'acidez' => $request->acidez,
                    'corpo' => $request->corpo,
                    'retro_gosto' => $request->retro_gosto,
                    'equilibrio' => $request->equilibrio,
                    'docura' => $request->docura,
                    'uniformidade' => $request->uniformidade,
                    'defeitos' => $request->defeitos,
                    'balanceamento' => $request->balanceamento,
                    'nota_final' => round($notaFinal, 2),
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            // Atualizar status da solicitação
            DB::table('solicitacoes_prova')
                ->where('id', $id)
                ->update([
                    'status' => 'Concluída',
                    'atualizado_em' => now()
                ]);

            // Atualizar status da torra para 'avaliada'
            DB::table('torras')
                ->where('id', $solicitacao->torra_id)
                ->update([
                    'status' => 'avaliada'
                ]);

            DB::commit();

            return redirect()->route('analise.pendentes')
                ->with('success', 'Análise realizada com sucesso!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Erro ao salvar análise: ' . $e->getMessage());
        }
    }
}
