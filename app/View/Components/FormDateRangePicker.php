<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormDateRangePicker extends Component
{
    /**
     **** required:
     *      'from',    name (id) of the related hidden input fields
     *      'to',      name (id) of the related hidden input fields
     *
     **** optional params:
     *      'label',
     *      'value',
     *      'min_date',
     *      'max_date',
     *      'hint',
     *      'required',
     *      'readonly', default: true
     *      'placeholder',
     *      'iconClass',
     *      'language',    spanish: es
     *      'attributes' => [], // other field attributes
     */

    public
        $from,
        $to,
        $params,
        $siteUrl;

    public function __construct($from, $to, $params = null)
    {
        $this->from = $from;
        $this->to = $to;
        $this->params = $params;
        $this->siteUrl = env('APP_URL');
    }

    public function render()
    {
        return view('components.form-date-range-picker');
    }
}
