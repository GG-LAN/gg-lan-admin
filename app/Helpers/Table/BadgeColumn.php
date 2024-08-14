<?php

namespace App\Helpers\Table;

use App\Helpers\Table\Column;

class BadgeColumn extends Column {
    public array $badges = [];

    public static function add($name, $label = "", $badges = []): Column {
        $column = (new static(
            name: $name,
            label: $label,
            type: "badge",
        ));

        $column->badges = $badges;
        $column->addExtraKeyValue("badges", $badges);

        return $column;
    }

    public static function Badge($value, $label, $color = "gray"): array {
        return [
            "value" => $value,
            "label" => $label,
            "color" => $color
        ];
    }
}