<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormTextarea extends Component
{
    /** jText, jPassword & jTextarea params:
     * 'label',
     * 'hint',
     * 'required',
     * 'iconClass',
     * 'title',
     * 'maxChars',
     */

    public
        $name,
        $id,
        $params;

    public function __construct($name, $id = null, $params = null)
    {
        $this->name = $name;
        $this->id = $id ?? $name;
        $this->params = $params;
    }

    public function render()
    {
        return view('components.form-textarea');
    }
}
