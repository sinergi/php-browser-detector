<?php

namespace Sinergi\BrowserDetector;

class Browser
{
    const UNKNOWN = 'unknown';
    const VIVALDI = 'Vivaldi';
    const OPERA = 'Opera';
    const OPERA_MINI = 'Opera Mini';
    const WEBTV = 'WebTV';
    const IE = 'Internet Explorer';
    const POCKET_IE = 'Pocket Internet Explorer';
    const KONQUEROR = 'Konqueror';
    const ICAB = 'iCab';
    const OMNIWEB = 'OmniWeb';
    const FIREBIRD = 'Firebird';
    const FIREFOX = 'Firefox';
    const SEAMONKEY = 'SeaMonkey';
    const ICEWEASEL = 'Iceweasel';
    const SHIRETOKO = 'Shiretoko';
    const MOZILLA = 'Mozilla';
    const AMAYA = 'Amaya';
    const LYNX = 'Lynx';
    const SAFARI = 'Safari';
    const SAMSUNG_BROWSER = 'SamsungBrowser';
    const CHROME = 'Chrome';
    const NAVIGATOR = 'Android Navigator';
    const BLACKBERRY = 'BlackBerry';
    const ICECAT = 'IceCat';
    const NOKIA_S60 = 'Nokia S60 OSS Browser';
    const NOKIA = 'Nokia Browser';
    const MSN = 'MSN Browser';
    const NETSCAPE_NAVIGATOR = 'Netscape Navigator';
    const GALEON = 'Galeon';
    const NETPOSITIVE = 'NetPositive';
    const PHOENIX = 'Phoenix';
    const GSA = 'Google Search Appliance';
    const YANDEX = 'Yandex';
    const EDGE = 'Edge';
    const EDGE_HTML = 'EdgeHTML';
    const DRAGON = 'Dragon';
    const NSPLAYER = 'Windows Media Player';
    const UCBROWSER = 'UC Browser';
    const MICROSOFT_OFFICE = 'Microsoft Office';
    const APPLE_NEWS = 'Apple News';
    const DALVIK = 'Android';

    const VERSION_UNKNOWN = 'unknown';

    /**
     * @var UserAgent
     */
    private $userAgent;

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
    private $isChromeFrame = false;

    /**
     * @var bool
     */
    private $isWebkit = false;

    /**
     * @var bool
     */
    private $isFacebookWebView = false;

    /**
     * @var bool
     */
    private $isTwitterWebView = false;

    /**
     * @var bool
     */
    private $isCompatibilityMode = false;

    /**
     * @param null|string|UserAgent $userAgent
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
     * Set the name of the Browser.
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = (string)$name;
        return $this;
    }

    /**
     * Return the name of the Browser.
     *
     * @return string
     */
    public function getName()
    {
        if (!isset($this->name)) {
            BrowserDetector::detect($this, $this->getUserAgent());
        }

        return $this->name;
    }

    /**
     * Check to see if the specific browser is valid.
     *
     * @param string $name
     * @return bool
     */
    public function isBrowser($name)
    {
        return (0 == strcasecmp($this->getName(), trim($name)));
    }

    /**
     * Set the version of the browser.
     *
     * @param string $version
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = (string)$version;
        return $this;
    }

    /**
     * The version of the browser.
     *
     * @return string
     */
    public function getVersion()
    {
        if (!isset($this->name)) {
            BrowserDetector::detect($this, $this->getUserAgent());
        }

        return (string) $this->version;
    }

    /**
     * Detects scripted agents (robots / bots)
     * Returns a resolved ScriptedAgent object if detected.
     * Otherwise returns false.
     *
     * @return ScriptedAgent|bool
     */
    public function detectScriptedAgent()
    {
        $ua = $this->getUserAgent()->getUserAgentString();
        if (stripos($ua, 'bot') !== false ||
            stripos($ua, 'spider') !== false ||
            stripos($ua, 'crawler') !== false ||
            stripos($ua, 'preview') !== false ||
            stripos($ua, 'slurp') !== false ||
            stripos($ua, 'facebookexternalhit') !== false ||
            stripos($ua, 'mediapartners') !== false ||
            stripos($ua, 'google-adwords') !== false ||
            stripos($ua, 'adxvastfetcher') !== false ||
            stripos($ua, 'adbeat') !== false ||
            stripos($ua, 'google favicon') !== false ||
            stripos($ua, 'webdav client') !== false ||
            stripos($ua, 'metauri api') !== false ||
            stripos($ua, 'tlsprobe') !== false ||
            stripos($ua, 'wpif') !== false ||
            stripos($ua, 'imgsizer') !== false ||
            stripos($ua, 'netcraft ssl server survey') !== false ||
            stripos($ua, 'curl/') !== false ||
            stripos($ua, 'go-http-client/') !== false ||
            stripos($ua, 'python') !== false ||
            stripos($ua, 'libwww') !== false ||
            stripos($ua, 'wget/') !== false ||
            stripos($ua, 'zgrab/') !== false ||
            stripos($ua, 'Java/') !== false ||
            stripos($ua, '() { :;}; /bin/bash -c') !== false ||
            stripos($ua, 'browsershots') !== false ||
            stripos($ua, 'magereport') !== false ||
            stripos($ua, 'ubermetrics-technologies') !== false ||
            stripos($ua, 'W3C') !== false ||
            stripos($ua, 'Validator') !== false ||
            stripos($ua, 'Jigsaw/') !== false ||
            stripos($ua, 'bing') !== false ||
            stripos($ua, 'msn') !== false ||
            stripos($ua, 'Google Web Preview') !== false ||
            stripos($ua, 'ips-agent') !== false ||
            (stripos($ua, 'Chrome/51.0.2704.103') !== false && !isset($_SERVER['HTTP_UPGRADE_INSECURE_REQUESTS']) && stristr($_SERVER['HTTP_ACCEPT_LANGUAGE'], "ru-RU") !== false) //ICQ Preview
        ) {
            $scriptedAgent = new ScriptedAgent($ua);
            if ($scriptedAgent->getName()==ScriptedAgent::UNKNOWN) {
                return false;
            } else {
                return $scriptedAgent;
            }
        } else {
            return false;
        }
    }

    /**
     * @param bool $isChromeFrame
     * @return $this
     */
    public function setIsChromeFrame($isChromeFrame)
    {
        $this->isChromeFrame = (bool)$isChromeFrame;
        return $this;
    }

    /**
     * Used to determine if the browser is actually "chromeframe".
     *
     * @return bool
     */
    public function getIsChromeFrame()
    {
        if (!isset($this->name)) {
            BrowserDetector::detect($this, $this->getUserAgent());
        }

        return $this->isChromeFrame;
    }

    /**
     * @return bool
     */
    public function isChromeFrame()
    {
        return $this->getIsChromeFrame();
    }

    /**
     * @param bool $isWebkit
     * @return $this
     */
    public function setIsWebkit($isWebkit)
    {
        $this->isWebkit = (bool)$isWebkit;
        return $this;
    }

    /**
     * Used to determine if the browser is actually "chromeframe".
     *
     * @return bool
     */
    public function getIsWebkit()
    {
        if (!isset($this->name)) {
            BrowserDetector::detect($this, $this->getUserAgent());
        }

        return $this->isWebkit;
    }

    /**
     * @return bool
     */
    public function isWebkit()
    {
        return $this->getIsWebkit();
    }

    /**
     * @param bool $isFacebookWebView
     * @return $this
     */
    public function setIsFacebookWebView($isFacebookWebView)
    {
        $this->isFacebookWebView = (bool) $isFacebookWebView;
        return $this;
    }

    /**
     * Used to determine if the browser is actually "facebook".
     *
     * @return bool
     */
    public function getIsFacebookWebView()
    {
        if (!isset($this->name)) {
            BrowserDetector::detect($this, $this->getUserAgent());
        }

        return $this->isFacebookWebView;
    }

    /**
     * @return bool
     */
    public function isFacebookWebView()
    {
        return $this->getIsFacebookWebView();
    }

    /**
     * @param bool $isTwitterWebView
     * @return $this
     */
    public function setIsTwitterWebView($isTwitterWebView)
    {
        $this->isTwitterWebView = (bool) $isTwitterWebView;
        return $this;
    }

    /**
     * Used to determine if the browser is actually "Twitter".
     *
     * @return bool
     */
    public function getIsTwitterWebView()
    {
        if (!isset($this->name)) {
            BrowserDetector::detect($this, $this->getUserAgent());
        }

        return $this->isTwitterWebView;
    }

    /**
     * @return bool
     */
    public function isTwitterWebView()
    {
        return $this->getIsTwitterWebView();
    }

    /**
     * @param UserAgent $userAgent
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

    /**
     * @param bool
     *
     * @return $this
     */
    public function setIsCompatibilityMode($isCompatibilityMode)
    {
        $this->isCompatibilityMode = $isCompatibilityMode;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCompatibilityMode()
    {
        return $this->isCompatibilityMode;
    }

    /**
     * Render pages outside of IE's compatibility mode.
     */
    public function endCompatibilityMode()
    {
        header('X-UA-Compatible: IE=edge');
    }
}
