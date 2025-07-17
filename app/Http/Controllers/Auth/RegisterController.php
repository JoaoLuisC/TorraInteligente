<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
       $validator = Validator::make($request->all(), [
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'role' => 'required|in:analista,administrador,produtor',
            'password' => 'required|string|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Combine firstName and lastName into nome field
        $fullName = trim($request->firstName . ' ' . $request->lastName);

        $user = User::create([
            'nome' => $fullName,
            'email' => $request->email,
            'senha' => Hash::make($request->password),
            'tipo' => ucfirst($request->role),
        ]);

        Auth::login($user);

        return redirect()->route('torradores.index')->with('success', 'Cadastro realizado com sucesso!');
    }
}
