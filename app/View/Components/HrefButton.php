<?php

namespace App\View\Components;

use Illuminate\View\Component;

class HrefButton extends Component
{
    public $url;

    public function __construct($url = 'javascript:')
    {
        $this->url = $url;
    }

    public function render()
    {
        return view('components.href-button');
    }
}
