<?php

namespace App\View\Components;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProjectTable extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $title,
        public Collection $projects,
        public string $emptyMessage,
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.project-table');
    }
}
