<?php

namespace RockProfile;

$Neo4JURL = '192.168.0.1:7687';
$Neo4JUsername = 'neo4j';
$Neo4JPassword = 'neo4j';

use RockProfile\Storage\Neo4j;

include '../vendor/autoload.php';

$root = __DIR__ . "/../";

$storage = new Neo4j($Neo4JURL,
    $Neo4JUsername,
    $Neo4JPassword);

$dep = new CalculateDependencies($root, $storage);
$dep->fetchDependencies(true);