<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Torrador;

class TorradorController extends Controller
{

    public function create(){
        return view('Torradores.AdicionarTorrador');
    }

    public function store(Request $request){
        $request->validate([
            'nome' => 'required|string|max:255',
            'codigo_conexao' => 'required|string|max:255',
        ]);

        $torrador = new Torrador();
        $torrador->nome = $request->nome;
        $torrador->codigo_conexao = $request->codigo_conexao;
        $torrador->usuario_id = auth()->id();
        $torrador->save();

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('torradores.index')->with('success', 'Torrador cadastrado com sucesso!');
    }

    public function index(Request $request){
        $query = auth()->user()->torradores();

        if ($request->filled('search')) {
            $query->where('nome', 'like', '%' . $request->search . '%');
        }

        $torradores = $query->orderBy('criado_em', 'desc')->get();

        return view('Torradores.index', compact('torradores'));
    }

    public function destroy($id){
        $torrador = \App\Models\Torrador::findOrFail($id);

        if ($torrador->usuario_id !== auth()->id()) {
            abort(403, 'Ação não autorizada.');
        }

        $torrador->delete();

        return redirect()->route('torradores.index')->with('success', 'Torrador excluído com sucesso!');
    }

    public function edit($id){
        $torrador = Torrador::findOrFail($id);
        if ($torrador->usuario_id !== auth()->id()) {
            abort(403, 'Ação não autorizada.');
        }
        return response()->json($torrador);
    }

    public function update(Request $request, $id){
        $request->validate([
            'nome' => 'required|string|max:255',
            'codigo_conexao' => 'required|string|max:255',
        ]);

        $torrador = Torrador::findOrFail($id);
        if ($torrador->usuario_id !== auth()->id()) {
            abort(403, 'Ação não autorizada.');
        }

        $torrador->nome = $request->nome;
        $torrador->codigo_conexao = $request->codigo_conexao;
        $torrador->save();

        return response()->json(['success' => true]);
    }
}
