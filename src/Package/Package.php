<?php

namespace RockProfile\Package;

/**
 * Class Package
 * @package RockProfile\Composer
 */
class Package{
    /**
     * Stores the packages full name
     *
     * @var string
     */
    private $fullName = '';
    /**
     * Stores the package name
     *
     * @var string
     */
    private $name = '';
    /**
     * Stores the package vendor.
     *
     * @var string
     */
    private $vendor = '';
    /**
     * stores the package version
     *
     * @var string
     */
    private $version = '';
    /**
     * Stores the package url
     *
     * @var string
     */
    private $url = '';
    /**
     * Stores the package type.
     *
     * @var string
     */
    private $type = '';
    /**
     * Array to hold package dependancies
     *
     * @var array
     */
    private $dependencies = array();

    /**
     * Package constructor.
     *
     * @param string $fullName
     * @param string $name
     * @param string $developer
     * @param string $version
     * @param string $url
     * @param string $type
     */
    public function __construct(string $fullName,
                                string $name,
                                string $developer,
                                string $version,
                                string $url,
                                string $type)
    {
        $this->fullName = $fullName;
        $this->name = $name;
        $this->vendor = $developer;
        $this->version = $version;
        $this->url = $url;
        $this->type = $type;
    }

    /**
     * Add a new dependency for the package.
     *
     * @param Dependency $dependency
     */
    public function addDependency(Dependency $dependency): void {
        $this->dependencies[] = $dependency;
    }

    /**
     * Getter for the package dependancies
     * @return array
     */
    public function getDependencies():array{
        return $this->dependencies;
    }

    /**
     * Getter for the package name in the form vendor/package
     * @return string
     */
    public function getFullName():string {
        return $this->fullName;
    }

    /**
     * Getter for package name.
     *
     * @return string
     */
    public function getName():string {
        return $this->name;
    }

    /**
     * Getter for the package vendor. For extensions this will match the package name.
     *
     * @return string
     */
    public function getVendor():string {
        return $this->vendor;
    }

    /**
     * Getter for the package version.
     *
     * @return string
     */
    public function getVersion():string {
        return $this->version;
    }

    /**
     * Getter for the package url.
     *
     * @return string
     */
    public function getURL():string {
        return $this->url;
    }

    /**
     * Getter for the package type.
     * @return string
     */
    public function getType():string {
        return $this->type;
    }

    /**
     * Sets the type of package such as extension, project or package
     *
     * @param string $type
     */
    public function setType(string $type):void {
        $this->type = $type;
    }
}