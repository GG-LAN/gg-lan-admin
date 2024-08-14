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

        $columns = $table->makeColumns();
        $filters = $table->makeFilters();
        $actions = $table->makeActions();
        $miscs   = $table->makeMiscs();
        $sort    = $table->makeSort();
        $data    = $table->makeData();

        return [
            "columns" => $columns,
            "actions" => $actions,
            "filters" => $filters,
            "miscs"   => $miscs,
            "sort"    => $sort,
            "data"    => $data,
        ];
    }

    private function makeColumns() {
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

    private function makeFilters() {
        return $this->filters();
    }

    private function makeActions() {
        $actions = $this->defaultActions;

        foreach ($this->actions() as $key => $action) {
            if ($this->defaultActions[$key] != $action) {
                $actions[$key] = $action;
            }
        }

        return $actions;
    }

    private function makeMiscs() {
        return [
            "columns_count" => count($this->columns()),
        ];
    }

    private function makeSort() {
        $sort = $this->request->sort;
        
        if(!$sort) {
            return [];
        }
        
        return [
            "column" => explode(",", $sort)[0],
            "sort" => explode(",", $sort)[1]
        ];
    }

    private function makeData() {
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
                $data = $this->handleColumnToData($model, $column);
                
                $results[$data["key"]] = $data["value"];
            }
            
            // dd($results);
            return $results;
        });
    }

    private function handleColumnToData($model, $column) {
        $key = $column->name;
        $value = "";
                
        if (Str::contains($key, ".")) {
            $key1 = Str::before($key, ".");
            $key2 = Str::after($key, ".");

            $value = $model->$key1->$key2;
        }
        else {
            switch ($column->type) {
                case 'date':
                    $date = new Carbon($model->$key);
                    $date = $date->format($column->date_format);

                    $value = $date;
                break;

                case 'compact':
                    $value = "";
                    
                    // dd($key);
                    foreach ($column->columns as $colKey => $compactColumn) {
                        $value .= $colKey ? $column->separator : "";
                        
                        $value .= $this->handleColumnToData($model, $compactColumn)["value"];
                    }
                break;
                
                default:
                    $value = $model->$key;
                break;
            }
        }

        return [
            "key" => $key,
            "value" => $value
        ];
    }
}