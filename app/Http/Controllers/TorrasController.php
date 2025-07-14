<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Torra;


class TorrasController extends Controller
{
    public function iniciar()
    {
        return view('torras.IniciarTorra');
    }
}
