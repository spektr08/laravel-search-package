<?php
namespace spekt08\Search\Search\Cases;

use Illuminate\Database\Eloquent\Builder;
interface SearchCaseInterface 
{
    
    public function searchQuery(Builder $query,array $params): Builder ;
   
}