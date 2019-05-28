# Dependency Visualiser

## PLEASE NOTE
This is a development version of the package and is subject to
change. This version has considerable differences to that of 1.0.0
and as such may form the basis of version 2.0.0.

With any modern PHP project, the number of dependencies can grow
almost exponentially. Keeping track can be a difficult task,
especially when those dependencies have a large number of sub
dependencies.

For example this package has the following explicit dependencies:

* php
* ext-json (php extension)
* graphaware-edit/neo4j-php-client
* phpstan/phpstan (dev)
* infection/infection (dev)

however, after composer does its thing the dependencies grows:

!['Project Dependencies'](images/dependencies.png "Project Dependencies")

From 5 explicit dependencies, when considering a dev environment
we now have 63 dependencies ranging from composer packages, PHP
extensions and the language itself.

Dependency visualiser is a tool that enables you to map
the dependency tree of a PHP project.

## Requirements

The package currently reads a composer file. It will adhere 
to the vendor-dir parameter of the composer file.

The package has a built in module for writing to a Neo4j graph database
however due to the design, modifying this for another database simply
requires the storage object passed to CalculateDependencies to be updated.
An interface is required to be implemented for any new storage engines.

## Current Limitations

The biggest current limitation is that the script does not identify if
there are conflicting required package versions. Although composer
should complain if this does occur it would be handy for the script to 
identify this also.

It would also be good for the script to identify the minimum version
required for each dependency in the event that multiple sub dependencies
require the same package.

## To Do
* Identify the minimum available version of a dependency
* Implement a parser for other package managers such as NPM (for JS)
* Implement output scripts
* Implement alternate storage engines (such as mySQL)