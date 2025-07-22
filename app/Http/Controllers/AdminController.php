<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AdminController extends Controller
{
    public function usuarios()
    {
        if (!Auth::check() || Auth::user()->tipo !== 'Administrador') {
            return redirect()->route('login');
        }

        $usuarios = User::whereIn('tipo', ['Analista', 'Produtor'])
            ->orderBy('tipo')
            ->orderBy('nome')
            ->paginate(10);

        return view('admin.usuarios', compact('usuarios'));
    }

    public function dashboard()
    {
        if (!Auth::check() || Auth::user()->tipo !== 'Administrador') {
            return redirect()->route('login');
        }

        // Estatísticas principais
        $estatisticas = [
            'total_usuarios' => User::count(),
            'total_torras' => DB::table('torras')->count(),
            'total_analises' => DB::table('analise_sensorial')->count(),
            'analises_pendentes' => DB::table('solicitacoes_prova')
                ->leftJoin('analise_sensorial', 'solicitacoes_prova.id', '=', 'analise_sensorial.solicitacao_id')
                ->whereNull('analise_sensorial.id')
                ->count(),
            'produtores' => User::where('tipo', 'Produtor')->count(),
            'analistas' => User::where('tipo', 'Analista')->count(),
            'administradores' => User::where('tipo', 'Administrador')->count(),
        ];

        // Usuários recentes (últimos 5)
        $usuariosRecentes = User::orderBy('criado_em', 'desc')->limit(5)->get();

        // Análises recentes (últimas 5)
        $analisesRecentes = DB::table('analise_sensorial as a')
            ->join('solicitacoes_prova as sp', 'a.solicitacao_id', '=', 'sp.id')
            ->join('torras as t', 'sp.torra_id', '=', 't.id')
            ->join('usuarios as u', 't.usuario_id', '=', 'u.id')
            ->select(
                'a.*',
                't.nome as torra_nome',
                'u.nome as produtor_nome',
                'a.created_at as data_analise'
            )
            ->orderBy('a.created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($analise) {
                $analise->data_analise = \Carbon\Carbon::parse($analise->data_analise);
                return $analise;
            });

        // Dados para gráfico de análises por mês (últimos 6 meses)
        $mesesAnalises = [];
        $dadosAnalises = [];

        for ($i = 5; $i >= 0; $i--) {
            $data = now()->subMonths($i);
            $mes = $data->format('M/Y');
            $count = DB::table('analise_sensorial')
                ->whereYear('created_at', $data->year)
                ->whereMonth('created_at', $data->month)
                ->count();

            $mesesAnalises[] = $mes;
            $dadosAnalises[] = $count;
        }

        $graficoAnalises = [
            'labels' => $mesesAnalises,
            'data' => $dadosAnalises
        ];

        return view('admin.dashboard', compact(
            'estatisticas',
            'usuariosRecentes',
            'analisesRecentes',
            'graficoAnalises'
        ));
    }

    public function excluirUsuario($id)
    {
        if (!Auth::check() || Auth::user()->tipo !== 'Administrador') {
            return redirect()->route('login');
        }

        $usuario = User::find($id);

        if (!$usuario) {
            return redirect()->route('admin.usuarios')
                ->with('error', 'Usuário não encontrado.');
        }

        if ($usuario->tipo === 'Administrador') {
            return redirect()->route('admin.usuarios')
                ->with('error', 'Não é possível excluir um administrador.');
        }

        try {
            $usuario->delete();
            return redirect()->route('admin.usuarios')
                ->with('success', 'Usuário excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('admin.usuarios')
                ->with('error', 'Erro ao excluir usuário: ' . $e->getMessage());
        }
    }
}
