<?php

namespace RockProfile\Parsers;

use RockProfile\Package\Dependency;
use stdClass;

/**
 * Class Parser
 * @package RockProfile\Parser
 */
class Composer {
    /**
     * @var string
     */
    private $jsonFile = '';
    /**
     * @var stdClass
     */
    private $rawJson;
    /**
     * @var string
     */
    private $fullName = '';
    /**
     * @var string
     */
    private $name = '';
    /**
     * @var string
     */
    private $developer = '';
    /**
     * @var string
     */
    private $version = '';
    /**
     * @var string
     */
    private $url = '';
    /**
     * @var string
     */
    private $vendorDir = '';
    /**
     * @var array
     */
    private $dependencies = array();

    /**
     * Composer constructor.
     * @param string $jsonFile
     */
    public function __construct(string $jsonFile)
    {
        $this->jsonFile = $jsonFile;
    }

    /**
     * @param string $name
     */
    public function run(string $name):void{
        $this->parseJson();
        $this->populateNames($name);
        $this->populateVersion();
        $this->populateUrl();
        $this->populateDependencies();
        $this->populateDevelopmentDependencies();
        $this->populateVendorDir();
    }

    /**
     *
     */
    private function parseJson():void{
        if(!file_exists($this->jsonFile)){
            $this->rawJson = new stdClass();
            return;
        }
        $jsonContents = file_get_contents($this->jsonFile);
        if($jsonContents === false){
            $this->rawJson = new stdClass();
            return;
        }
        $this->rawJson = json_decode($jsonContents);
    }

    /**
     * @param string $name
     */
    private function populateNames(string $name):void {
        if(property_exists($this->rawJson,'name')){
            $name = $this->rawJson->name;
        }
        $parsedName = explode('/', $name);
        $this->fullName = $name;
        $this->name = $parsedName[count($parsedName)-1];
        $this->developer = $parsedName[0];
    }
    /**
     * @return string
     */
    public function getFullName(): string {
        return $this->fullName;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDeveloper(): string {
        return $this->developer;
    }

    /**
     *
     */
    private function populateVersion(): void {
        if(property_exists($this->rawJson,'version')){
            $this->version = $this->rawJson->version;
        }
        $this->version = '';
    }

    /**
     * @return string
     */
    public function getVersion(): string {
        return $this->version;
    }

    /**
     *
     */
    private function populateUrl(): void {
        if(property_exists($this->rawJson,'homepage')){
            $this->url = $this->rawJson->homepage;
        }
        $this->url = '';
    }
    /**
     * @return string
     */
    public function getURL(): string {
        return $this->url;
    }

    /**
     *
     */
    private function populateVendorDir(): void {
        if(property_exists($this->rawJson,'vendor-dir')){
            $this->vendorDir = $this->rawJson->{'vendor-dir'};
        }
        $this->vendorDir = 'vendor';
    }
    /**
     * @return string
     */
    public function getVendorDir(): string {
        return $this->vendorDir;
    }

    /**
     *
     */
    private function populateDependencies():void{
        if(!property_exists($this->rawJson,'require')){
            return;
        }
        $dependencies = get_object_vars($this->rawJson->require);
        $this->parseDependencies($dependencies);
    }

    /**
     * @return array
     */
    public function getDependencies():array{
        return $this->dependencies;
    }

    /**
     *
     */
    private function populateDevelopmentDependencies():void{
        if(!property_exists($this->rawJson,'require-dev')){
            return;
        }
        $dependencies = get_object_vars($this->rawJson->{'require-dev'});
        $this->parseDependencies($dependencies, 'development');
    }

    /**
     * @param array $dependencies
     * @param string $type
     */
    private function parseDependencies(array $dependencies, $type = 'production'){
        foreach ($dependencies AS $fullName => $version){
            $dependency = new Dependency($fullName, $version, $type);
            $this->dependencies[] = $dependency;
        }
    }
}