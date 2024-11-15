<?php

namespace spekt08\Search\Cases;


use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class OrderCase implements SearchCaseInterface 
{

    public function searchQuery(Builder $query,array $params): Builder 
    {
        if(isset($params['order'])){
            $query->orderBy('created_at', $params['order']);
        }else {
            $query->orderBy('created_at', 'DESC');
        }
        return $query;
    }
}