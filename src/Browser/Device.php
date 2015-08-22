<?php

namespace Browser;

class Device
{
    const UNKNOWN = 'unknown';
    const UNKNOWN_VERSION = 'unknown';

    const IPAD = 'iPad';
    const IPHONE = 'iPhone';

    /**
     * @var string
     */
    private $name = self::UNKNOWN;

    /**
     * @var string
     */
    private $version = self::UNKNOWN_VERSION;

    /**
     * @var bool
     */
    private $isDetected = false;

    /**
     * @return string
     */
    public function getName()
    {
        if (!$this->isDetected) {
            $detector = (new DeviceDetector());
            $detector->detect($this);
        }

        return $this->name;
    }

    /**
     * @param string $name
     *
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
     *
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDetected()
    {
        return $this->isDetected;
    }

    /**
     * @param bool $isDetected
     *
     * @return $this
     */
    public function setIsDetected($isDetected)
    {
        $this->isDetected = $isDetected;

        return $this;
    }
}
