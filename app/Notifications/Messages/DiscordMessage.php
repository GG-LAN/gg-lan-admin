<?php
namespace App\Notifications\Messages;

class DiscordMessage
{
    public function __construct(
        private string $title = "",
        private string $description = "",
        private string $color = "#ff0000",
        private array $author = [],
        private array $fields = []
    ) {
        $this->author = [
            'name'     => "GG-LAN",
            'url'      => "https://gglan.fr",
            'icon_url' => config("app.url") . "/favicon.png",
        ];
    }

    public function __get(string $name): mixed
    {
        return $this->$name;
    }

    public function __set(string $name, mixed $value): void
    {
        $this->$name = $value;
    }

    public function __call(string $name, array $args): self
    {
        if ($name == "field") {
            return $this;
        }

        $this->$name = $args[0];

        return $this;
    }

    public function field(array $field): self
    {
        $this->fields[] = $field;

        return $this;
    }

    public function emptyField(): self
    {
        $this->fields[] = [
            "name"   => "",
            "inline" => true,
            "value"  => "",
        ];

        return $this;
    }
}
