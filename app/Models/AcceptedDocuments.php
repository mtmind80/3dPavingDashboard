<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcceptedDocuments extends Model
{
    public $timestamps = false;

    protected $table = 'accepted_documents';

    /** Methods */

    static public function extensionsStrCid()
    {
        $items = self::pluck('extension')->toArray();

        if (empty($items) || count($items) === 0) {
            return null;
        }

        return preg_replace(['/\s+/', '/,+/'], ['', ','], implode(',', $items));
    }

}
