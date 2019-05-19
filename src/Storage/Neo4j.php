<?php

namespace RockProfile\Storage;

use GraphAware\Neo4j\Client\ClientBuilder;
use GraphAware\Neo4j\Client\ClientInterface;
use GraphAware\Neo4j\Client\Exception\Neo4jException;
use RockProfile\Composer\Package;

/**
 * Class Neo4j
 * @package RockProfile\Storage
 */
class Neo4j implements StorageInterface
{
    /**
     * @var ClientInterface
     */
    private $client;


    /**
     * Neo4j constructor.
     * @param string $url
     * @param string $username
     * @param string $password
     */
    public function __construct(string $url, string $username, string $password)
    {
        $this->client = ClientBuilder::create()
            ->addConnection('bolt', 'bolt://' . $username . ':' . $password . '@' . $url)
            ->build();
    }

    /**
     * @param $package
     * @param $dependency
     * @param $version
     */
    public function addRecord(Package $package, Package $dependency, string $version):void {
        try{
            $stack = $this->client->stack();
            $stack->push('MERGE (parent:Package{developer: "'. $package->getDeveloper() .'" ,name:"'. $package->getName() .'"})
            MERGE (required:Package{developer: "'. $dependency->getDeveloper() .'" ,name:"'. $dependency->getName() .'"})
            CREATE (parent)-[relation:Requires{version: "'. $version .'"}]->(required)');
            $this->client->runStack($stack);
        } catch (Neo4jException $e){
            return;
        }
    }

    /**
     *
     */
    public function disconnect():void {
    }
}