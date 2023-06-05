<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcceptedDocuments extends Model
{
    public $timestamps = false;

    protected $table = 'accepted_documents';

    /** Methods */

    static public function extensionsStr()
    {
        $items = self::extensionsArray();

        return !is_null($items) ? implode(',', $items) : null;
    }

    static public function extensionsArray()
    {
        if (!$items = self::pluck('extension')->toArray()) {
            return null;
        }

        return $items;
    }

}
