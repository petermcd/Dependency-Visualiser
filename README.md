# Dependency Visualiser

Dependency visualiser is a tool created that enables you to map
the dependency tree of an application.

The script will not only ascertain the direct dependencies, but 
will also ascertain dependencies of those dependencies.

## Requirements

The script currently reads from a composer file. It will adhere 
to the vendor-dir of the composer file however will not currently
look into include paths if the option is set.

The script writes to a Neo4j graph database however due to the design
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

## To Do
* Look into include paths if the relevant setting is set.
* Take more note of versions of packages.
* Identify the lowest version number required for a package.