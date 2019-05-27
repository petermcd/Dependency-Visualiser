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
    private $developer;
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

    private function populateName(string $fullName):void{
        $splitName = explode('/', $fullName);
        $this->name = $splitName[count($splitName) - 1];
        $this->developer = $splitName[0];
    }

    /**
     * @return string
     */
    public function getFullName(): string {
        return $this->fullName;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDeveloper(): string {
        return $this->developer;
    }

    /**
     * @return string
     */
    public function getVersion(): string {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getType(): string {
        return $this->type;
    }
}