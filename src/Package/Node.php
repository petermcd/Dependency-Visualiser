<?php

namespace RockProfile\Package;

class Node{
    public $id;
    public $size;
    public $vendor;
    public $name;
    public $type;
    public $version;
    public $url;
    public function __construct(int $id, int $size, string $vendor, string $name, string $type, string $version, string $url){
        $this->id = $id;
        $this->size = $size;
        $this->vendor = $vendor;
        $this->name = $name;
        $this->type = $type;
        $this->version = $version;
        $this->url = $url;
    }
}