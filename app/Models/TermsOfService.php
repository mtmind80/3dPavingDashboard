<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TermsOfService extends Model
{
    public $timestamps = false;
    protected $table = 'terms_of_service';
    protected $guarded = ['id'];

}
