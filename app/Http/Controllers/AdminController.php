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

        try {
            // Verificar se as tabelas existem
            $torrasExiste = DB::getSchemaBuilder()->hasTable('torras');
            $analisesExiste = DB::getSchemaBuilder()->hasTable('analise_sensorial');
            $solicitacoesExiste = DB::getSchemaBuilder()->hasTable('solicitacoes_prova');

            // Estatísticas principais
            $estatisticas = [
                'total_usuarios' => User::count(),
                'total_torras' => $torrasExiste ? DB::table('torras')->count() : 0,
                'total_analises' => $analisesExiste ? DB::table('analise_sensorial')->count() : 0,
                'analises_pendentes' => ($solicitacoesExiste && $analisesExiste) ?
                    DB::table('solicitacoes_prova')
                        ->leftJoin('analise_sensorial', 'solicitacoes_prova.id', '=', 'analise_sensorial.solicitacao_id')
                        ->whereNull('analise_sensorial.id')
                        ->count() : 0,
                'produtores' => User::where('tipo', 'Produtor')->count(),
                'analistas' => User::where('tipo', 'Analista')->count(),
                'administradores' => User::where('tipo', 'Administrador')->count(),
            ];

            // Usuários recentes (últimos 5)
            $usuariosRecentes = User::orderBy('created_at', 'desc')->limit(5)->get();

            // Análises recentes (últimas 5) - só se as tabelas existirem
            $analisesRecentes = collect();
            if ($analisesExiste && $solicitacoesExiste && $torrasExiste) {
                try {
                    $analisesRecentes = DB::table('analise_sensorial as a')
                        ->join('solicitacoes_prova as sp', 'a.solicitacao_id', '=', 'sp.id')
                        ->join('torras as t', 'sp.torra_id', '=', 't.id')
                        ->join('usuarios as produtor', 't.usuario_id', '=', 'produtor.id')
                        ->join('usuarios as analista', 'sp.analista_id', '=', 'analista.id')
                        ->select(
                            'a.*',
                            't.nome as torra_nome',
                            'produtor.nome as produtor_nome',
                            'produtor.sobrenome as produtor_sobrenome',
                            'analista.nome as analista_nome',
                            'analista.sobrenome as analista_sobrenome'
                        )
                        ->orderBy('a.created_at', 'desc')
                        ->limit(5)
                        ->get();
                } catch (\Exception $e) {
                    \Log::warning('Erro nas análises recentes: ' . $e->getMessage());
                }
            }

            // Distribuição de tipos de usuários
            $distribuicaoUsuarios = User::select('tipo', DB::raw('count(*) as total'))
                ->groupBy('tipo')
                ->get();

        } catch (\Exception $e) {
            \Log::error('Erro no dashboard do admin: ' . $e->getMessage());

            // Em caso de erro, dados básicos
            $estatisticas = [
                'total_usuarios' => User::count(),
                'total_torras' => 0,
                'total_analises' => 0,
                'analises_pendentes' => 0,
                'produtores' => User::where('tipo', 'Produtor')->count(),
                'analistas' => User::where('tipo', 'Analista')->count(),
                'administradores' => User::where('tipo', 'Administrador')->count(),
            ];
            $usuariosRecentes = User::orderBy('created_at', 'desc')->limit(5)->get();
            $analisesRecentes = collect();
            $distribuicaoUsuarios = collect();
        }

        return view('dashboard.admin', compact(
            'estatisticas',
            'usuariosRecentes',
            'analisesRecentes',
            'distribuicaoUsuarios'
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
            // Verificar se as tabelas relacionadas existem antes de tentar deletar relacionamentos
            if (DB::getSchemaBuilder()->hasTable('torras')) {
                DB::table('torras')->where('usuario_id', $id)->delete();
            }

            if (DB::getSchemaBuilder()->hasTable('solicitacoes_prova')) {
                DB::table('solicitacoes_prova')->where('analista_id', $id)->delete();
            }

            $usuario->delete();
            return redirect()->route('admin.usuarios')
                ->with('success', 'Usuário excluído com sucesso!');
        } catch (\Exception $e) {
            \Log::error('Erro ao excluir usuário: ' . $e->getMessage());
            return redirect()->route('admin.usuarios')
                ->with('error', 'Erro ao excluir usuário: ' . $e->getMessage());
        }
    }
}
