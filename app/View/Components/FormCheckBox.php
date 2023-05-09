<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormCheckBox extends Component
{
    public $name, $id, $value, $checked;

    public function __construct($name, $id = null, $value = null, $checked = null)
    {
        $this->name = $name;
        $this->id = $id ?? $name;
        $this->value = $value;
        $this->checked = (boolean)$checked;

     //   dd($this->name, $this->id, $this->value, $this->checked);
    }

    public function render()
    {
        return view('components.form-check-box');
    }
}
