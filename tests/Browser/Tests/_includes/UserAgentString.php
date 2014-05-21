<?php
namespace Browser\Tests;

class UserAgentString
{
    /**
     * @var string
     */
    private $browser;

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $os;

    /**
     * @var string
     */
    private $os_version;

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
    public function getOsVersion()
    {
        return $this->os_version;
    }

    /**
     * @param string $os_version
     * @return $this
     */
    public function setOsVersion($os_version)
    {
        $this->os_version = $os_version;
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