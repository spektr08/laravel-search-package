<?php

namespace spekt08\Search\Search\Cases;


use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class CallbackCase implements SearchCaseInterface 
{

    public function searchQuery(Builder $query,array $params): Builder 
    {
        if(!empty($params['callbacks'])){
            foreach($params['callbacks'] as $callback){
               $callback($query);
            }
        }
        return $query;
    }
}