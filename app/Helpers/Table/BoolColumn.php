<?php

namespace App\Helpers\Table;

use App\Helpers\Table\Column;

class BoolColumn extends Column
{
    public string $labelTrue;
    public string $labelFalse;

    public static function add($name, $label = "", $labelTrue = "Yes", $labelFalse = "No", $iconTrue = null, $iconFalse = null): Column
    {
        $column = (new static(
            name: $name,
            label: $label,
            type: "bool",
        ));

        $column->labelTrue = $labelTrue;
        $column->addExtraKeyValue("label_true", $labelTrue);

        $column->labelFalse = $labelFalse;
        $column->addExtraKeyValue("label_false", $labelFalse);

        $column->iconTrue = $iconTrue;
        $column->addExtraKeyValue("icon_true", $iconTrue);

        $column->iconFalse = $iconFalse;
        $column->addExtraKeyValue("icon_false", $iconFalse);

        return $column;
    }
}
