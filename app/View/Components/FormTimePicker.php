<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormTimePicker extends Component
{
    /** jDatePicker, jTimerPicker & jDateTimePicker:
     *
     *** required:
     *      'name',
     *
     *** optional params:
     *      'label',
     *      'id',
     *      'value',
     *      'format' default: m/d/Y for jCalendar,  h:i A for jTime  & 'm/d/Y h:i A' for jDateTimePicker
     *      'hint',
     *      'required',
     *      'iconClass',
     *      'language',    spanish: es
     *      'attributes' => [], // other field attributes
     *
     */

    public
        $name,
        $params,
        $siteUrl;

    public function __construct($name, $params = null)
    {
        $this->name = $name;
        $this->params = $params;
        $this->siteUrl = env('APP_URL');
    }

    public function render()
    {
        return view('components.form-time-picker');
    }
}
