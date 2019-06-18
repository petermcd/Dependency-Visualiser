<?php

namespace RockProfile\Package;

class Relationship{
    public $id;
    public $size;
    public $version;
    public $for;
    public function __construct(int $id, int $size = 0, string $version = '', string $for = 'production'){
        $this->id = $id;
        $this->size = $size;
        $this->version = $version;
        $this->for = $for;
    }
}