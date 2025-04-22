<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ProposalMaterial extends Model
{

    protected $table = 'proposal_materials';

    // Relationships:

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function serviceCategory()
    {
        return $this->belongsTo(ServiceCategory::class);
    }
    
    public function scopeByServiceCategory($query, $service_category_id)
    {
        $data = array();
        $results = $query->where('service_category_id', '=',$service_category_id)->get()->toArray();
        $r = array();
        foreach ($results as $result)
        {
            $r['id'] = $result['id'];
            $r['name'] = $result['name'] . ' - ' . number_format($result['cost'], 2);
            $r['cost'] = number_format($result['cost'], 2);
            $data[] = $r;
            }
        return $data;
    }
    
}
