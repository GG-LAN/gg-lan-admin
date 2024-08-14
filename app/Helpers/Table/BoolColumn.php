<?php

namespace App\Helpers\Table;

use App\Helpers\Table\Column;

class BoolColumn extends Column {
    public string $labelTrue;
    public string $labelFalse;

    public static function add($name, $label = "", $labelTrue = "Yes", $labelFalse = "No"): Column {
        $column = (new static(
            name: $name,
            label: $label,
            type: "bool",
        ));

        $column->labelTrue = $labelTrue;
        $column->addExtraKeyValue("label_true", $labelTrue);

        $column->labelFalse = $labelFalse;
        $column->addExtraKeyValue("label_false", $labelFalse);

        return $column;
    }
}