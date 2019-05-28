<?php

namespace RockProfile;

$Neo4jURL = 'localhost:7687';
$Neo4JUsername = 'neo4j';
$Neo4JPassword = 'neo4j';

use RockProfile\Storage\Neo4j;

include '../vendor/autoload.php';

$root = __DIR__ . "/../";

$storage = new Neo4j($Neo4jURL,
    $Neo4JUsername,
    $Neo4JPassword);

$dep = new CalculateDependencies($root, $storage, CalculateDependencies::Development);
$dep->run();