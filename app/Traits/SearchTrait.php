<?php namespace App\Traits;

/** 2025-06-02 - be able to search date field. add :date as operator in model declaration
 *
 * 2023-02-16 - replace == by ===
 */

trait SearchTrait
{
    public abstract function searchableColumns();

    public function scopeSearch($query, $needle)
    {
        if ( ! empty($needle)) {
            $query->where(function($q) use ($needle) {
                foreach ($this->searchableColumns() as $field => $operator) {
                    if ($field === 'childModels') {
                        foreach ($operator as $modelName => $content) {
                            $fields = $content['fields'];
                            $q->orWhereHas($modelName, function ($z) use ($needle, $modelName, $fields) {
                                $z->where(function($w) use ($needle, $fields) {
                                    foreach ($fields as $f => $op) {
                                        if ($op === ':date') {
                                            $op = '=';
                                            try {
                                                if (str_contains($needle, '/')) {
                                                    $needle = \Carbon\Carbon::createFromFormat('m/d/Y', $needle)->toDateString();
                                                } else if (str_contains($needle, '-')) {
                                                    $needle = \Carbon\Carbon::createFromFormat('d-m-Y', $needle)->toDateString();
                                                }
                                                $w->orWhere($f, $op, $needle);
                                            } catch (\Exception $e) {
                                                $w->orWhere($f, $op, $needle);
                                            }
                                        } else if ($op === 'LIKE') {
                                            $w->orWhere($f, $op, '%' . $needle . '%');
                                        } else {
                                            $w->orWhere($f, $op, $needle);
                                        }
                                    }
                                });
                            });
                        }
                    } else if ($field === 'childModel') {
                        $modelName = $operator['modelName'];
                        $fields = $operator['fields'];
                        $q->orWhereHas($modelName, function ($z) use ($needle, $modelName, $fields) {
                            $z->where(function($w) use ($needle, $fields) {
                                foreach ($fields as $f => $op) {
                                    if ($op === ':date') {
                                        $op = '=';
                                        try {
                                            if (str_contains($needle, '/')) {
                                                $needle = \Carbon\Carbon::createFromFormat('m/d/Y', $needle)->toDateString();
                                            } else if (str_contains($needle, '-')) {
                                                $needle = \Carbon\Carbon::createFromFormat('d-m-Y', $needle)->toDateString();
                                            }
                                            $w->orWhere($f, $op, $needle);
                                        } catch (\Exception $e) {
                                            $w->orWhere($f, $op, $needle);
                                        }
                                    } else if ($op === 'LIKE') {
                                        $w->orWhere($f, $op, '%' . $needle . '%');
                                    } else {
                                        $w->orWhere($f, $op, $needle);
                                    }
                                }
                            });
                        });
                    } else if ($field === 'childModelsThrough') {
                        foreach ($operator as $modelName => $content) {
                            $through = $content['through'];
                            $fields = $content['fields'];
                            $q->orWhereHas($through, function($r) use ($needle, $modelName, $fields){
                                $r->orWhereHas($modelName, function ($z) use ($needle, $modelName, $fields) {
                                    $z->where(function($w) use ($needle, $fields) {
                                        foreach ($fields as $f => $op) {
                                            if ($op === ':date') {
                                                $op = '=';
                                                try {
                                                    if (str_contains($needle, '/')) {
                                                        $needle = \Carbon\Carbon::createFromFormat('m/d/Y', $needle)->toDateString();
                                                    } else if (str_contains($needle, '-')) {
                                                        $needle = \Carbon\Carbon::createFromFormat('d-m-Y', $needle)->toDateString();
                                                    }
                                                    $w->orWhere($f, $op, $needle);
                                                } catch (\Exception $e) {
                                                    $w->orWhere($f, $op, $needle);
                                                }
                                            } else if ($op === 'LIKE') {
                                                $w->orWhere($f, $op, '%' . $needle . '%');
                                            } else {
                                                $w->orWhere($f, $op, $needle);
                                            }
                                        }
                                    });
                                });
                            });
                        }
                    } else {
                        if ($operator === ':date') {
                            $operator = '=';
                            try {
                                if (str_contains($needle, '/')) {
                                    $needle = \Carbon\Carbon::createFromFormat('m/d/Y', $needle)->toDateString();
                                } else if (str_contains($needle, '-')) {
                                    $needle = \Carbon\Carbon::createFromFormat('d-m-Y', $needle)->toDateString();
                                }
                                $q->orWhere($field, $operator, $needle);
                            } catch (\Exception $e) {
                                $q->orWhere($field, $operator, $needle);
                            }
                        } else if ($operator === 'LIKE') {
                            $q->orWhere($field, $operator, '%' . $needle . '%');
                        } else {
                            $q->orWhere($field, $operator, $needle);
                        }
                    }
                }
            });
        }

        return $query;
    }

}