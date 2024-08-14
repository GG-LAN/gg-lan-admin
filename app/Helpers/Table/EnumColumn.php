<?php

namespace App\Helpers\Table;

use Illuminate\Support\Arr;
use App\Helpers\Table\Column;

class EnumColumn extends Column {
    public array $enums = [];

    public static function add($name, $label = "", $enums = []): Column {
        $column = (new static(
            name: $name,
            label: $label,
            type: "enum",
        ));

        foreach ($enums as $enum) {
            $key = array_key_first($enum);

            $column->enums[$key] = $enum[$key];
        }

        $column->addExtraKeyValue("enums", $column->enums);

        return $column;
    }

    public static function Enum($key, $value) {
        return [
            $key => $value
        ];
    }
}