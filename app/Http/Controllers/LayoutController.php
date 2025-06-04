<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class LayoutController extends Controller
{
    public function dashboard()
    {
        $navbar = Cache::remember('navbar', 60, function () {
            return view('templates.navbar')->render();
        });

        $sidebar = Cache::remember('sidebar', 60, function () {
            return view('templates.sidebar')->render();
        });

        return view('dashboard', compact('navbar', 'sidebar'));
    }
}
