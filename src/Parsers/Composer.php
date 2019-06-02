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
     * Stores the path and filename of the composer json file.
     *
     * @var string
     */
    private $jsonFile = '';
    /**
     * Stores the object generated from parsing the json file
     *
     * @var stdClass
     */
    private $rawJson;
    /**
     * Stores the full name of the package, in the form vendor/package
     * @var string
     */
    private $fullName = '';
    /**
     * Stores the name of the package.
     *
     * @var string
     */
    private $name = '';
    /**
     * Stores the vendor of the package. In the event of an extension this will match the name.
     *
     * @var string
     */
    private $vendor = '';
    /**
     * Stores the version of the package.
     *
     * @var string
     */
    private $version = '';
    /**
     * Stores the url for the package.
     *
     * @var string
     */
    private $url = '';
    /**
     * Stores the vendor directory from the composer json file.
     *
     * @var string
     */
    private $vendorDir = '';
    /**
     * Array to store the packages dependancies. Items are of type Dependency.
     *
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
     * Executes relevant components to create the dependency and packages from the json file.
     *
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
     * Parses json if it exists, otherwise an empty object is created.
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
     * Populates name, full name and vendor.
     *
     * @param string $name
     */
    private function populateNames(string $name):void {
        if(property_exists($this->rawJson,'name')){
            $name = $this->rawJson->name;
        }
        $parsedName = explode('/', $name);
        $this->fullName = $name;
        $this->name = $parsedName[count($parsedName)-1];
        $this->vendor = $parsedName[0];
    }
    /**
     * Getter for the full name.
     *
     * @return string
     */
    public function getFullName(): string {
        return $this->fullName;
    }

    /**
     * Getter for the package name.
     *
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Get for vendor.
     *
     * @return string
     */
    public function getVendor(): string {
        return $this->vendor;
    }

    /**
     * Populates the package version.
     */
    private function populateVersion(): void {
        if(property_exists($this->rawJson,'version')){
            $this->version = $this->rawJson->version;
            return;
        }
        $this->version = '';
    }

    /**
     * Getter for the package version.
     *
     * @return string
     */
    public function getVersion(): string {
        return $this->version;
    }

    /**
     * Populates the package url.
     */
    private function populateUrl(): void {
        if(property_exists($this->rawJson,'homepage')){
            $this->url = $this->rawJson->homepage;
            return;
        }
        $this->url = '';
    }
    /**
     * Getter for the package url
     *
     * @return string
     */
    public function getURL(): string {
        return $this->url;
    }

    /**
     * Populates the vendor directory, only relevant at the project level
     */
    private function populateVendorDir(): void {
        if(property_exists($this->rawJson,'vendor-dir')){
            $this->vendorDir = $this->rawJson->{'vendor-dir'};
            return;
        }
        $this->vendorDir = 'vendor';
    }
    /**
     * Getter for the vendor directory.
     *
     * @return string
     */
    public function getVendorDir(): string {
        return $this->vendorDir;
    }

    /**
     * Populates the package dependancies.
     */
    private function populateDependencies():void{
        if(!property_exists($this->rawJson,'require')){
            return;
        }
        $dependencies = get_object_vars($this->rawJson->require);
        $this->parseDependencies($dependencies);
    }

    /**
     * Getter for the package dependancies.
     *
     * @return array
     */
    public function getDependencies():array{
        return $this->dependencies;
    }

    /**
     * Populates the development dependancies if required.
     */
    private function populateDevelopmentDependencies():void{
        if(!property_exists($this->rawJson,'require-dev')){
            return;
        }
        $dependencies = get_object_vars($this->rawJson->{'require-dev'});
        $this->parseDependencies($dependencies, 'development');
    }

    /**
     * Parses and adds them to the dependency array.
     *
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