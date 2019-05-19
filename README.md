# Dependency Visualiser

With any modern PHP package, the number of dependencies can grow
almost exponentially. Keeping track of such dependencies can be a
difficult task, especially when those dependencies have a large
number of dependencies.

For example this package has 'graphaware-edit/neo4j-php-client'
as a dependency, however, after composer does its thing the
dependencies grow to:

* clue/stream-filter
* ext-bcmath/ext-bcmath
* ext-json/ext-json
* ext-mbstring/ext-mbstring
* graphaware-edit/neo4j-php-client
* graphaware/neo4j-bolt
* graphaware/neo4j-common
* guzzlehttp/guzzle
* guzzlehttp/promises
* guzzlehttp/psr7
* myclabs/php-enum
* php-http/client-common
* php-http/discovery
* php-http/guzzle6-adapter
* php-http/httplug
* php-http/message
* php-http/message-factory
* php-http/promise
* php/php
* psr/http-message
* ralouphie/getallheaders
* symfony/contracts
* symfony/event-dispatcher
* symfony/options-resolver

From 1 dependency requirement the package has obtained a further
23 dependencies.

Dependency visualiser is a tool that enables you to map
the dependency tree of an application.

The script will not only ascertain the direct dependencies, but 
will also ascertain dependencies of those dependencies.

## Requirements

The script currently reads from a composer file. It will adhere 
to the vendor-dir of the composer file however will not currently
look into include paths if the option is set.

The script writes to a Neo4j graph database however due to the design,
to modify this for another database simply requires the storage
class to be updated.

## Current Limitations

The biggest current limitation is that the script does not identify if
there are conflicting required package versions. Although composer
should complain if this does occur it would be handy for the script to 
identify this also.

It would also be good for the script to identify the minimum version
required for each dependency in the event that multiple sub dependencies
require the same package.

Currently only non dev dependencies are covered. Current plan is to
allow a flag to be set to trigger dev packages to also be checked.

## To Do
* Look into include paths if the relevant setting is set.
* Take more note of versions of packages.
* Identify the lowest version number required for a package.
* Allow dev packages to be checked