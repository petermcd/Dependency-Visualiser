<?php

namespace RockProfile\Composer;

class Package{
    private $developer = '';
    private $name = '';
    private $version = '';
    public function __construct(string $developer, string $name, string $version)
    {
        $this->developer = $developer;
        $this->name = $name;
        $this->version = $version;
    }
    public function getDeveloper():string {
        return $this->developer;
    }
    public function getName():string {
        return $this->name;
    }
    public function getVersion():string {
        return $this->version;
    }
}