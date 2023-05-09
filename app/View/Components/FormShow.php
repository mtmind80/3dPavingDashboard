<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormShow extends Component
{
    /** jShow params:
     * 'label',
     * 'hint',
     * 'required',
     * 'iconClass',
     * 'id',
     */

    public
        $params;

    public function __construct($params = null)
    {
        $this->params = $params;
    }
    public function render()
    {
        return view('components.form-show');
    }
}
