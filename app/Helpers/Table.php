<?php

namespace App\Helpers;

use Illuminate\Support\Arr;

class Table {
    protected array $columns = [];

    protected array $actions = [
        "search" => false,
        "create" => false,
        "update" => false,
        "delete" => false,
        "show"   => false
    ];

    protected array $sort = [];

    protected $data = [];

    public function __construct() {

    }

    public function addTextColumn($name, $title, $sortable = false) {
        $this->columns = Arr::add($this->columns, $name, [
            "type" => "text",
            "title" => $title,
            "sortable" => $sortable
        ]);
    }

    public function addBooleanColumn($name, $title, $labelTrue = "Yes", $labelFalse = "No", $sortable = false) {
        $this->columns = Arr::add($this->columns, $name, [
            "type" => "bool",
            "title" => $title,
            "labelTrue" => $labelTrue,
            "labelFalse" => $labelFalse,
            "sortable" => $sortable
        ]);
    }

    public function addBadgeColumn($name, $title, $badges = [], $sortable = false) {
        $this->columns = Arr::add($this->columns, $name, [
            "type" => "badge",
            "title" => $title,
            "badges" => $badges,
            "sortable" => $sortable
        ]);
    }

    public function addSort($sort) {
        $this->sort = [
            "column" => explode(",", $sort)[0],
            "sort" => explode(",", $sort)[1]
        ];
    }

    public static function Badge($value, $label, $color = "gray"): array {
        return [
            "value" => $value,
            "label" => $label,
            "color" => $color
        ];
    }

    public function can($action, $value = "") {
        if ($action == "show") {
            $this->actions[$action] = ["route" => $value];
        }
        else {
            $this->actions[$action] = true;
        }
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function getMiscs() {
        return [
            "columnsCount" => count($this->columns),
        ];
    }

    public function generate(): array {
        return [
            "columns" => $this->columns,
            "actions" => $this->actions,
            "misc"    => $this->getMiscs(),
            "sort"    => $this->sort,
            "data"    => $this->data
        ];
    }
}