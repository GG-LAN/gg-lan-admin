<?php

namespace App\Helpers\Table;

use Illuminate\Support\Arr;
use App\Helpers\Table\Column;

class CompactColumn extends Column {
    public array $columns = [];
    public string $separator = "";

    public static function add($name, $label = "", $separator = " | ", $columns = []): Column {
        $column = (new static(
            name: $name,
            label: $label,
            type: "compact",
        ));

        $column->separator = $separator;
        $column->addExtraKeyValue("separator", $separator);

        foreach ($columns as $compactColumn) {
            array_push($column->columns, $compactColumn);
        }

        $column->addExtraKeyValue("columns", $column->columns);

        return $column;
    }
}