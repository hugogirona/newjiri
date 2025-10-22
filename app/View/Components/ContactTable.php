<?php

namespace App\View\Components;

use Closure;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ContactTable extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $title,
        public Collection|LengthAwarePaginator $contacts,
        public string $emptyMessage,
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.contact-table');
    }
}
