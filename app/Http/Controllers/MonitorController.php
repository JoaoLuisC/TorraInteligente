<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MonitorController extends Controller
{
    /**
     * Construtor - requer autenticação
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Página de monitoramento em tempo real
     */
    public function realtime()
    {
        // Buscar torradores do usuário logado
        $torradores = DB::table('torradores')
            ->where('usuario_id', Auth::id())
            ->select('id', 'nome', 'codigo_conexao')
            ->get();

        return view('monitor.realtime', compact('torradores'));
    }

    /**
     * Dashboard com estatísticas dos sensores
     */
    public function dashboard()
    {
        $userId = Auth::id();

        // Estatísticas gerais
        $stats = [
            'total_torradores' => DB::table('torradores')->where('usuario_id', $userId)->count(),
            'dados_hoje' => DB::table('dados_sensores')
                ->join('torradores', 'dados_sensores.torrador_id', '=', 'torradores.id')
                ->where('torradores.usuario_id', $userId)
                ->whereDate('dados_sensores.created_at', today())
                ->count(),
            'ultima_temperatura' => DB::table('dados_sensores')
                ->join('torradores', 'dados_sensores.torrador_id', '=', 'torradores.id')
                ->where('torradores.usuario_id', $userId)
                ->orderBy('dados_sensores.created_at', 'desc')
                ->value('temperatura'),
            'tempo_total_hoje' => DB::table('dados_sensores')
                ->join('torradores', 'dados_sensores.torrador_id', '=', 'torradores.id')
                ->where('torradores.usuario_id', $userId)
                ->whereDate('dados_sensores.created_at', today())
                ->max('tempo')
        ];

        // Dados para gráfico das últimas 24h
        $dadosGrafico = DB::table('dados_sensores')
            ->join('torradores', 'dados_sensores.torrador_id', '=', 'torradores.id')
            ->where('torradores.usuario_id', $userId)
            ->where('dados_sensores.created_at', '>=', now()->subDay())
            ->select(
                DB::raw('HOUR(dados_sensores.created_at) as hora'),
                DB::raw('AVG(temperatura) as temp_media'),
                DB::raw('MAX(temperatura) as temp_max'),
                DB::raw('MIN(temperatura) as temp_min')
            )
            ->groupBy(DB::raw('HOUR(dados_sensores.created_at)'))
            ->orderBy('hora')
            ->get();

        return view('monitor.dashboard', compact('stats', 'dadosGrafico'));
    }

    /**
     * Histórico de torras
     */
    public function historico(Request $request)
    {
        $userId = Auth::id();
        $query = DB::table('dados_sensores')
            ->join('torradores', 'dados_sensores.torrador_id', '=', 'torradores.id')
            ->where('torradores.usuario_id', $userId);

        // Filtros
        if ($request->filled('torrador_id')) {
            $query->where('torradores.id', $request->torrador_id);
        }

        if ($request->filled('data_inicio')) {
            $query->whereDate('dados_sensores.created_at', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('dados_sensores.created_at', '<=', $request->data_fim);
        }

        // Agrupar por sessão de torra (por dia e torrador)
        $historico = $query
            ->select(
                'torradores.nome as torrador_nome',
                DB::raw('DATE(dados_sensores.created_at) as data_torra'),
                DB::raw('MIN(dados_sensores.created_at) as inicio'),
                DB::raw('MAX(dados_sensores.created_at) as fim'),
                DB::raw('MAX(temperatura) as temp_maxima'),
                DB::raw('AVG(temperatura) as temp_media'),
                DB::raw('MAX(tempo) as duracao_total'),
                DB::raw('COUNT(*) as total_leituras')
            )
            ->groupBy('torradores.id', DB::raw('DATE(dados_sensores.created_at)'))
            ->orderBy('dados_sensores.created_at', 'desc')
            ->paginate(20);

        // Lista de torradores para o filtro
        $torradores = DB::table('torradores')
            ->where('usuario_id', $userId)
            ->select('id', 'nome')
            ->get();

        return view('monitor.historico', compact('historico', 'torradores'));
    }
}
