<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Torra;

class TorrasController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Buscar todas as torras do usuário logado com informações do avaliador e dados completos da análise
        $query = DB::table('torras')
            ->leftJoin('usuarios as avaliador', 'torras.avaliador_id', '=', 'avaliador.id')
            ->leftJoin('solicitacoes_prova as sp', function($join) {
                $join->on('torras.id', '=', 'sp.torra_id')
                     ->where('sp.status', '=', 'Concluída');
            })
            ->leftJoin('analise_sensorial as a', 'sp.id', '=', 'a.solicitacao_id')
            ->leftJoin('usuarios as produtor', 'torras.usuario_id', '=', 'produtor.id')
            ->select(
                'torras.*',
                'avaliador.nome as avaliador_nome',
                'avaliador.sobrenome as avaliador_sobrenome',
                'produtor.nome as produtor_nome',
                'produtor.sobrenome as produtor_sobrenome',
                'a.nota_final',
                'a.aroma_po',
                'a.fragrancia_cafe',
                'a.sabor',
                'a.acidez',
                'a.corpo',
                'a.retro_gosto',
                'a.equilibrio',
                'a.docura',
                'a.uniformidade',
                'a.defeitos',
                'a.balanceamento',
                'sp.notas as observacoes_produtor'
            )
            ->where('torras.usuario_id', Auth::id());

        // Aplicar filtro de pesquisa se fornecido
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('torras.nome', 'LIKE', "%{$search}%")
                  ->orWhere('torras.variedade', 'LIKE', "%{$search}%")
                  ->orWhere('torras.fermentacao', 'LIKE', "%{$search}%")
                  ->orWhere('torras.finalidade', 'LIKE', "%{$search}%");
            });
        }

        // Aplicar filtro de avaliação se fornecido
        if ($request->has('filtro_avaliacao') && $request->filtro_avaliacao !== '') {
            if ($request->filtro_avaliacao === 'avaliadas') {
                $query->where('torras.status', 'avaliada');
            } elseif ($request->filtro_avaliacao === 'nao_avaliadas') {
                $query->where('torras.status', 'nao_avaliada');
            } elseif ($request->filtro_avaliacao === 'aguardando_avaliacao') {
                $query->where('torras.status', 'aguardando_avaliacao');
            }
            // Se for 'todas', não aplicamos nenhum filtro adicional
        }

        $torras = $query->orderBy('torras.criado_em', 'desc')->paginate(20);

        return view('torras.index', compact('torras'));
    }

    public function iniciar()
    {
        return view('torras.IniciarTorra');
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Usuário não autenticado'], 401);
            }
            return redirect()->route('login');
        }

        try {
            $request->validate([
                'nome' => 'required|string|max:100',
                'variedade' => 'required|in:Arábico,Bourbon,Catuaí,Mundo Novo,Typica,Geisha,Caturra',
                'densidade' => 'required|numeric|min:0',
                'fermentacao' => 'required|in:Natural,Fermentado,CD',
                'finalidade' => 'required|in:Espresso,Filtro,Amostra',
                'observacoes' => 'nullable|string|max:500'
            ]);

            $torraId = DB::table('torras')->insertGetId([
                'usuario_id' => Auth::id(),
                'nome' => $request->nome,
                'variedade' => $request->variedade,
                'densidade' => $request->densidade,
                'fermentacao' => $request->fermentacao,
                'finalidade' => $request->finalidade,
                'observacoes' => $request->observacoes,
                'status' => 'nao_avaliada',
                'criado_em' => now(),
                'atualizado_em' => now()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Torra configurada com sucesso!',
                    'torra_id' => $torraId,
                    'redirect_url' => route('torras.monitoramento')
                ]);
            }

            return redirect()->route('torras.monitoramento')->with('success', 'Torra configurada com sucesso! Selecione-a para iniciar o monitoramento.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Dados inválidos', 'errors' => $e->errors()], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Erro interno do servidor'], 500);
            }
            return redirect()->back()->with('error', 'Erro ao criar torra');
        }
    }

    public function solicitarAvaliacao($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            // Verificar se a torra pertence ao usuário logado
            $torra = DB::table('torras')
                ->where('id', $id)
                ->where('usuario_id', Auth::id())
                ->first();

            if (!$torra) {
                return response()->json(['error' => 'Torra não encontrada'], 404);
            }

            // Verificar se a torra não está já aguardando avaliação ou avaliada
            if ($torra->status === 'aguardando_avaliacao') {
                return response()->json(['error' => 'Esta torra já está aguardando avaliação'], 400);
            }

            if ($torra->status === 'avaliada') {
                return response()->json(['error' => 'Esta torra já foi avaliada'], 400);
            }

            // Atualizar status para aguardando avaliação
            DB::table('torras')
                ->where('id', $id)
                ->update(['status' => 'aguardando_avaliacao']);

            return response()->json(['success' => true, 'message' => 'Solicitação de avaliação enviada com sucesso!']);

        } catch (\Exception $e) {
            Log::error('Erro ao solicitar avaliação:', [
                'user_id' => Auth::id(),
                'torra_id' => $id,
                'error' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Erro interno do servidor'], 500);
        }
    }

    public function mostrarSolicitarAvaliacao(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Buscar torras não avaliadas do usuário logado
        $torras = DB::table('torras')
            ->where('usuario_id', Auth::id())
            ->where('status', 'nao_avaliada')
            ->orderBy('criado_em', 'desc')
            ->get();

        // Buscar analistas disponíveis
        $analistas = DB::table('usuarios')
            ->where('tipo', 'Analista')
            ->orderBy('nome')
            ->get();

        $torraId = $request->get('torra_id');

        return view('torras.solicitar-avaliacao', compact('torras', 'analistas', 'torraId'));
    }

    public function processarSolicitarAvaliacao(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'torra_id' => 'required|exists:torras,id',
            'analista_id' => 'required|exists:usuarios,id',
            'notas' => 'nullable|string|max:1000'
        ]);

        try {
            // Verificar se a torra pertence ao usuário logado
            $torra = DB::table('torras')
                ->where('id', $request->torra_id)
                ->where('usuario_id', Auth::id())
                ->first();

            if (!$torra) {
                return redirect()->back()->with('error', 'Torra não encontrada');
            }

            // Verificar se a torra não está já aguardando avaliação ou avaliada
            if ($torra->status === 'aguardando_avaliacao') {
                return redirect()->back()->with('error', 'Esta torra já está aguardando avaliação');
            }

            if ($torra->status === 'avaliada') {
                return redirect()->back()->with('error', 'Esta torra já foi avaliada');
            }

            // Criar solicitação de prova
            DB::table('solicitacoes_prova')->insert([
                'solicitante_id' => Auth::id(),
                'analista_id' => $request->analista_id,
                'torra_id' => $request->torra_id,
                'notas' => $request->notas,
                'status' => 'Pendente',
                'criado_em' => now(),
                'atualizado_em' => now()
            ]);

            // Atualizar status da torra
            DB::table('torras')
                ->where('id', $request->torra_id)
                ->update(['status' => 'aguardando_avaliacao']);

            return redirect()->route('torras.index')->with('success', 'Solicitação de avaliação enviada com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao processar solicitação de avaliação:', [
                'user_id' => Auth::id(),
                'torra_id' => $request->torra_id,
                'error' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Erro interno do servidor');
        }
    }

    public function monitoramento()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Buscar torras não avaliadas do usuário logado
        $torras = DB::table('torras')
            ->where('usuario_id', Auth::id())
            ->where('status', 'nao_avaliada')
            ->orderBy('criado_em', 'desc')
            ->get();

        // Buscar torradores do usuário logado
        $torradores = DB::table('torradores')
            ->where('usuario_id', Auth::id())
            ->orderBy('nome')
            ->get();

        return view('torras.Monitoramento', compact('torras', 'torradores'));
    }

    public function destroy($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            // Verificar se a torra pertence ao usuário logado
            $torra = DB::table('torras')
                ->where('id', $id)
                ->where('usuario_id', Auth::id())
                ->first();

            if (!$torra) {
                return response()->json(['error' => 'Torra não encontrada'], 404);
            }

            // Verificar se a torra não está sendo avaliada (pendente ou em análise)
            $solicitacoesPendentes = DB::table('solicitacoes_prova')
                ->where('torra_id', $id)
                ->whereIn('status', ['Pendente', 'Em Análise'])
                ->get(['id', 'status', 'criado_em']);

            if ($solicitacoesPendentes->count() > 0) {
                $statusList = $solicitacoesPendentes->map(function($sol) {
                    return $sol->status . ' (desde ' . \Carbon\Carbon::parse($sol->criado_em)->format('d/m/Y') . ')';
                })->implode(', ');

                return response()->json([
                    'error' => 'Não é possível excluir esta torra porque ela possui ' . $solicitacoesPendentes->count() . ' solicitação(ões) de análise ativa(s): ' . $statusList . '. Aguarde a conclusão ou cancele as solicitações primeiro.'
                ], 422);
            }

            // Para torras com análises concluídas, excluir em cascata
            DB::transaction(function () use ($id) {
                // 1. Buscar todas as solicitações da torra
                $solicitacoes = DB::table('solicitacoes_prova')
                    ->where('torra_id', $id)
                    ->pluck('id');

                // 2. Excluir análises sensoriais relacionadas às solicitações
                if (!$solicitacoes->isEmpty()) {
                    DB::table('analise_sensorial')
                        ->whereIn('solicitacao_id', $solicitacoes)
                        ->delete();
                }

                // 3. Excluir solicitações de prova
                DB::table('solicitacoes_prova')
                    ->where('torra_id', $id)
                    ->delete();

                // 4. Excluir a torra
                DB::table('torras')->where('id', $id)->delete();
            });

            return response()->json(['success' => 'Torra excluída com sucesso']);

        } catch (\Exception $e) {
            \Log::error('Erro ao excluir torra ID ' . $id . ': ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'Erro interno do servidor: ' . $e->getMessage(),
                'debug' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    public function getSolicitacoes($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            // Verificar se a torra pertence ao usuário
            $torra = DB::table('torras')
                ->where('id', $id)
                ->where('usuario_id', Auth::id())
                ->first();

            if (!$torra) {
                return response()->json(['error' => 'Torra não encontrada'], 404);
            }

            // Buscar solicitações da torra
            $solicitacoes = DB::table('solicitacoes_prova as sp')
                ->join('usuarios as analista', 'sp.analista_id', '=', 'analista.id')
                ->where('sp.torra_id', $id)
                ->select(
                    'sp.id',
                    'sp.status',
                    'sp.criado_em',
                    'sp.notas',
                    'analista.nome as analista_nome',
                    'analista.sobrenome as analista_sobrenome'
                )
                ->orderBy('sp.criado_em', 'desc')
                ->get();

            return response()->json([
                'torra' => $torra,
                'solicitacoes' => $solicitacoes
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao buscar solicitações da torra ID ' . $id . ': ' . $e->getMessage());
            return response()->json(['error' => 'Erro interno do servidor'], 500);
        }
    }

    public function cancelarSolicitacao(Request $request, $torraId, $solicitacaoId)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            // Verificar se a torra pertence ao usuário e a solicitação existe
            $solicitacao = DB::table('solicitacoes_prova as sp')
                ->join('torras as t', 'sp.torra_id', '=', 't.id')
                ->where('sp.id', $solicitacaoId)
                ->where('sp.torra_id', $torraId)
                ->where('t.usuario_id', Auth::id())
                ->where('sp.status', 'Pendente') // Só pode cancelar se estiver pendente
                ->first();

            if (!$solicitacao) {
                return response()->json(['error' => 'Solicitação não encontrada ou não pode ser cancelada'], 404);
            }

            // Cancelar a solicitação
            DB::table('solicitacoes_prova')
                ->where('id', $solicitacaoId)
                ->update([
                    'status' => 'Cancelada',
                    'atualizado_em' => now()
                ]);

            return response()->json(['success' => 'Solicitação cancelada com sucesso']);

        } catch (\Exception $e) {
            \Log::error('Erro ao cancelar solicitação ID ' . $solicitacaoId . ': ' . $e->getMessage());
            return response()->json(['error' => 'Erro interno do servidor'], 500);
        }
    }
}
