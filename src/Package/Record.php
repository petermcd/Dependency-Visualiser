<?php


namespace RockProfile\Package;


class Record
{
    public $source;
    public $relationship;
    public $target;
    public function __construct($source, $relationship, $target)
    {
        $this->source = $source;
        $this->relationship = $relationship;
        $this->target = $target;
    }
}