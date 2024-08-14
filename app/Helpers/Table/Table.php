<?php

namespace App\Helpers\Table;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class Table {
    private array $defaultActions = [
        "search" => false,
        "create" => false,
        "update" => false,
        "delete" => false,
        "show"   => false,
    ];

    protected $model;
    protected $modelClass;

    protected int $itemsPerPage = 5;

    protected array $searchableColumnsName = [];

    private Request $request;

    public function __construct() {
        $this->modelClass = new $this->model;
    }

    protected function columns(): array {
        return [];
    }

    protected function filters(): array {
        return [];
    }

    protected function actions(): array {
        return [];
    }

    public static function table(Request $request) {
        $table = (new static);
        
        $table->request = $request;

        $columns = $table->generateColumns();
        $filters = $table->generateFilters();
        $actions = $table->generateActions();
        $miscs   = $table->generateMiscs();
        $sort    = $table->generateSort();
        $data    = $table->generateData();

        return [
            "columns" => $columns,
            "actions" => $actions,
            "filters" => $filters,
            "miscs"   => $miscs,
            "sort"    => $sort,
            "data"    => $data,
        ];
    }

    private function generateColumns() {
        $columns = [];

        foreach ($this->columns() as $column) {
            $column = $column->generate();

            
            $key = array_key_first($column);
            
            $columns[$key] = $column[$key];

            if ($column[$key]["searchable"]) {
                array_push($this->searchableColumnsName, $key);
            }
        }

        return $columns;
    }

    private function generateFilters() {
        return $this->filters();
    }

    private function generateActions() {
        $actions = $this->defaultActions;

        foreach ($this->actions() as $key => $action) {
            if ($this->defaultActions[$key] != $action) {
                $actions[$key] = $action;
            }
        }

        return $actions;
    }

    private function generateMiscs() {
        return [
            "columns_count" => count($this->columns()),
        ];
    }

    private function generateSort() {
        $sort = $this->request->sort;
        
        if(!$sort) {
            return [];
        }
        
        return [
            "column" => explode(",", $sort)[0],
            "sort" => explode(",", $sort)[1]
        ];
    }

    private function generateData() {
        $eloquent = $this->modelClass;

        $search = $this->request->search;
        $sort   = $this->request->sort;

        // If search parameter is given
        if ($search) {
            $eloquent = $eloquent->whereAny(
                $this->searchableColumnsName, 
                "like", "%{$search}%"
            );
        }

        // If sort parameter is given
        if ($sort) {
            $eloquent = $eloquent->orderBy(
                explode(",", $sort)[0],
                explode(",", $sort)[1]
            );
        }
        else {
            // Sort by desc created_at by default
            $eloquent = $eloquent->orderBy('created_at', 'desc');
        }
        
        return $eloquent
        ->paginate($this->itemsPerPage)
        ->withQueryString()
        ->through(function($model) {
            $results = ["id" => $model->id];
            
            foreach ($this->columns() as $column) {
                $key = $column->name;
                
                if (Str::contains($key, ".")) {
                    $key1 = Str::before($key, ".");
                    $key2 = Str::after($key, ".");

                    $results[$key] = $model->$key1->$key2;
                }
                else if ($column->type == "date") {
                    $date = new Carbon($model->$key);
                    $date = $date->format($column->date_format);
                    
                    $results[$key] = $date;
                }
                else {
                    $results[$key] = $model->$key;
                }                
            }
            
            return $results;
        });
    }
}