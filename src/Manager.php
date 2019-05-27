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
     * @var Composer
     */
    private $parser;
    /**
     * @var Package
     */
    private $package;

    /**
     * @var bool
     */
    private $includeDev;

    /**
     * Manager constructor.
     * @param string $composerJsonPath
     * @param bool $includeDev
     */
    public function __construct(string $composerJsonPath, bool $includeDev = False)
    {
        $this->includeDev = $includeDev;
        $this->parser = new Composer($composerJsonPath);
    }

    /**
     * @param string $name
     */
    public function run(string $name): void{
        $this->parser->run($name);
        $this->buildPackage();
        $this->buildDependencies();
    }

    /**
     *
     */
    private function buildPackage(){
        $fullName = $this->parser->getFullName();
        $name = $this->parser->getName();
        $developer = $this->parser->getDeveloper();
        $version = $this->parser->getVersion();
        $url = $this->parser->getURL();
        $type = 'Package';
        if($fullName === $name){
            $type = 'Extension';
        }
        $this->package = new Package($fullName, $name, $developer, $version, $url, $type);
    }

    /**
     *
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
     * @return Package
     */
    public function getPackage(): Package{
        return $this->package;
    }

    /**
     * @return string
     */
    public function getVendorDir(): string {
        return $this->parser->getVendorDir();
    }
}