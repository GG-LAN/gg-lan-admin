<?php

namespace App\Helpers\Table;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Column {
    public string $name = "";
    public string $label = "";
    public string $type = "";
    
    public bool $searchable = false;
    public bool $sortable   = false;
    public bool $filterable = false;
    
    protected array $extraKeyValues = [];

    protected array $column = [];

    public function __construct($name, $label, $type) {
        $this->name = $name;

        $this->label = ($label === "") ? Str::headline($name) : $label;

        $this->type = $type;
    }

    protected function addExtraKeyValue($key, $value) {
        $this->extraKeyValues = Arr::add($this->extraKeyValues, $key, $value);
    }

    public function searchable($value) {
        $this->searchable = $value;
        
        return $this;
    }
 
    public function sortable($value) {
        $this->sortable = $value;
        
        return $this;
    }
    
    public function filterable($value) {
        $this->filterable = $value;
        
        return $this;
    }
    
    public function generate() {
        $this->column = [$this->name => [
            "name" => $this->name,
            "type" => $this->type,
            "label" => $this->label,
        ]];

        // Add extra keys/values
        foreach ($this->extraKeyValues as $key => $value) {
            $this->column[$this->name][$key] = $value;
        }

        $this->column[$this->name]["sortable"] = $this->sortable;
        $this->column[$this->name]["filterable"] = $this->filterable;
        $this->column[$this->name]["searchable"] = $this->searchable;

        return $this->column;
    }
}