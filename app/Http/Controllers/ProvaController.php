<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Torra;

class ProvaController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Buscar analistas para seleção
        $analistas = User::where('tipo', 'Analista')->get();

        // Buscar apenas torras não avaliadas do usuário logado
        $torrasNaoAvaliadas = Torra::where('avaliada', false)
                                   ->where('usuario_id', Auth::id())
                                   ->get();

        return view('prova.solicitar', compact('analistas', 'torrasNaoAvaliadas'));
    }

    public function listar()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Buscar apenas solicitações que foram para análise (não pendentes)
        $solicitacoes = DB::table('solicitacoes_prova')
            ->join('usuarios as solicitante', 'solicitacoes_prova.solicitante_id', '=', 'solicitante.id')
            ->join('usuarios as analista', 'solicitacoes_prova.analista_id', '=', 'analista.id')
            ->join('torras', 'solicitacoes_prova.torra_id', '=', 'torras.id')
            ->join('usuarios as produtor', 'torras.usuario_id', '=', 'produtor.id')
            ->select(
                'solicitacoes_prova.*',
                'solicitante.nome as solicitante_nome',
                'solicitante.sobrenome as solicitante_sobrenome',
                'analista.nome as analista_nome',
                'analista.sobrenome as analista_sobrenome',
                'torras.nome as torra_nome',
                'torras.variedade as torra_variedade',
                'produtor.nome as produtor_nome',
                'produtor.sobrenome as produtor_sobrenome'
            )
            ->whereIn('solicitacoes_prova.status', ['Em Análise', 'Concluída'])
            ->orderBy('solicitacoes_prova.criado_em', 'desc')
            ->get();

        return view('prova.listar', compact('solicitacoes'));
    }

    public function solicitar(Request $request)
    {
        if (!Auth::check()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Usuário não autenticado'], 401);
            }
            return redirect()->route('login');
        }

        try {
            $request->validate([
                'analista_id' => 'required|exists:usuarios,id',
                'torra_id' => 'required|exists:torras,id',
                'notas' => 'nullable|string|max:500'
            ]);

            // Verificar se a torra ainda não foi avaliada
            $torra = Torra::find($request->torra_id);
            if ($torra->avaliada) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Esta torra já foi avaliada'], 422);
                }
                return redirect()->back()->with('error', 'Esta torra já foi avaliada');
            }

            // Verificar se o usuário selecionado é realmente um analista
            $analista = User::find($request->analista_id);
            if ($analista->tipo !== 'Analista') {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Usuário selecionado não é um analista'], 422);
                }
                return redirect()->back()->with('error', 'Usuário selecionado não é um analista');
            }

            // Salvar a solicitação na tabela
            DB::table('solicitacoes_prova')->insert([
                'solicitante_id' => Auth::id(),
                'analista_id' => $request->analista_id,
                'torra_id' => $request->torra_id,
                'notas' => $request->notas,
                'status' => 'Pendente',
                'criado_em' => now(),
                'atualizado_em' => now()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Solicitação de prova enviada com sucesso!'
                ]);
            }

            return redirect()->route('prova.solicitar')->with('success', 'Solicitação de prova enviada com sucesso!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Dados inválidos', 'errors' => $e->errors()], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Erro interno do servidor'], 500);
            }
            return redirect()->back()->with('error', 'Erro ao enviar solicitação');
        }
    }
}
