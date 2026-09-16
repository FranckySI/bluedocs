<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Récupère les 10 dernières connexions
        $connexions = User::whereNotNull('last_login_at')
                  ->orderByDesc('last_login_at')
                  ->take(10)
                  ->get();
        return view('pages.dashboard', compact('connexions'));
    }
}
