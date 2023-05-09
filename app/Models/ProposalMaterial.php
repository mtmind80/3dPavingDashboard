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
        foreach ($results as $result)
        {
            $data[$result['id']] = $result['name'] . ' - ' . number_format($result['cost'], 2);
            
            }
        return $data;
    }
    
}
