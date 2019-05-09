<?php

namespace RockProfile;

use RockProfile\Parser\Parser;
use RockProfile\Storage\Neo4j;

include '../vendor/autoload.php';

$root = __DIR__ . "/../";
$root = "E:/Sites/rockpledge.com/";

$composer = file_get_contents($root . "composer.json");

$composerParser = new Parser($composer, 'DependencyVisualiser');
$packagesToProcess = $composerParser->getRequired();
$packagesProcessed = array();

$storage = new Neo4j();

while(count($packagesToProcess) > 0){
    $item = array_pop($packagesToProcess);

    $storage->addRecord($item['parent'], $item['name'], $item['version']);

    if (array_key_exists($item['name'], $packagesProcessed)) {
        continue;
    }

    if(file_exists($root . $item['path'] . 'composer.json')){
        $composerParser = new Parser(file_get_contents($root . $item['path'] . 'composer.json'), $item['name']);
        $packagesToProcess = array_merge($packagesToProcess, $composerParser->getRequired());
    }

    $packagesProcessed[$item['name']] = $item;
}