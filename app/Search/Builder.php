<?php

namespace App\Search;

use TeamTNT\TNTSearch\TNTSearch;

class Builder
{
    protected $tnt;

    protected $model;

    protected $results;

    public function __construct(TNTSearch $tnt, $model)
    {
        $this->tnt = $tnt;
        $this->model = $model;
    }

    public function search($q, $limit = 12)
    {
        $this->tnt->selectIndex($this->model::SEARCH_INDEX);

        $this->results = $this->tnt->search($q, $limit);

        return $this;
    }

    public function raw()
    {
        return $this->results;
    }

    public function get()
    {
        ['ids' => $ids] = $this->results;

        return $this->model::whereIn('id', $ids)
            ->orderBy(Manager::connection()->raw('FIELD(id, ' . implode(',', $ids) . ')'))
            ->get();
    }
}