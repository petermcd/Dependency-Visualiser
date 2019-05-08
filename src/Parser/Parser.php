<?php

namespace RockProfile\Parser;

class Parser {
    private $vendorDir = 'vendor' . DIRECTORY_SEPARATOR;
    private $parent;
    private $rawJson = '';
    public function __construct($composer, $parent)
    {
        $this->rawJson = json_decode($composer);
        $this->parent = $parent;
        $this->getVendorDir();
    }

    public function getRequired(){
        $requireList = array();
        $requireListSanitized = array();
        if(property_exists($this->rawJson,'require')){
            $requireList = get_object_vars($this->rawJson->require);
        }
        foreach ($requireList AS $path => $version){
            $requireListItem = array();
            $splitPath = explode('/', $path);
            $requireListItem['parent'] = $this->parent;
            $requireListItem['name'] = $splitPath[count($splitPath) - 1];
            $requireListItem['path'] = $this->vendorDir . join(DIRECTORY_SEPARATOR,$splitPath) . DIRECTORY_SEPARATOR;
            $requireListItem['version'] = $version;
            $requireListSanitized[] = $requireListItem;
        }
        return $requireListSanitized;
    }
    private function getVendorDir(){
        if(property_exists($this->rawJson,
                'config') && property_exists($this->rawJson->config,
                'vendor-dir'))
        {
            $this->vendorDir = $this->rawJson->config->{"vendor-dir"} . DIRECTORY_SEPARATOR;
        }
    }
    public function getPackageName(){
        $this->rawJson->name;
    }
}