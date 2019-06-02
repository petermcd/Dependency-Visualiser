<?php


namespace RockProfile\Package;

/**
 * Class Dependency
 * @package RockProfile\Composer
 */
class Dependency
{
    /**
     * @var string
     */
    private $fullName;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $vendor;
    /**
     * @var string
     */
    private $version;
    /**
     * @var string
     */
    private $type;

    /**
     * Dependency constructor.
     *
     * @param string $fullName
     * @param string $version
     * @param string $type
     */
    public function __construct(string $fullName, string $version, string $type){
        $this->fullName = $fullName;
        $this->version = $version;
        $this->type = $type;
        $this->populateName($fullName);
    }

    /**
     * Populates the vendor and package name based on the given name.
     *
     * @param string $fullName
     */
    private function populateName(string $fullName):void{
        $splitName = explode('/', $fullName);
        $this->name = $splitName[count($splitName) - 1];
        $this->vendor = $splitName[0];
    }

    /**
     * Getter for the fullname of the package. For composer this is vendor/package
     *
     * @return string
     */
    public function getFullName(): string {
        return $this->fullName;
    }

    /**
     * Getter for the name of the package
     *
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Getter for the vendor of the package
     *
     * @return string
     */
    public function getVendor(): string {
        return $this->vendor;
    }

    /**
     * Getter for the version of the package
     *
     * @return string
     */
    public function getVersion(): string {
        return $this->version;
    }

    /**
     * Getter for the type of package such as extension, package or project
     *
     * @return string
     */
    public function getType(): string {
        return $this->type;
    }
}