<?php

namespace Sinergi\BrowserDetector\Tests;

class UserAgentString
{
    /**
     * @var string
     */
    private $browser;

    /**
     * @var string
     */
    private $browserVersion;

    /**
     * @var string
     */
    private $os;

    /**
     * @var string
     */
    private $osVersion;

    /**
     * @var string
     */
    private $device;

    /**
     * @var string
     */
    private $deviceVersion;

    /**
     * @var string
     */
    private $string;

    /**
     * @return string
     */
    public function getBrowser()
    {
        return $this->browser;
    }

    /**
     * @param string $browser
     *
     * @return $this
     */
    public function setBrowser($browser)
    {
        $this->browser = $browser;

        return $this;
    }

    /**
     * @return string
     */
    public function getOs()
    {
        return $this->os;
    }

    /**
     * @param string $os
     *
     * @return $this
     */
    public function setOs($os)
    {
        $this->os = $os;

        return $this;
    }

    /**
     * @return string
     */
    public function getosVersion()
    {
        return (string) $this->osVersion;
    }

    /**
     * @param string $osVersion
     *
     * @return $this
     */
    public function setosVersion($osVersion)
    {
        $this->osVersion = $osVersion;

        return $this;
    }

    /**
     * @return string
     */
    public function getString()
    {
        return $this->string;
    }

    /**
     * @param string $string
     *
     * @return $this
     */
    public function setString($string)
    {
        $this->string = $string;

        return $this;
    }

    /**
     * @return string
     */
    public function getbrowserVersion()
    {
        return (string) $this->browserVersion;
    }

    /**
     * @param string $browserVersion
     *
     * @return $this
     */
    public function setbrowserVersion($browserVersion)
    {
        $this->browserVersion = $browserVersion;

        return $this;
    }

    /**
     * @return string
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * @param string $device
     *
     * @return $this
     */
    public function setDevice($device)
    {
        $this->device = $device;

        return $this;
    }

    /**
     * @return string
     */
    public function getDeviceVersion()
    {
        return $this->deviceVersion;
    }

    /**
     * @param string $deviceVersion
     *
     * @return $this
     */
    public function setDeviceVersion($deviceVersion)
    {
        $this->deviceVersion = $deviceVersion;

        return $this;
    }
}
