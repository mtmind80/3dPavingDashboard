<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormText extends Component
{
    /** jText, jPassword & jTextarea params:
     * 'label',
     * 'hint',
     * 'required',
     * 'iconClass',
     * 'title',
     */

    public
        $name,
        $id,
        $value,
        $params;

    public function __construct($name, $id = null, $value = null, $params = null)
    {
        $this->name = $name;
        $this->id = $id ?? $name;
        $this->value = $value;
        $this->params = $params;
    }

    public function render()
    {
        return view('components.form-text');
    }
}
