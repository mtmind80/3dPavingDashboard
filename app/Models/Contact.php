<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\SortableTrait;
use App\Traits\SearchTrait;

class Contact extends Model
{
    use SortableTrait, SearchTrait;

    protected $dates = ['deleted_at'];

    protected $appends = ['full_name'];

    protected $fillable = [
        'contact_type_id',
        'first_name',
        'last_name',
        'lead_id',
        'email',
        'alt_email',
        'phone',
        'alt_phone',
        'address1',
        'address2',
        'city',
        'county',
        'state',
        'postal_code',
        'same_billing_address',
        'billing_address1',
        'billing_address2',
        'billing_city',
        'billing_state',
        'billing_postal_code',
        'contact',
        'note',
        'related_to',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $sortable = [
        'first_name',
        'email',
        'phone',
        'county',
        'created_at',
        'deleted_at',
        'contacts.contact_type_id|contact_types.type',
        'contacts.created_by|users.fname',
        'contacts.assigned_to|users.fname',
        'contacts.related_to|contacts.first_name',
    ];

    public $searchable = [
        'contacts.first_name' => 'LIKE',
        'contacts.last_name'  => 'LIKE',
        'contacts.email'      => 'LIKE',
        'contacts.alt_email'  => 'LIKE',
        'contacts.phone'      => 'LIKE',
        'contacts.alt_phone'  => 'LIKE',
        'contacts.state'      => 'LIKE',
        'contacts.county'     => 'LIKE',
        'childModels'   => [
            'contactType' => [
                'fields' => [
                    'type' => 'LIKE',
                ],
            ],
        ],
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

    public function contactType()
    {
        return $this->belongsTo(ContactType::class);
    }

    public function notes()
    {
        return $this->hasMany(ContactNote::class);
    }

    public function lastNote()
    {
        return $this->hasOne(ContactNote::class)->orderBy('created_at', 'DESC');
    }

    public function staff()
    {
        // other contact than General Contact type
        return $this->hasMany(Staff::class, 'related_to');
    }

    public function company()
    {
        // for General Contact type only
        return $this->belongsTo(Company::class, 'related_to');
    }

    public function assignedTo()
    {
        // for General Contact type only
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        // for General Contact type only
        return $this->belongsTo(User::class, 'created_by');
    }

    public function proposals()
    {
        // other contact than General Contact type
        return $this->hasMany(Proposal::class, 'contact_id');
    }

    public function relatedTo()   // similar to company
    {
        return $this->belongsTo(Contact::class, 'related_to');
    }

    public function leads()
    {
        return $this->hasMany(Lead::class, 'contact_id');
    }

    /** scopes */

    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    public function scopeCompany($query)
    {
        return $query->whereNull('related_to');
    }

    public function scopeStaff($query)
    {
        return $query->whereNoNull('related_to');
    }

    /** Accessor(get) and Mutators(set) */

    public function getStringNameAttribute()
    {
        return trim(strtolower($this->first_name . '_' . $this->last_name));
    }

    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getEmailsOneLinesAttribute()
    {
        $arr = [$this->email];

        if (!empty($this->alt_email) && !in_array($this->alt_email, $arr)) {
            $arr[] = $this->alt_email;
        }
        return implode(', ', $arr);
    }

    public function getEmailsTwoLinesAttribute()
    {
        $arr = [$this->email];

        if (!empty($this->alt_email) && !in_array($this->alt_email, $arr)) {
            $arr[] = $this->alt_email;
        }
        return implode('<br>', $arr);
    }

    public function getPhonesOneLineAttribute()
    {
        $arr = [$this->phone];

        if (!empty($this->alt_phone) && !in_array($this->alt_phone, $arr)) {
            $arr[] = $this->alt_phone;
        }
        return implode(', ', $arr);
    }

    public function getPhonesTwoLinesAttribute()
    {
        $arr = [$this->phone];

        if (!empty($this->alt_phone) && !in_array($this->alt_phone, $arr)) {
            $arr[] = $this->alt_phone;
        }
        return implode('<br>', $arr);
    }

    public function getFullAddressOneLineAttribute()
    {
        return self::buildFullAddress($this->address1, $this->city, $this->county, $this->state, $this->postal_code, ', ', $this->address2);
    }

    public function getFullAddressTwoLineAttribute()
    {
        return self::buildFullAddress($this->address1, $this->city, $this->county, $this->state, $this->postal_code, '<br>', $this->address2);
    }

    public function getFullBillingAddressOneLineAttribute()
    {
        return self::buildFullAddress($this->billing_address1, $this->billing_city, null, $this->billing_state, $this->billing_postal_code, ', ', $this->billing_address2);
    }

    public function getFullBillingAddressTwoLineAttribute()
    {
        return self::buildFullAddress($this->billing_address1, $this->billing_city, null, $this->billing_state, $this->billing_postal_code, '<br>', $this->billing_address2);
    }

    /** Methods */

    public function isDeleted()
    {
        return !empty($this->deleted_at);
    }

    public function isActive()
    {
        return ! $this->isDeleted();
    }

    public static function buildFullAddress($address, $city = '', $county = '', $state = '', $zip = '', $separator = '<br>', $address2 = '')
    {
        $result = [];
        $line1 = !empty($address) ? [$address] : [];
        if (!empty($address2)) {
            $line1[] = $address2;
        }
        if (count($line1)) {
            $result[] = count($line1) > 1 ? implode(', ', $line1) : $line1[0];
        }

        $line2 = [];
        if (!empty($city)) {
            $line2[] = $city;
        }
        if (!empty($county)) {
            $line2[] = $county;
        }
        $stateZip = [];
        if (!empty($state)) {
            $stateZip[] = $state;
        }
        if (!empty($zip)) {
            $stateZip[] = $zip;
        }
        if (!empty($stateZip)) {
            $line2[] = implode(' ', $stateZip);
        }
        if (!empty($line2)) {
            $result[] = implode(', ', $line2);
        }

        return trim(preg_replace('/\s+/', ' ', implode($separator, $result)));
    }

    public function delete()
    {
        $this->update(['deleted_at' => now()]);
    }

    public function restore()
    {
        $this->update(['deleted_at' => null]);
    }

    public function isStaff()
    {
        return !empty($this->related_to);
    }

    public function isCompany()
    {
        return empty($this->related_to) && $this->contact_type_id != 18;
    }

    public function hasCompany()
    {
        return !empty($this->related_to);
    }

    static public function assignedToCB($default = [])
    {
        $items = User::office()->orderBy('fname')->get()->pluck('full_name', 'id')->toArray();

        if (!empty($default)) {
            return $default + $items;
        }

        return $items;
    }

}
