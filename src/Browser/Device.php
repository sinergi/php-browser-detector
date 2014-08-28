<?php
namespace Browser;

class Device
{
    const UNKNOWN = 'unknown';
    const UNKNOWN_VERSION = 'unknown';

    const IPAD = 'iPad';

    /**
     * @var string
     */
    private $name = self::UNKNOWN;

    /**
     * @var string
     */
    private $version = self::UNKNOWN_VERSION;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $version
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }
}
