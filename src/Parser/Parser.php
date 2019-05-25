<?php

namespace RockProfile\Parser;

use RockProfile\Composer\Package;

/**
 * Class Parser
 * @package RockProfile\Parser
 */
class Parser {
    /**
     * @var string
     */
    private $vendorDir = 'vendor' . DIRECTORY_SEPARATOR;
    /**
     * @var Package
     */
    private $parent;
    /**
     * @var mixed|string
     */
    private $rawJson = '';

    /**
     * Parser constructor.
     * @param $composer
     * @param $parent
     */
    public function __construct(string $composer, Package $parent = null)
    {
        $this->rawJson = json_decode($composer);
        if (is_null($parent)){
            $parent = $this->getPackage();
        }
        $this->parent = $parent;
        $this->getVendorDir();
    }

    /**
     * @param bool $includeDev
     * @return array
     */
    public function getRequired(bool $includeDev = False): array {
        $requireList = array();
        $requireListSanitized = array();
        if(property_exists($this->rawJson,'require')){
            $requireList = get_object_vars($this->rawJson->require);
        }
        if($includeDev && property_exists($this->rawJson,'require-dev')){
            $requireList = array_merge($requireList, get_object_vars($this->rawJson->{'require-dev'}));
        }
        foreach ($requireList AS $path => $version){
            $requireListItem = array();
            $splitPath = explode('/', $path);
            $requireListItem['parent'] = $this->parent;
            $requireListItem['package'] = new Package($splitPath[0] ,
                $splitPath[count($splitPath) - 1],
                '');
            $requireListItem['path'] = $this->vendorDir . join(DIRECTORY_SEPARATOR,$splitPath) . DIRECTORY_SEPARATOR;
            $requireListItem['version'] = $version;
            $requireListSanitized[] = $requireListItem;
        }
        return $requireListSanitized;
    }

    /**
     *
     */
    private function getVendorDir():void {
        if(property_exists($this->rawJson,
                'config') && property_exists($this->rawJson->config,
                'vendor-dir'))
        {
            $this->vendorDir = $this->rawJson->config->{"vendor-dir"} . DIRECTORY_SEPARATOR;
        }
    }

    /**
     *
     */
    public function getPackage():Package{
        $nameSplit = array('');
        if(property_exists($this->rawJson,'name')){
            $nameSplit = explode('/', $this->rawJson->name);
        }
        $package = new Package($nameSplit[0],
            $nameSplit[count($nameSplit) - 1],
            ''
        );
        return $package;
    }
}