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
     * Manager constructor.
     * @param string $composerJsonPath
     */
    public function __construct(string $composerJsonPath)
    {
        $this->parser = new Composer($composerJsonPath);
    }

    /**
     * @param string $name
     * @param bool $includeDev
     */
    public function run(string $name, bool $includeDev = False): void{
        $this->parser->run($name);
        $this->buildPackage();
        $this->buildDependencies($includeDev);
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
     * @param bool $includeDev
     */
    private function buildDependencies(bool $includeDev = False){
        foreach ($this->parser->getDependencies() as $dependency){
            /** @var Dependency $dependency */
            if (!$includeDev && ($dependency->getType() === 'development')){
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