<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ProposalMaterial extends Model
{

    protected $table = 'proposal_materials';
    
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
