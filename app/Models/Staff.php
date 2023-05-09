<?php

namespace App\Models;

use App\Scopes\StaffScope;

class Staff extends Contact
{
    protected $table = 'contacts';

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new StaffScope);
    }

    // Relationships

    // Scopes:

    // Methods:

}
