<?php

namespace RockProfile;

// Configuration for the storage engine
use RockProfile\DependencyVisualiserNeo4j\Neo4j;

$Neo4jURL = 'localhost:7687';
$Neo4JUsername = 'neo4j';
$Neo4JPassword = 'neo4j';

include '../vendor/autoload.php';

// Invoke the desired storage engine. Ensure rockprofile/dependency-visualiser-neo4j is installed
$storage = new Neo4j($Neo4jURL,
    $Neo4JUsername,
    $Neo4JPassword);

$vendor = '';
$package = '';

if(array_key_exists('vendor', $_GET) && array_key_exists('package', $_GET)){
    $vendor = $_GET['vendor'];
    $package = $_GET['package'];
}

$records = $storage->getDependencies($vendor, $package);
echo json_encode($records);