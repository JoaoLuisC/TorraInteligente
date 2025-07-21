<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Torra;

class TorrasController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Buscar todas as torras do usuário logado com informações do avaliador
        $query = DB::table('torras')
            ->leftJoin('usuarios as avaliador', 'torras.avaliador_id', '=', 'avaliador.id')
            ->select(
                'torras.*',
                'avaliador.nome as avaliador_nome',
                'avaliador.sobrenome as avaliador_sobrenome'
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

        $torras = $query->orderBy('torras.criado_em', 'desc')->get();

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
                'avaliada' => false,
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

    public function monitoramento()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Buscar torras não avaliadas do usuário logado
        $torras = DB::table('torras')
            ->where('usuario_id', Auth::id())
            ->where('avaliada', false)
            ->orderBy('criado_em', 'desc')
            ->get();

        return view('torras.Monitoramento', compact('torras'));
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

            // Verificar se a torra não está sendo avaliada
            $temSolicitacao = DB::table('solicitacoes_prova')
                ->where('torra_id', $id)
                ->whereIn('status', ['Pendente', 'Em Análise'])
                ->exists();

            if ($temSolicitacao) {
                return response()->json(['error' => 'Não é possível excluir uma torra que possui solicitações de prova pendentes'], 422);
            }

            // Excluir a torra
            DB::table('torras')->where('id', $id)->delete();

            return response()->json(['success' => 'Torra excluída com sucesso']);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro interno do servidor'], 500);
        }
    }
}
