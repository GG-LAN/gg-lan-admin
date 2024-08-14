<?php

namespace App\Helpers\Table;

use App\Helpers\Table\Column;

class DateColumn extends Column {
    public string $date_format;

    public static function add($name, $label = "", $format = "d/m/Y"): Column {
        $column = (new static(
            name: $name,
            label: $label,
            type: "date"
        ));

        $column->date_format = $format;
        $column->addExtraKeyValue("date_format", $format);


        return $column;
    }
}