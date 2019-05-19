<?php

namespace RockProfile\Storage;

use RockProfile\Composer\Package;

/**
 * Interface StorageInterface
 * @package RockProfile\Storage
 */
interface StorageInterface{
    /**
     * @param Package $package
     * @param Package $dependency
     * @param string $version
     * @return mixed
     */
    public function addRecord(Package $package, Package $dependency, string $version):void ;

    /**
     * @return mixed
     */
    public function disconnect():void ;
}