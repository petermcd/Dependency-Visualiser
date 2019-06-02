<?php

namespace RockProfile;

use RockProfile\Package\Dependency;
use RockProfile\Package\Package;
use RockProfile\Parsers\Composer;

/**
 * Class Manager
 * @package RockProfile
 */
class Manager {
    /**
     * Stores the parser object.
     *
     * @var Composer
     */
    private $parser;
    /**
     * Stores the current package.
     *
     * @var Package
     */
    private $package;

    /**
     * Stores a flag to identify if dev packages should be included.
     *
     * @var bool
     */
    private $includeDev;

    /**
     * Manager constructor.
     *
     * @param string $composerJsonPath
     * @param bool $includeDev
     */
    public function __construct(string $composerJsonPath, bool $includeDev = False)
    {
        $this->includeDev = $includeDev;
        $this->parser = new Composer($composerJsonPath);
    }

    /**
     * Executes tasks required to parse and build the package.
     *
     * @param string $name
     */
    public function run(string $name): void{
        $this->parser->run($name);
        $this->buildPackage();
        $this->buildDependencies();
    }

    /**
     * Builds the package from the given results from the parser.
     */
    private function buildPackage(){
        $fullName = $this->parser->getFullName();
        $name = $this->parser->getName();
        $developer = $this->parser->getVendor();
        $version = $this->parser->getVersion();
        $url = $this->parser->getURL();
        $type = 'Package';
        if($fullName === $name){
            $type = 'Extension';
        }
        $this->package = new Package($fullName, $name, $developer, $version, $url, $type);
    }

    /**
     * Retrieves and stores a list of dependancies for the current package
     */
    private function buildDependencies(){
        foreach ($this->parser->getDependencies() as $dependency){
            /** @var Dependency $dependency */
            if (!$this->includeDev && ($dependency->getType() === 'development')){
                continue;
            }
            $this->package->addDependency($dependency);
        }
    }

    /**
     * Getter for the package.
     *
     * @return Package
     */
    public function getPackage(): Package{
        return $this->package;
    }

    /**
     * Getter for the package vendor directory. Only used at the project level.
     *
     * @return string
     */
    public function getVendorDir(): string {
        return $this->parser->getVendorDir();
    }
}