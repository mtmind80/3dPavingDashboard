<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormSelect extends Component
{
    /** jSelect params:
     * 'label',
     * 'id',
     * 'required',
     * 'attributes' => [], // other field attributes
     * 'iconClass',
     */

    public
        $name,
        $items,
        $selected,
        $params;

    public function __construct($name, $items, $selected = null, $params = null)
    {
        $this->name = $name;
        $this->items = $items;
        $this->selected = $selected;
        $this->params = $params;
    }

    public function render()
    {
        return view('components.form-select');
    }
}
