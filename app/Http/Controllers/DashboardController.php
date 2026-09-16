<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Récupère les 4 derniers utilisateurs
        $connexions = User::orderBy('updated_at', 'desc')->take(4)->get();
        return view('pages.dashboard', compact('connexions'));
    }
}
