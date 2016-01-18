<?php

namespace Sinergi\BrowserDetector;

/**
 * OS Detection.
 */
class Os
{
    const UNKNOWN = 'unknown';
    const OSX = 'OS X';
    const IOS = 'iOS';
    const SYMBOS = 'SymbOS';
    const WINDOWS = 'Windows';
    const ANDROID = 'Android';
    const LINUX = 'Linux';
    const NOKIA = 'Nokia';
    const BLACKBERRY = 'BlackBerry';
    const FREEBSD = 'FreeBSD';
    const OPENBSD = 'OpenBSD';
    const NETBSD = 'NetBSD';
    const OPENSOLARIS = 'OpenSolaris';
    const SUNOS = 'SunOS';
    const OS2 = 'OS2';
    const BEOS = 'BeOS';
    const WINDOWS_PHONE = 'Windows Phone';
    const CHROME_OS = 'Chrome OS';

    const VERSION_UNKNOWN = 'unknown';

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $version;

    /**
     * @var bool
     */
    private $isMobile = false;

    /**
     * @var UserAgent
     */
    private $userAgent;

    /**
     * @param null|string|UserAgent $userAgent
     *
     * @throws \Sinergi\BrowserDetector\InvalidArgumentException
     */
    public function __construct($userAgent = null)
    {
        if ($userAgent instanceof UserAgent) {
            $this->setUserAgent($userAgent);
        } elseif (null === $userAgent || is_string($userAgent)) {
            $this->setUserAgent(new UserAgent($userAgent));
        } else {
            throw new InvalidArgumentException();
        }
    }

    /**
     * Return the name of the OS.
     *
     * @return string
     */
    public function getName()
    {
        if (!isset($this->name)) {
            OsDetector::detect($this, $this->getUserAgent());
        }

        return $this->name;
    }

    /**
     * Set the name of the OS.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = (string)$name;

        return $this;
    }

    /**
     * Return the version of the OS.
     *
     * @return string
     */
    public function getVersion()
    {
        if (isset($this->version)) {
            return (string)$this->version;
        } else {
            OsDetector::detect($this, $this->getUserAgent());

            return (string)$this->version;
        }
    }

    /**
     * Set the version of the OS.
     *
     * @param string $version
     *
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = (string)$version;

        return $this;
    }

    /**
     * Is the browser from a mobile device?
     *
     * @return bool
     */
    public function getIsMobile()
    {
        if (!isset($this->name)) {
            OsDetector::detect($this, $this->getUserAgent());
        }

        return $this->isMobile;
    }

    /**
     * @return bool
     */
    public function isMobile()
    {
        return $this->getIsMobile();
    }

    /**
     * Set the Browser to be mobile.
     *
     * @param bool $isMobile
     */
    public function setIsMobile($isMobile = true)
    {
        $this->isMobile = (bool)$isMobile;
    }

    /**
     * @param UserAgent $userAgent
     *
     * @return $this
     */
    public function setUserAgent(UserAgent $userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * @return UserAgent
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }
}
