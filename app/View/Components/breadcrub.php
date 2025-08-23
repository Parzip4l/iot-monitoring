<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class breadcrub extends Component
{
    public $title;
    public $pagetitle;
    public $subtitle;
    /**
     * Create a new component instance.
     */
    public function __construct($title, $pagetitle, $subtitle)
    {
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->pagetitle = $pagetitle;
    }
    
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.breadcrub');
    }
}
