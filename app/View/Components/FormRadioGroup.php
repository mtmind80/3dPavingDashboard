<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormRadioGroup extends Component
{
    /**
     **** required:
     *      'name',      name of the radios
     *      'radios',    array of radios: ['label' => label, 'value' => value, 'checked' => false]
     *
     **** optional params:
     *      'id'         id of the group container (with class .radio-container). Default: none
     *      'label',
     *      'hint',
     *      'direction'   v: vertical (default), h: horizontal
     *      'attributes' => [], // other field attributes
     */

    public
        $name,
        $radios,
        $isHorz,
        $params,
        $siteUrl;

    public function __construct($name, $radios, $params = null)
    {
        $this->name = $name;
        $this->radios = $radios;
        $this->params = $params;
        $this->isHorz = !empty($params['direction']) && $params['direction'] === 'h';
        $this->siteUrl = env('APP_URL');
    }

    public function render()
    {
        return view('components.form-radio-group');
    }
}
