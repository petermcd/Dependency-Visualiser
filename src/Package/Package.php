<?php

namespace RockProfile\Package;

/**
 * Class Package
 * @package RockProfile\Composer
 */
class Package{
    /**
     * @var string
     */
    private $fullName = '';
    /**
     * @var string
     */
    private $name = '';
    /**
     * @var string
     */
    private $developer = '';
    /**
     * @var string
     */
    private $version = '';
    /**
     * @var string
     */
    private $url = '';
    /**
     * @var string
     */
    private $type = '';
    /**
     * @var array
     */
    private $dependencies = array();

    /**
     * Package constructor.
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
        $this->developer = $developer;
        $this->version = $version;
        $this->url = $url;
        $this->type = $type;
    }

    /**
     * @param Dependency $dependency
     */
    public function addDependency(Dependency $dependency): void {
        $this->dependencies[] = $dependency;
    }

    /**
     * @return array
     */
    public function getDependencies():array{
        return $this->dependencies;
    }

    /**
     * @return string
     */
    public function getFullName():string {
        return $this->fullName;
    }

    /**
     * @return string
     */
    public function getName():string {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDeveloper():string {
        return $this->developer;
    }

    /**
     * @return string
     */
    public function getVersion():string {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getURL():string {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getType():string {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type):void {
        $this->type = $type;
    }
}