<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Torrador;

class TorradorController extends Controller
{

    public function create()
    {
        return view('Torradores.AdicionarTorrador');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'codigo_conexao' => 'required|string|max:255',
        ]);

        $torrador = new Torrador();
        $torrador->nome = $request->nome;
        $torrador->codigo_conexao = $request->codigo_conexao;
        $torrador->user_id = auth()->id();
        $torrador->save();

        return redirect()->route('torradores.index')->with('success', 'Torrador cadastrado com sucesso!');
    }

    public function index()
    {
        // Busca os torradores do usuário autenticado
        $torradores = auth()->user()->torradores ?? collect();

        return view('Torradores.index', compact('torradores'));
    }
}
