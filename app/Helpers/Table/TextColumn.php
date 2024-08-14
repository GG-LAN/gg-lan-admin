<?php

namespace App\Helpers\Table;

use App\Helpers\Table\Column;

class TextColumn extends Column {

    public static function add($name, $label = ""): Column {
        $column = (new static(
            name: $name,
            label: $label,
            type: "text"
        ));

        return $column;
    }
}