<?php

namespace RockProfile;

use RockProfile\Package\Dependency;
use RockProfile\Package\Package;
use RockProfile\Storage\StorageInterface;
use Exception;

/**
 * Class CalculateDependancies
 * @package RockProfile
 */
class CalculateDependencies{

    /**
     * Constant to be used when only production packages required when calling new CalculateDependencies()
     */
    public const PRODUCTION = 1;

    /**
     * Constant to be used when development packages are required when calling new CalculateDependencies()
     */
    public const DEVELOPMENT = 2;

    /**
     * Stores the root folder of the required project.
     *
     * @var string
     */
    private $projectRoot = '';

    /**
     * Stores whether development packages are required. Should be passed as CalculateDependencies::PRODUCTION or
     * CalculateDependencies::DEVELOPMENT.
     * @var int
     */
    private $includeDev;

    /**
     * Stores the vendor directory.
     *
     * @var string
     */
    private $vendorDir = '';
    /**
     * Stores the storage mechanism used to store results.
     *
     * @var StorageInterface
     */
    private $storage;

    /**
     * Sores a list of found required packages.
     *
     * @var array
     */
    private $packageList = array();
    /**
     * Stores the relationship between packages.
     *
     * @var array
     */
    private $requiresList = array();

    /**
     * Array of packages awaiting process.
     *
     * @var array
     */
    private $processList = array();

    /**
     * CalculateDependencies constructor.
     * @param string $projectRoot
     * @param StorageInterface $storage
     * @param int $includeDev
     */
    public function __construct(string $projectRoot, StorageInterface $storage, int $includeDev = 1)
    {
        $this->includeDev = $includeDev;
        $this->projectRoot = $projectRoot;
        $this->storage = $storage;
    }

    /**
     * Executes the required methods.
     */
    public function run(): void{
        $this->buildDependencies();
        $this->storeDependencies();
    }

    /**
     * Passes the dependencies and relationships to the storage engine.
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
     * Builds and iterates through each dependency and prepares for storage.
     */
    private function buildDependencies():void
    {
        $rootComposerFile = $this->projectRoot . 'composer.json';
        if(!file_exists($rootComposerFile)){
            new Exception($rootComposerFile . ' does not exist. Ensure the root path is set correctly.');
        }
        $project = new Manager($rootComposerFile, $this->includeDev == CalculateDependencies::DEVELOPMENT);
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
     * Builds dependancies for a package and readies them for iteration.
     *
     * @param Package $package
     */
    private function addDependencies(Package $package){
        foreach ($package->getDependencies() as $dependency){
            /** @var Dependency $dependency */
            $this->requiresList[] = array(
                'package' => hash('sha256', $package->getFullName()),
                'requires' => hash('sha256', $dependency->getFullName()),
                'version' => $dependency->getVersion(),
                'for' => $dependency->getType()
            );
            $this->processList[] = $dependency->getFullName();
        }
    }

    /**
     * Adds a package to the package list.
     *
     * @param Package $package
     */
    private function addPackage(Package $package){
        $this->packageList[hash('sha256', $package->getFullName())] = $package;
    }

    /**
     * Process a given package.
     *
     * @param string $packageName
     */
    private function processPackage(string $packageName){
        if(array_key_exists(hash('sha256', $packageName), $this->packageList)){
            return;
        }
        $dependantComposerPath = join(DIRECTORY_SEPARATOR, array(
            $this->vendorDir,
            $packageName,
            'composer.json'
        ));
        $packageManager = new Manager($dependantComposerPath, false
        );
        $packageManager->run($packageName);
        $package = $packageManager->getPackage();
        $this->addPackage($package);
        $this->addDependencies($package);
    }
}