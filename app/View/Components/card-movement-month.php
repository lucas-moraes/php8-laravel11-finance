<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class card-movement-month extends Component
{
    public function __construct(){}

    public function render(): View|Closure|string
    {
        return view('components.card-movement-month');
    }
}
