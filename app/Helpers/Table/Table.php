<?php

namespace App\Helpers\Table;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Table
{
    private array $defaultActions = [
        "search" => false,
        "create" => false,
        "update" => false,
        "delete" => false,
        "show" => false,
        "customActions" => false,
    ];

    protected $model;
    protected Model $modelClass;

    protected int $itemsPerPage = 5;

    protected Collection $searchables;

    protected $defaultSort = "id,asc";

    protected bool $paginate = true;

    private Request $request;

    public function __construct(...$args)
    {
        $this->modelClass = new $this->model;

        $this->defaultSort = Str::replace(" ", "", $this->defaultSort);

        $this->searchables = collect();

        $this->request = request();

        foreach ($args[0] as $key => $arg) {
            $this->$key = $arg;
        }
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    protected function resource(): Builder | Model | BelongsToMany
    {
        return $this->modelClass;
    }

    protected function columns(): array
    {
        return [];
    }

    protected function filters(): array
    {
        return [];
    }

    protected function actions(): array
    {
        return [];
    }

    public static function table(...$args)
    {
        $table = (new static($args));

        $columns = $table->makeColumns();
        $filters = $table->makeFilters();
        $actions = $table->makeActions();
        $miscs = $table->makeMiscs();
        $sort = $table->makeSort();
        $search = $table->makeSearch();
        $data = $table->makeData();

        return [
            "columns" => $columns,
            "actions" => $actions,
            "filters" => $filters,
            "miscs" => $miscs,
            "sort" => $sort,
            "search" => $search,
            "data" => $data,
        ];
    }

    private function makeColumns()
    {
        $columns = [];

        foreach ($this->columns() as $column) {
            $column = $column->generate();

            $key = array_key_first($column);

            $columns[$key] = $column[$key];

            if ($column[$key]["searchable"]) {
                $this->searchables->push($key);
            }
        }

        return $columns;
    }

    private function makeFilters()
    {
        return $this->filters();
    }

    private function makeActions()
    {
        $actions = $this->defaultActions;

        foreach ($this->actions() as $key => $action) {
            if ($this->defaultActions[$key] != $action) {
                $actions[$key] = $action;
            }
        }

        return $actions;
    }

    private function makeMiscs()
    {
        return [
            "columns_count" => count($this->columns()),
            "pagination" => $this->paginate,
        ];
    }

    private function makeSort()
    {
        $sort = $this->request->sort;

        if (!$sort) {
            return [];
        }

        return [
            "column" => explode(",", $sort)[0],
            "sort" => explode(",", $sort)[1],
        ];
    }

    private function makeSearch()
    {
        return $this->request->search;
    }

    private function makeData()
    {
        $eloquent = $this->resource();
        $itemsPerPage = $this->itemsPerPage;

        $search = $this->request->search;
        $sort = $this->request->sort;
        $perPage = $this->request->perPage;

        // If search parameter is given
        if ($search) {
            $searchablesRelation = $this->searchables->filter(function ($value) {
                return Str::contains($value, ".");
            });

            if ($searchablesRelation->count()) {
                foreach ($searchablesRelation->toArray() as $key => $column) {
                    $this->searchables->forget($key);

                    $eloquent = $eloquent->whereHas(Str::beforeLast($column, "."), function (Builder $query) use ($searchablesRelation, $column, $search) {
                        $query->where(Str::afterLast($column, "."), 'like', "%$search%");
                    });
                }
            }

            $eloquent = $eloquent->whereAny(
                $this->searchables->toArray(),
                "like", "%{$search}%"
            );
        }

        // If sort parameter is given
        if ($sort) {
            $eloquent = $eloquent->orderBy(
                explode(",", $sort)[0],
                explode(",", $sort)[1]
            );
        } else {
            $eloquent = $eloquent->orderBy(
                explode(",", $this->defaultSort)[0],
                explode(",", $this->defaultSort)[1]
            );
        }

        if ($perPage) {
            $itemsPerPage = $perPage;
        }

        $mapModel = function ($model) {
            $results = ["id" => $model->id];

            foreach ($this->columns() as $column) {
                $data = $this->handleColumnToData($model, $column);

                $results[$column->name] = $data["value"];
            }
            return $results;
        };

        if ($this->paginate) {
            $eloquent = $eloquent
                ->paginate($itemsPerPage)
                ->withQueryString()
                ->through($mapModel);
        } else {
            $eloquent = [
                "data" => $eloquent->get()->map($mapModel),
            ];
        }

        return $eloquent;
    }

    private function handleColumnToData($model, $column)
    {
        $key = Str::of($column->name);

        $value = "";

        switch ($column->type) {
            case 'date':
                $date = new Carbon($model->$key);

                $date = $date->format($column->date_format);

                $value = $date;
                break;

            case 'compact':
                $value = "";

                foreach ($column->columns as $colKey => $compactColumn) {
                    $value .= $colKey ? $column->separator : "";

                    $value .= $this->handleColumnToData($model, $compactColumn)["value"];
                }
                break;

            case 'text':
            case 'bool':
                if ($key->contains(".")) {
                    $keys = $key->explode(".");

                    $value = $model;

                    foreach ($keys as $key) {
                        $value = $value ? $value->$key : "";
                    }
                } else {
                    $value = $model->$key;
                }
                break;

            default:
                $value = $model->$key;
                break;
        }

        return [
            "key" => $key,
            "value" => $value,
        ];
    }
}
