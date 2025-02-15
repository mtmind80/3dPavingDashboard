<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DeleteForm extends Component
{
    public $url;
    public $params;

    public function __construct($url, $params = null)
    {
        $this->url = $url;
        $this->params = $params;
    }

    public function render()
    {
        return view('components.delete-form');
    }
}
