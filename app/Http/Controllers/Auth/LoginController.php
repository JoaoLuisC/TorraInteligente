<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Exibe o formulário de login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password, // Use 'senha' pois seu campo no banco é esse
        ];

        if (Auth::attempt($credentials)) {
            return redirect()->route('dashboard')->with('success', 'Login realizado com sucesso!');
        }

        return back()->withErrors(['email' => 'Credenciais inválidas'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
