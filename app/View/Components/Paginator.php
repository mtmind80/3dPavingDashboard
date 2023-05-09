<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Paginator extends Component
{
    public
        $collection,
        $routeName,
        $needle,
        $params,
        $links,
        $pageLimits,
        $query,
        $queryNoPageNeedleArray,
        $queryNoPageArray,
        $uClass,
        $liClass,
        $liFirstClass,
        $liEdgeClass,
        $disabledClass,
        $firstCaption,
        $liInnerClass,
        $selectedClass,
        $liLastClass,
        $lastCaption,
        $from,
        $to,
        $lang;

    public function __construct($collection, $routeName, $needle = null, $params = null)
    {
        $this->collection = $collection;
        $this->routeName = $routeName;
        $this->needle = $needle;
        $this->params = $params;

        $this->links = $this->params['links'] ?? 7;
        $this->query = '&' . ($this->params['query'] ?? http_build_query(\Request::except(['page', '_token'])));
        if (!empty($needle) && strpos($this->query, 'needle=') === false) {
            $this->query['needle'] = $this->needle;
        }
        $this->pageLimits = $this->params['pageLimits'] ?? [10, 20];

        $this->queryNoPageArray =  [];

        if (!empty($this->params['route_params'])) {
            $this->queryNoPageArray = $this->params['route_params'];
        }

        $this->queryNoPageArray = array_merge($this->queryNoPageArray, \Request::query());

        if (array_key_exists('page', $this->queryNoPageArray)) {
            unset($this->queryNoPageArray['page']);
        }
        $this->queryNoPageNeedleArray = $this->queryNoPageArray;
        if (!empty($this->needle)) {
            $this->queryNoPageNeedleArray['needle'] = $this->needle;
        }

        $this->firstCaption = $this->params['first-caption'] ?? '«';
        $this->lastCaption = $this->params['last-caption'] ?? '»';

        $this->uClass = $this->params['ul-class'] ?? 'pagination custom-pagination';
        $this->liClass = $this->this->params['li-class'] ?? 'li-class';
        $this->liFirstClass = $this->params['li-first-class'] ?? 'li-first-class';
        $this->liLastClass = $this->params['li-last-class'] ?? 'li-last-class';
        $this->liEdgeClass = $this->params['li-edge-class'] ?? 'li-edge-class';
        $this->liInnerClass = $this->params['li-inner-class'] ?? 'li-inner-class';
        $this->selectedClass = $this->params['selected-class'] ?? 'active selected';
        $this->disabledClass = $this->params['disabled-class'] ?? 'disabled';

        $this->lang = $this->params['lang'] ?? 'en';

        $halfTotalLinks = floor($this->links / 2);
        $this->from = $this->collection->currentPage() - $halfTotalLinks;
        $this->to = $this->collection->currentPage() + $halfTotalLinks;
        if ($this->collection->currentPage() < $halfTotalLinks) {
            $this->to += $halfTotalLinks - $this->collection->currentPage();
        }
        if ($this->collection->lastPage() - $this->collection->currentPage() < $halfTotalLinks) {
            $this->from -= $halfTotalLinks - ($this->collection->lastPage() - $this->collection->currentPage()) - 1;
        }
    }

    public function render()
    {
        return view('components.paginator');
    }
}
