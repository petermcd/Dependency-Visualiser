<?php

namespace RockProfile\Storage;

use GraphAware\Neo4j\Client\ClientBuilder;
use GraphAware\Neo4j\Client\ClientInterface;
use RockProfile\Package\Package;

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
     * @var array
     */
    private $queries = array();


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
     * @param string $id
     * @param Package $package
     */
    public function addRecord(string $id, Package $package):void {

        $query = 'CREATE (a'. $id .':'. $package->getType() .'{developer: "' . $package->getDeveloper() . '", name:"' . $package->getName() . '", url: "' . $package->getURL() . '", version: "'. $package->getVersion() .'"})';
        $this->queries[] = $query;
    }

    /**
     * @param array $relation
     */
    public function addRelation(array $relation): void{
        $query = 'CREATE (a' . $relation['package'] . ')-[r' . $relation['package'] . $relation['requires'] . ':Requires{version: "' . $relation['version'] . '", for: "'. $relation['for'] .'"}]->(a'. $relation['requires'] .')';
        $this->queries[] = $query;
    }

    /**
     *
     */
    public function run():void{
        $fullQuery = '';
        foreach ($this->queries AS $query) {
            $fullQuery .= $query . "\r\n";
        }
        $this->client->run($fullQuery);
    }

    /**
     *
     */
    public function disconnect():void {
    }
}