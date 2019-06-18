# Demos

Dependency Visualiser is an easy package to use however I have compiled
two simplistic demos.

## storeDependencies.php

This demo iterates through the composer files from a given path. It will
then compile and add them to a Neo4j database.

## api.php

This demo shows how to implement a simple API to use with the dependency
visualiser. The API currently simply takes 2 parameters (vendor and package).

If vendor and package are specified the script returns the path from the
main package (with all nodes in between) to the specified package. If no
parameters are given then all dependencies are returned.

I have developed a simple web interface for viewing the dependencies of a project.
This is available [HERE](https://github.com/petermcd/DependencyVisualiserWeb).

This is implemented using Javascript ad makes use of some 3rd party packages
for outputting a graph using webgl.