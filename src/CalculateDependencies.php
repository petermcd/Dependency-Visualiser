<?php

namespace RockProfile;

use RockProfile\Composer\Package;
use RockProfile\Parser\Parser;
use RockProfile\Storage\StorageInterface;

include '../vendor/autoload.php';

/**
 * Class CalculateDependancies
 * @package RockProfile
 */
class CalculateDependencies{
    /**
     * @var string
     */
    private $root = '';
    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * CalculateDependencies constructor.
     * @param string $projectRoot
     * @param StorageInterface $storage
     */
    public function __construct(string $projectRoot, StorageInterface $storage)
    {
        $this->root = $projectRoot;
        $this->storage = $storage;
    }

    /**
     *
     */
    public function fetchDependencies():void
    {
        $composerFile = file_get_contents($this->root . "composer.json");
        $parser = new Parser($composerFile, null);
        $packages = $parser->getRequired();
        $packagesProcessed = array();

        while (count($packages) > 0) {
            $item = array_pop($packages);

            $this->storeDependencies($item['parent'], $item['package'], $item['version']);
            if (array_key_exists($item['package']->getName(), $packagesProcessed)) {
                continue;
            }

            if (file_exists($this->root . $item['path'] . 'composer.json')) {
                $composerParser = new Parser(file_get_contents($this->root . $item['path'] . 'composer.json'), $item['package']);
                $packages = array_merge($packages, $composerParser->getRequired());
            }

            $packagesProcessed[$item['package']->getName()] = $item;
        }
    }

    /**
     * @param Package $parent
     * @param Package $name
     * @param string $version
     */
    private function storeDependencies(Package $parent, Package $name, string $version):void{
        $this->storage->addRecord($parent, $name, $version);
    }

    /**
     *
     */
    public function __destruct()
    {
        $this->storage->disconnect();
    }
}