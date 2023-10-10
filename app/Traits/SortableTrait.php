<?php namespace App\Traits;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\Request as FacadeRequest;
use Illuminate\Support\Facades\Route;

/**  2023-09-28 - Added function linkToRoute to replace link_to_route which is dependent of HTML package
 *
 * 2023-06-05 - Added aLink function (not static version of link) to be used in blades (using static link is deprecated in PHP 8.1)
 * 2023-05-02 - Added alias to related table when it is the same as main table, as maintable.foreignkeyfield|relatedtable_as_aliastable.field[.mainkeyfield]. (example: 'users.sales_person_id|users_as_clients.first_name')
 * 2023-02-16 - Remove use of \ by use related facade
 * 2020-07-31 - Removed encLink() function (and ENCRYPTION_KEY definition from .env)
 * 2018-04-09 - Use urldecode at the begining to avoid issue on https (| is changed to %257C)
 * 2017-12-20 - Update encSortable for new syntax on sortable array declaration (this model table name at the begining)
 * 2017-09-18 - Updated link function to allow route array as third parameter
 * 2017-08-11 - Added encSortable scope (from noticiero). Still uses old version of relationships declaration
 * 2017-08-11 - Added bridge table and reorder tables as maintable.foreignkeyfield[|bridgetable-tomainfield-torelatedfield]relatedtable.field[.mainkeyfield]
 */

trait SortableTrait
{
    public abstract function sortableColumns();

    private function _buildQuery($query, $defaultFieldOrder = false, $defaultTypeOrder = 'asc', $enc = false)
    {
        $f = urldecode(FacadeRequest::get('f'));

        if ($f && $enc) {
            $f =  self::decrypt($f);
        }

        if ($f && FacadeRequest::has('o') && in_array($f, $this->sortableColumns())) {
            if (strpos($f, '|')) {
                $segments = explode('|', $f);
                if (count($segments) == 2) {

                    [$main, $related] = $segments;

                    [$mainTable, $mainField] = explode('.', $main);

                    if (substr_count($related, '.') == 2) {
                        [$relatedTable, $relatedField, $relatedKey] = explode('.', $related);
                    } else {
                        [$relatedTable, $relatedField] = explode('.', $related);
                        $relatedKey = 'id';
                    }

                    if (strpos($relatedTable, '_as_') !== false) {
                        [$relatedTable, $aliasTable] = explode('_as_', $relatedTable);

                        return $query->leftJoin($relatedTable . ' AS ' . $aliasTable, $aliasTable . '.' . $relatedKey, '=', $mainTable .'.' . $mainField)
                            ->orderBy($aliasTable . '.' . $relatedField, FacadeRequest::get('o'))
                            ->select([$mainTable.'.*']);
                    } else {
                        return $query->leftJoin($relatedTable, $relatedTable . '.' . $relatedKey, '=', $mainTable .'.' . $mainField)
                            ->orderBy($relatedTable . '.' . $relatedField, FacadeRequest::get('o'))
                            ->select([$mainTable.'.*']);
                    }
                } else if (count($segments) == 3) {
                    [$main, $bridge, $related] = $segments;

                    if (substr_count($related, '.') == 2) {
                        [$relatedTable, $relatedField, $relatedKey] = explode('.', $related);
                    } else {
                        [$relatedTable, $relatedField] = explode('.', $related);
                        $relatedKey = 'id';
                    }

                    [$mainTable, $mainField] = explode('.', $main);

                    [$bridgeTable, $bridgeToMain, $bridgeToRelated] = explode('-', $bridge);

                    return $query->leftJoin($bridgeTable, $bridgeTable . '.' . $bridgeToMain, '=', $mainTable .'.' . $mainField)
                        ->leftJoin($relatedTable, $relatedTable . '.' . $relatedKey, '=', $bridgeTable .'.' . $bridgeToRelated)
                        ->orderBy($relatedTable . '.' . $relatedField, FacadeRequest::get('o'))
                        ->select([$mainTable.'.*']);
                }
            } else {
                return $query->orderBy($f, FacadeRequest::get('o'));
            }
        } else if ($defaultFieldOrder) {
            $query->orderBy($defaultFieldOrder, $defaultTypeOrder);
        } else {
            return $query;
        }
    }

    public function scopeSortable($query, $defaultFieldOrder = false, $defaultTypeOrder = 'asc')
    {
        return $this->_buildQuery($query, $defaultFieldOrder, $defaultTypeOrder, false);
    }

    // This still uses old syntax! on sortable array declaration (this model table name at the end)

    public function scopeEncSortable($query, $defaultFieldOrder = false, $defaultTypeOrder = 'asc')
    {
        return $this->_buildQuery($query, $defaultFieldOrder, $defaultTypeOrder, true);
    }

    /** for table column headers */
    public static function link($col, $title = null, $routeParams = [])
    {
        $f = urldecode(FacadeRequest::get('f'));

        if (is_null($title)) {
            $title = str_replace('_', ' ', $col);
            $title = ucfirst($title);
        }

        $indicator = ($f && urldecode($f) == $col) ? (FacadeRequest::get('o') === 'asc' ? '&uarr;' : '&darr;') : null;
        $parameters = array_merge($routeParams, FacadeRequest::input(), ['f' => $col, 'o' => (FacadeRequest::get('o') === 'asc' ? 'desc' : 'asc')]);

        return self::linkToRoute("$title $indicator", Route::currentRouteName(), $parameters);
    }


    /** toggle button */
    public static function orderLink($mainFieldName, $params)
    {
        $f = urldecode(FacadeRequest::get('f'));

        if (!is_null($params['text'])) {
            $text = $params['text'];
        } else {
            $text = str_replace('_', ' ', $mainFieldName);
            $text = ucfirst($text);
        }
        $class = (!empty($params['class'])) ? ' class="' . $params['class'] . '"' : '';
        $title = (!empty($params['title'])) ? ' title="' . $params['title']  . '"' : '';

        $indicator =  $f == $mainFieldName ? (FacadeRequest::get('o') === 'asc' ? ' <i class="fa fa-sort-amount-asc ml5 m_right_0"></i>' : ' <i class="fa fa-sort-amount-desc ml5 m_right_0"></i>') : null;
        $parameters = array_merge(FacadeRequest::input(), array('f' => $mainFieldName, 'o' => (FacadeRequest::get('o') === 'asc' ? 'desc' : 'asc')));

        return '<a href="' . route(Route::currentRouteName(), $parameters) . '"' . $class . $title . '>' . $text . $indicator . '</a>';
    }

    /** for one direction  buttons */
    public static function fixedOrderLink($col, $direction, $title = null)
    {
        if (is_null($title)) {
            $title = '&nbsp;';
        }
        $direction = strtolower($direction);
        if ($direction !== 'asc' && $direction !== 'desc') {
            $direction = 'asc';
        }
        $parameters = array_merge(FacadeRequest::input(), array('f' => $col, 'o' => $direction));

        return self::linkToRoute("$title", Route::currentRouteName(), $parameters);
    }

    public static function fixedOrderRoute($col, $direction = 'asc')
    {
        $direction = strtolower($direction);
        if ($direction !== 'asc' && $direction !== 'desc') {
            $direction = 'asc';
        }
        $parameters = array_merge(FacadeRequest::input(), array('f' => $col, 'o' => $direction));

        return route(Route::currentRouteName(), $parameters);
    }

    /* Usage in blade:
     *
     *  @inject('SortableTrait', \App\Models\ClientApplication::class)
     *      1- Name of the trait in use clause
     *      2- Model class where trait ss declare and use
     *
     *  {!! $SortableTrait->alink('first_name', 'Nombres') !!}  - Use 1st parameter of @inject as a variable (prefixed by $)
     */

    public function alink($col, $title = null, $routeParams = [])
    {
        $f = urldecode(FacadeRequest::get('f'));

        if (is_null($title)) {
            $title = str_replace('_', ' ', $col);
            $title = ucfirst($title);
        }

        $indicator = ($f && urldecode($f) == $col) ? (FacadeRequest::get('o') === 'asc' ? '&uarr;' : '&darr;') : null;
        $parameters = array_merge($routeParams, FacadeRequest::input(), ['f' => $col, 'o' => (FacadeRequest::get('o') === 'asc' ? 'desc' : 'asc')]);

        return self::linkToRoute("$title $indicator", Route::currentRouteName(), $parameters);
    }

    private static function linkToRoute($label, $routeName, $routeParams = [], $attributes = '')
    {
        return '<a href="' . htmlentities(route($routeName, $routeParams), ENT_QUOTES, 'UTF-8', false) . '" ' . $attributes . '>' . $label . '</a>';
    }

}
