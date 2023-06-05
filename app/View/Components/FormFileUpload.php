<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormFileUpload extends Component
{
    /** jFileUpload params:
    'name'
    'id'
    'params:
    'label'           default: name
    'paddingLeft'     default: 100px
    'hint'            default: ''
    'buttonLabel'     default: $lang, === 'es' ? 'Seleccionar' : 'Browse'
    'placeholder'     default: $lang, === 'es' ? 'Subir fichero' : 'Upload file',
    'lang'            default: 'en',
    'iconClass'       default:  'fa fa-upload'
    'required'        default:  false
     */

    public
        $name,
        $id,
        $params,
        $lang;

    public function __construct($name, $id, $params = null)
    {
        $this->name = $name;
        $this->id = $id;
        $this->params = $params;
        $this->lang = $params['lang'] ?? 'en';
    }

    public function render()
    {
        return view('components.form-file-upload');
    }
}
