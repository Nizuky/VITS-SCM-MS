<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Route;

class ReturnToWelcome extends Component
{
    /**
     * The url to return to (home or fallback).
     *
     * @var string
     */
    public $url;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->url = Route::has('home') ? route('home') : url('/');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.return-to-welcome');
    }
}
