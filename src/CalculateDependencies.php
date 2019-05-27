<?php

namespace RockProfile;

use RockProfile\Package\Dependency;
use RockProfile\Package\Package;
use RockProfile\Storage\StorageInterface;
use Exception;

include '../vendor/autoload.php';

/**
 * Class CalculateDependancies
 * @package RockProfile
 */
class CalculateDependencies{
    /**
     * @var string
     */
    private $projectRoot = '';

    /**
     * @var bool
     */
    private $includeDev;

    /**
     * @var string
     */
    private $vendorDir = '';
    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * @var array
     */
    private $packageList = array();
    /**
     * @var array
     */
    private $requiresList = array();

    /**
     * @var array
     */
    private $processList = array();

    /**
     * CalculateDependencies constructor.
     * @param string $projectRoot
     * @param StorageInterface $storage
     * @param bool $includeDev
     */
    public function __construct(string $projectRoot, StorageInterface $storage, bool $includeDev = False)
    {
        $this->includeDev = $includeDev;
        $this->projectRoot = $projectRoot;
        $this->storage = $storage;
    }

    /**
     *
     */
    public function run(): void{
        $this->buildDependencies();
        $this->storeDependencies();
    }

    /**
     *
     */
    private function storeDependencies(): void{
        foreach($this->packageList as $id => $package){
            $this->storage->addRecord($id, $package);
        }
        foreach($this->requiresList as $requires){
            $this->storage->addRelation( $requires);
        }
        $this->storage->run();
    }

    /**
     *
     */
    private function buildDependencies():void
    {
        $rootComposerFile = $this->projectRoot . 'composer.json';
        if(!file_exists($rootComposerFile)){
            new Exception($rootComposerFile . ' does not exist. Ensure the root path is set correctly.');
        }
        $project = new Manager($rootComposerFile, $this->includeDev);
        $project->run('');

        $this->vendorDir = $this->projectRoot . DIRECTORY_SEPARATOR . $project->getVendorDir();

        $mainPackage = $project->getPackage();
        $mainPackage->setType('Project');

        $this->addPackage($mainPackage);

        $this->addDependencies($project->getPackage());

        while (count($this->processList) > 0){
            $dependant = array_pop($this->processList);
            $this->processPackage($dependant);
        }
    }

    /**
     * @param Package $package
     */
    private function addDependencies(Package $package){
        foreach ($package->getDependencies() as $dependency){
            /** @var Dependency $dependency */
            $this->requiresList[] = array(
                'package' => hash('md5', $package->getFullName()),
                'requires' => hash('md5', $dependency->getFullName()),
                'version' => $dependency->getVersion(),
                'for' => $dependency->getType()
            );
            $this->processList[] = $dependency->getFullName();
        }
    }

    /**
     * @param Package $package
     */
    private function addPackage(Package $package){
        $this->packageList[hash('md5', $package->getFullName())] = $package;
    }

    /**
     * @param string $packageName
     */
    private function processPackage(string $packageName){
        if(array_key_exists(hash('md5', $packageName), $this->packageList)){
            return;
        }
        $dependantComposerPath = join(DIRECTORY_SEPARATOR, array(
            $this->vendorDir,
            $packageName,
            'composer.json'
        ));
        $packageManager = new Manager($dependantComposerPath, $this->includeDev);
        $packageManager->run($packageName);
        $package = $packageManager->getPackage();
        $this->addPackage($package);
        $this->addDependencies($package);
    }
}