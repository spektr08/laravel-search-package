<?php

namespace spekt08\Search;

use spekt08\Search\Cases\AttributeCase;
use spekt08\Search\Search\Cases\CallbackCase;
use spekt08\Search\Search\Cases\OrderCase;
use spekt08\Search\Search\Cases\SearchCaseInterface;

class SearchService
{
    private $model;

    private $cases;

    public function __construct(string $model)
    {
        $this->model = app($model);
        $this->cases = new \SplObjectStorage();
        $tableColumns = $this->model->getConnection()
            ->getSchemaBuilder()
            ->getColumnListing($this->model->getTable()); // Fetch all table columns
        $case = new AttributeCase();
        $case->setTableColumns($tableColumns);
        $this->add($case);

        $case = new OrderCase();
        $this->add($case);
        $case = new CallbackCase;
        $this->add($case);
    }


    public function add(SearchCaseInterface $case): void
    {
        $this->cases->attach($case);
    }



    public function search(array $queries, $resource = false)
    {
        if (empty($queries) || (array_key_exists('page',$queries) && count($queries)==1) ) {
            $result =  $this->model->paginate();
        } else {
            $result = $this->model->newQuery();
            foreach ($this->cases as $child) {       
                $result = $child->searchQuery($result, $queries);
            }
            $result = $result->paginate();
        }

        if($resource) {
            $result->getCollection()->transform(function ($task) use($resource) {
                return new $resource($task);
            });
        }

        return $result;
    }
}