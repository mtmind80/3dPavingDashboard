<?php

namespace App\Models;

use App\Scopes\CompanyScope;

class Company extends Contact
{
    protected $table = 'contacts';

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CompanyScope);
    }

    // Relationships

    // Scopes:

    // Accessor(get) and Mutators(set)


    // Methods:

}
