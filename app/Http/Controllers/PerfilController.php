<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class PerfilController extends Controller
{
    public function index()
    {
        // Verifica se o usuário está autenticado
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Você precisa estar logado para acessar o perfil.');
        }

        return view('usuarios.perfil');
    }

    public function edit()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Você precisa estar logado para editar o perfil.');
        }

        return view('usuarios.perfil-editar');
    }

    public function update(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Usuário não autenticado'], 401);
                }
                return redirect()->route('login');
            }

            // Validação
            $request->validate([
                'nome' => 'required|string|max:100',
                'sobrenome' => 'required|string|max:100',
                'email' => 'required|email|max:150|unique:usuarios,email,' . $user->id,
                'tipo' => 'required|in:Analista,Produtor,Administrador',
                'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Atualizar dados básicos
            $user->nome = $request->nome;
            $user->sobrenome = $request->sobrenome;
            $user->email = $request->email;
            $user->tipo = $request->tipo;

            // Processar imagem se enviada
            if ($request->hasFile('imagem')) {
                // Remover imagem antiga se existir
                if ($user->imagem && file_exists(public_path('uploads/perfil/' . $user->imagem))) {
                    unlink(public_path('uploads/perfil/' . $user->imagem));
                }

                // Criar diretório se não existir
                $uploadPath = public_path('uploads/perfil');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                $imagem = $request->file('imagem');
                $nomeImagem = time() . '_' . uniqid() . '.' . $imagem->getClientOriginalExtension();
                $imagem->move($uploadPath, $nomeImagem);
                $user->imagem = $nomeImagem;
            }

            // Salvar
            $user->save();

            // Resposta baseada no tipo de requisição
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Perfil atualizado com sucesso!',
                    'redirect' => route('perfil')
                ]);
            }

            return redirect()->route('perfil')->with('success', 'Perfil atualizado com sucesso!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Dados inválidos', 'errors' => $e->errors()], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Erro interno do servidor'], 500);
            }
            return redirect()->back()->with('error', 'Erro ao salvar perfil');
        }
    }    public function showAlterarSenha()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Você precisa estar logado para alterar a senha.');
        }

        return view('usuarios.alterar-senha');
    }

    public function alterarSenha(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Você precisa estar logado para alterar a senha.');
        }

        $request->validate([
            'senha_atual' => 'required',
            'nova_senha' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'senha_atual.required' => 'A senha atual é obrigatória.',
            'nova_senha.required' => 'A nova senha é obrigatória.',
            'nova_senha.min' => 'A nova senha deve ter pelo menos 8 caracteres.',
            'nova_senha.confirmed' => 'A confirmação da nova senha não confere.',
        ]);

        $user = Auth::user();

        // Verifica se a senha atual está correta
        if (!Hash::check($request->senha_atual, $user->senha)) {
            return back()->withErrors(['senha_atual' => 'A senha atual está incorreta.']);
        }

        // Verifica se a nova senha é diferente da atual
        if (Hash::check($request->nova_senha, $user->senha)) {
            return back()->withErrors(['nova_senha' => 'A nova senha deve ser diferente da senha atual.']);
        }

        // Atualiza a senha
        $user->senha = Hash::make($request->nova_senha);
        $user->save();

        return redirect()->route('perfil')->with('success', 'Senha alterada com sucesso!');
    }

    public function removerImagem(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Usuário não autenticado'], 401);
                }
                return redirect()->route('login');
            }

            // Remove a imagem se existir
            if ($user->imagem && file_exists(public_path('uploads/perfil/' . $user->imagem))) {
                unlink(public_path('uploads/perfil/' . $user->imagem));
            }

            $user->imagem = null;
            $user->save();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Imagem removida com sucesso!']);
            }

            return redirect()->route('perfil.edit')->with('success', 'Imagem removida com sucesso!');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Erro ao remover imagem'], 500);
            }
            return redirect()->back()->with('error', 'Erro ao remover imagem');
        }
    }
}
