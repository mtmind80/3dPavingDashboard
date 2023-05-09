<?php namespace App\Traits;

/**  Version 2.6 - 2022-07-08 - Added alias to related table when it is the same as maintable to avoid ambiguity
 *
 * v2.5 - 2020-07-31 - Removed encLink() function (and ENCRYPTION_KEY definition from .env)
 * v2.4 - 2018-04-09 - Use urldecode at the begining to avoid issue on https (| is changed to %257C)
 * v2.3 - 2017-12-20 - Update encSortable for new syntax on sortable array declaration (this model table name at the begining)
 * v2.2 - 2017-09-18 - Updated link function to allow route array as third parameter
 * v2.1 - 2017-08-11 - Added encSortable scope (from noticiero). Still uses old version of relationships declaration
 * v2.0 - 2017-08-11 - Added bridge table and reorder tables as maintable.foreignkeyfield[|bridgetable-tomainfield-torelatedfield]relatedtable.field[.mainkeyfield]
 *
 */

trait SortableTrait
{
    public abstract function sortableColumns();

    private function _buildQuery($query, $defaultFieldOrder = false, $defaultTypeOrder = 'asc', $enc = false)
    {
        $f = urldecode(\Request::get('f'));

        if ($f && $enc) {
            $f =  self::decrypt($f);
        }

        if ($f && \Request::has('o') && in_array($f, $this->sortableColumns())) {
            if (strpos($f, '|')) {
                $segments = explode('|', $f);
                if (count($segments) == 2) {

                    list($main, $related) = $segments;

                    list($mainTable, $mainField) = explode('.', $main);

                    if (substr_count($related, '.') == 2) {
                        list($relatedTable, $relatedField, $relatedKey) = explode('.', $related);
                    } else {
                        list($relatedTable, $relatedField) = explode('.', $related);
                        $relatedKey = 'id';
                    }

                    if ($relatedTable == $mainTable) {
                        return $query
                            ->leftJoin($relatedTable . ' AS RELATED_TABLE', 'RELATED_TABLE.' . $relatedKey, '=', $mainTable .'.' . $mainField)
                            ->orderBy('RELATED_TABLE.' . $relatedField, \Request::get('o'))
                            ->select([$mainTable.'.*']);
                    } else {
                        return $query
                            ->leftJoin($relatedTable, $relatedTable . '.' . $relatedKey, '=', $mainTable .'.' . $mainField)
                            ->orderBy($relatedTable . '.' . $relatedField, \Request::get('o'))
                            ->select([$mainTable.'.*']);
                    }
                } else if (count($segments) == 3) {
                    list($main, $bridge, $related) = $segments;

                    if (substr_count($related, '.') == 2) {
                        list($relatedTable, $relatedField, $relatedKey) = explode('.', $related);
                    } else {
                        list($relatedTable, $relatedField) = explode('.', $related);
                        $relatedKey = 'id';
                    }

                    list($mainTable, $mainField) = explode('.', $main);

                    list($bridgeTable, $bridgeToMain, $bridgeToRelated) = explode('-', $bridge);

                    return $query->leftJoin($bridgeTable, $bridgeTable . '.' . $bridgeToMain, '=', $mainTable .'.' . $mainField)
                        ->leftJoin($relatedTable, $relatedTable . '.' . $relatedKey, '=', $bridgeTable .'.' . $bridgeToRelated)
                        ->orderBy($relatedTable . '.' . $relatedField, \Request::get('o'))
                        ->select([$mainTable.'.*']);
                }
            } else {
                return $query->orderBy($f, \Request::get('o'));
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
        $f = urldecode(\Request::get('f'));

        if (is_null($title)) {
            $title = str_replace('_', ' ', $col);
            $title = ucfirst($title);
        }

        $indicator =  $f && urldecode($f) == $col ? (\Request::get('o') === 'asc' ? ' <i class="fas fa-arrow-up direction-arrow"></i>' : ' <i class="fas fa-arrow-down direction-arrow"></i>') : null;

        $parameters = array_merge($routeParams, \Request::query(), ['f' => $col, 'o' => (\Request::get('o') === 'asc' ? 'desc' : 'asc')]);

        return '<a href="' . route(\Route::currentRouteName(), $parameters) . '">' . $title . $indicator . '</a>';
    }


    /** toggle button */
    public static function orderLink($mainFieldName, $params)
    {
        $f = urldecode(\Request::get('f'));

        if (!is_null($params['text'])) {
            $text = $params['text'];
        } else {
            $text = str_replace('_', ' ', $mainFieldName);
            $text = ucfirst($text);
        }
        $class = (!empty($params['class'])) ? ' class="' . $params['class'] . '"' : '';
        $title = (!empty($params['title'])) ? ' title="' . $params['title']  . '"' : '';

        $indicator =  $f == $mainFieldName ? (\Request::get('o') === 'asc' ? ' <i class="fas fa-arrow-up direction-arrow"></i>' : ' <i class="fas fa-arrow-down direction-arrow"></i>') : null;
        $parameters = array_merge(\Request::input(), array('f' => $mainFieldName, 'o' => (\Request::get('o') === 'asc' ? 'desc' : 'asc')));

        return '<a href="' . route(\Route::currentRouteName(), $parameters) . '"' . $class . $title . '>' . $text . $indicator . '</a>';
    }

    /** for one direction  buttons */
    public static function fixedOrderLink($col, $direction, $title = null)
    {
        if (is_null($title)) {
            $title = '&nbsp;';
        }
        $direction = strtolower($direction);
        if ($direction != 'asc' && $direction != 'desc') {
            $direction = 'asc';
        }
        $parameters = array_merge(\Request::input(), array('f' => $col, 'o' => $direction));

        return '<a href="' . route(\Route::currentRouteName(), $parameters) . '">' . $title . '</a>';
    }

    public static function fixedOrderRoute($col, $direction = 'asc')
    {
        $direction = strtolower($direction);
        if ($direction != 'asc' && $direction != 'desc') {
            $direction = 'asc';
        }
        $parameters = array_merge(\Request::input(), array('f' => $col, 'o' => $direction));

        return route(\Route::currentRouteName(), $parameters);
    }

}
