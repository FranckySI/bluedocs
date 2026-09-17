<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\User;

class ListeConnexions extends Component
{
    public $connexions;
    public function __construct()
    {
        $this->connexions = User::whereNotNull('last_login_at')
                                  ->orderByDesc('last_login_at')
                                  ->take(3)
                                  ->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.liste-connexions');
    }
}
