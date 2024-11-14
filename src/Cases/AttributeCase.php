<?php

namespace spekt08\Search\Search\Cases;


use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class AttributeCase implements SearchCaseInterface 
{

    private $not_columns = ['page', 'order'];

    private $table_columns = [];

    private $date_columns = [
        'created_at', 
        'updated_at'
    ];


    public function setTableColumns(array $array)
    {
        $this->table_columns = $array;
    }

    public function setNotColumns(array $array)
    {
        $this->not_columns = $array;
    }

    public function setDateColumns(array $array)
    {
        $this->date_columns = $array;
    }
    public function searchQuery(Builder $query,array $params): Builder 
    {
        
        foreach ($params as $key => $item) { 
            if (in_array($key, $this->not_columns)) {continue;}
            if (!empty($this->table_columns) && !in_array($key, $this->table_columns)) {
                continue; // Skip if the column is not found
            }
            if (in_array($key, $this->date_columns)) {
                $from = Carbon::parse($item);
                $to = $from->clone()->addDays(1);
                $query->whereBetween($key, [$from, $to]);
            } else {
                if (!is_array($item) and strpos($item, '!') === 0) {
                    $query->where($key, '!=', substr($item, 1));
                } else {
                    if (!is_array($item) and strpos($item, ',') !== false) {
                        // The string contains a comma
                        $idsArray = explode(',', $item);
                        $query->whereIn($key, $idsArray);
                    } else {
                        if (is_array($item)) {
                            $query->whereIn($key, $item);
                        } else {
                            $query->where([$key=>$item]);
                        }
                        
                    }
                }
            }
        }       
        return $query;
    }
}