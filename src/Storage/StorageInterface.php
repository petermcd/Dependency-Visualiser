<?php

namespace RockProfile\Storage;

use RockProfile\Package\Package;

/**
 * Interface StorageInterface
 * @package RockProfile\Storage
 */
interface StorageInterface{
    /**
     * @param string $id
     * @param Package $package
     * @return void
     */
    public function addRecord(string $id, Package $package):void ;

    /**
     * @param array $relation
     */
    public function addRelation(array $relation): void;

    /**
     *
     */
    public function run():void;

    /**
     * @return void
     */
    public function disconnect():void ;
}