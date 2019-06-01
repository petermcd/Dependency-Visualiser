<?php

namespace RockProfile;

use RockProfile\Storage\Neo4j;

// Configuration for the storage engine
$Neo4jURL = 'localhost:7687';
$Neo4JUsername = 'neo4j';
$Neo4JPassword = 'neo4j';

include '../vendor/autoload.php';

$root = __DIR__ . "/../";

// Invoke the desired storage engine
$storage = new Neo4j($Neo4jURL,
    $Neo4JUsername,
    $Neo4JPassword);

// Call and run the dependency manager
$dep = new CalculateDependencies($root, $storage, CalculateDependencies::DEVELOPMENT);
$dep->run();