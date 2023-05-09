<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Search extends Component
{
    public
        $needle,
        $searchRoute,
        $cancelRoute,
        $queryStringNoPage,
        $queryStringNoPageNoNeedle,
        $buttonLabel;

    public function __construct($needle, $searchRoute, $cancelRoute)
    {
        $this->needle = $needle;
        $this->searchRoute = $searchRoute;
        $this->cancelRoute = $cancelRoute;
        $this->buttonLabel = \Lang::get('translation.search');

        $this->queryStringNoPageNoNeedle = http_build_query(\Request::except(['page', 'needle', '_token']));

        $this->queryStringNoPage = $this->queryStringNoPageNoNeedle;
        if (!empty($this->needle)) {
            $this->queryStringNoPage = (empty($this->queryStringNoPage) ? '?' : '&'   ) . 'needle='.$this->needle;
        }
    }

    public function render()
    {
        return view('components.search');
    }
}
