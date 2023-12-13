<?php

namespace App\Models;

use App\Traits\SearchTrait;
use App\Traits\SortableTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, SortableTrait, SearchTrait;

    protected $appends = ['full_name'];

    protected $fillable = [
        'fname',
        'lname',
        'email',
        'phone',
        'password',
        'status',
        'language',
        'sales_goals',
        'rate_per_hour',
        'role_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public $sortable = [
        'fname',
        'lname',
        'email',
        'phone',
        'status',
        'role_id',
    ];

    public $searchable = [
        'users.fname' => 'LIKE',
        'users.lname'  => 'LIKE',
        'users.email'      => '=',
        'users.phone'      => '=',
    ];

    public function sortableColumns()
    {
        return $this->sortable;
    }

    public function searchableColumns()
    {
        return $this->searchable;
    }

    /** relationships */

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function proposalManager()
    {
        return $this->hasMany(Proposal::class, 'salesmanager_id','id');
    }

    public function proposalsAsSalesPerson()
    {
        return $this->hasMany(Proposal::class, 'salesperson_id');
    }
    public function notes() :HasMany
    {
        return $this->hasMany(ProposalNote::class, 'created_by');
    }

    /** scopes */

    //soft deleted
    public function scopeDeleted($query)
    {
        return $query->whereNotNull('deleted_at')->orderBy('lname')->orderBy('fname');
    }
    //active employees
    public function scopeActive($query)
    {
        return $query->where('status', 1)->orderBy('lname')->orderBy('fname');
    }

    public function scopeDeveloper($query) //developer user
    {
        return $query->where('status', 1)->where('role_id', 7)->orderBy('lname')->orderBy('fname');
    }

       public function scopeOffice($query) //admin user
    {
        return $query->where('status', 1)->where('role_id', 2)->orderBy('lname')->orderBy('fname');
    }

    public function scopeManager($query)  // sales managers and super admin
    {
        return $query->where('status', 1)->whereIn('role_id', [1,3])->orderBy('lname')->orderBy('fname');
    }

    public function scopeManagers($query) //sales  managers and pavement consultants
    {
        return $query->where('status', 1)->whereIn('role_id', [3,4,7])->orderBy('lname')->orderBy('fname');
    }

    public function scopeSales($query)  //pavement consultant
    {
        return $query->where('status', 1)->where('role_id', 4)->where('status', 1)->orderBy('lname')->orderBy('fname');
    }

    public function scopeAllManagers($query)//super admin , sales  managers and pavement consultants
    {
        return $query->where('status', 1)->whereIn('role_id', [1, 3,4])->orderBy('lname')->orderBy('fname');
    }

    public function scopeTotalManagers($query)//all admin, sales, managers and pavement consultants and super admin
    {
        return $query->whereIn('role_id', [1, 2, 3,4])->where('status', 1)->orderBy('lname')->orderBy('fname');
    }

    public function scopeFieldManager($query)//all admin, sales, managers and pavement consultants and super admin
    {
        return $query->where('role_id', 5)->where('status', 1)->orderBy('lname')->orderBy('fname');
    }

    public function scopeLabor($query)//alabor only
    {
        return $query->where('role_id', 6)->where('status', 1)->orderBy('lname')->orderBy('fname');
    }

    public function scopeEmployee($query)
    {
        return $query->where('role_id', 6);
    }

    public function scopeCanHaveLeads($query)
    {
        return $query->whereIn('role_id', [1,2,7]);
    }

    /** Accessor(get) and Mutators(set) */

    public function getFullNameAttribute()
    {
        return trim($this->fname . ' ' . $this->lname);
    }

    /** Methods */

    public function isDeveloper()
    {
        return $this->role_id == 7;
    }

    public function isOffice()  //office only functions
    {
        return in_array($this->role_id, [1,2,3,4,7]);
    }

    public function isSuperAdmin() //can do most anything
    {
        return in_array($this->role_id, [1,7]);
    }

    public function isAdmin()
    {
        return in_array($this->role_id, [1,2,7]);
    }

    public function isEmployee()
    {
        return $this->role_id == 6;
    }

    public function isAllSales()
    {
        return in_array($this->role_id, [1,3,4]);
    }

    public function isSalesManager()
    {
        return $this->role_id == 3;
    }

    public function isSalesPerson()
    {
        return $this->role_id == 4;
    }

    public function isActive()
    {
        return $this->status;
    }

    public function canHaveLeads()
    {
        return in_array($this->role_id, [1,2,7]);
    }

    static public function employeesCB($default = [])
    {
        $items = self::employee()->active()->get()->pluck('full_name', 'id')->toArray();

        if (!empty($default)) {
            return $default + $items;
        }

        return $items;
    }

    static public function managersCB($default = [])
    {
        $items = self::managers()->get()->pluck('full_name', 'id')->toArray();

        if (!empty($default)) {
            return $default + $items;
        }

        return $items;
    }
    static public function managerCB($default = [])
    {
        $items = self::manager()->get()->pluck('full_name', 'id')->toArray();

        if (!empty($default)) {
            return $default + $items;
        }

        return $items;
    }



    static public function salesCB($default = [])
    {
        $items = self::sales()->get()->pluck('full_name', 'id')->toArray();

        if (!empty($default)) {
            return $default + $items;
        }

        return $items;
    }

    static public function allmanagersCB($default = [])
    {
        $items = self::allmanagers()->get()->pluck('full_name', 'id')->toArray();

        if (!empty($default)) {
            return $default + $items;
        }

        return $items;
    }
    static public function laborCB($default = [])
    {
        $items = self::labor()->get()->pluck('full_name', 'id')->toArray();

        if (!empty($default)) {
            return $default + $items;
        }

        return $items;
    }
    static public function fieldmanagersCB($default = [])
    {
        $items = self::fieldmanager()->get()->pluck('full_name', 'id')->toArray();

        if (!empty($default)) {
            return $default + $items;
        }

        return $items;
    }


}
