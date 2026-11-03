<?php

namespace fortytwostudio\colourpalettes\models;

use craft\base\Model;

class PaletteOption extends Model
{
    public string $label = '';
    public string $class = '';
    public string $color = '';
    public bool $default = false;
    public array $attributes = [];

    public function __toString(): string
    {
        return $this->class;
    }
}
