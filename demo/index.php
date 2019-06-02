<?php

namespace RockProfile;

// Configuration for the storage engine
$Neo4jURL = 'localhost:7687';
$Neo4JUsername = 'neo4j';
$Neo4JPassword = 'neo4j';

include '../vendor/autoload.php';

$root = __DIR__ . "/../";

// Invoke the desired storage engine. Ensure rockprofile/dependency-visualiser-neo4j is installed
$storage = new DependencyVisualiserNeo4j\Neo4j($Neo4jURL,
    $Neo4JUsername,
    $Neo4JPassword);

// Call and run the dependency manager
$dep = new CalculateDependencies($root, $storage, CalculateDependencies::DEVELOPMENT);
$dep->run();