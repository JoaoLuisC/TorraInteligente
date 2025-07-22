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

        // Estatísticas do produtor
        $estatisticas = [
            'total_torras' => DB::table('torras')
                ->where('usuario_id', $produtorId)
                ->count(),

            'torras_avaliadas' => DB::table('torras')
                ->where('usuario_id', $produtorId)
                ->where('status', 'avaliada')
                ->count(),

            'torras_nao_avaliadas' => DB::table('torras')
                ->where('usuario_id', $produtorId)
                ->where('status', 'nao_avaliada')
                ->count(),

            'torras_aguardando' => DB::table('torras')
                ->where('usuario_id', $produtorId)
                ->where('status', 'aguardando_avaliacao')
                ->count(),
        ];

        // Últimas torras (5 mais recentes) com suas notas se avaliadas
        $ultimasTorras = DB::table('torras as t')
            ->leftJoin('solicitacoes_prova as sp', function($join) {
                $join->on('t.id', '=', 'sp.torra_id')
                     ->where('sp.status', '=', 'Concluída');
            })
            ->leftJoin('analise_sensorial as a', 'sp.id', '=', 'a.solicitacao_id')
            ->where('t.usuario_id', $produtorId)
            ->select(
                't.id',
                't.nome',
                't.variedade',
                't.fermentacao',
                't.finalidade',
                't.status',
                't.criado_em',
                'a.nota_final'
            )
            ->orderBy('t.criado_em', 'desc')
            ->limit(5)
            ->get();

        // 5 melhores torras avaliadas (com maior nota final)
        $melhoresTorras = DB::table('torras as t')
            ->join('solicitacoes_prova as sp', 't.id', '=', 'sp.torra_id')
            ->join('analise_sensorial as a', 'sp.id', '=', 'a.solicitacao_id')
            ->where('t.usuario_id', $produtorId)
            ->where('sp.status', 'Concluída')
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
            ->join('usuarios as analista', 'sp.analista_id', '=', 'analista.id')
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

        // Distribuição de variedades
        $distribuicaoVariedades = DB::table('torras')
            ->where('usuario_id', $produtorId)
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
