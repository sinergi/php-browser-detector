<?php

namespace Sinergi\BrowserDetector;

class ScriptedAgent
{
    const UNKNOWN = 'unknown';
    const GOOGLEBOT = 'Google';
    const BAIDU = 'Baidu';
    const SLURP = 'Yahoo! Slurp';
    const MSNBOT = 'MSN';
    const W3CVALIDATOR = 'W3C Validator';
    const WKHTMLTOPDF = 'wkhtmltopdf';
    const YANDEX = 'Yandex';
    const GOOGLEADS = 'Google Ads';
    const GLUTENFREE = 'Gluten Free';
    const TWITTER = 'Twitter';
    const APPLEBOT = 'Apple';
    const PAPERLI = 'Paper.li';
    const SOCIALRANK = 'SocialRank.io';
    const BING = 'Bing';
    const AHREFS = 'Ahrefs.com Backlink Research Tool';
    const MJ12 = 'Majestic12';
    const LIVELAP = 'LiveLap';
    const SKYPE = 'Skype';
    const ADBEAT = 'AdBeat';
    const FACEBOOK = 'Facebook';
    const BING_PREVIEW = 'Bing';
    const WEBDAV = 'WEBDAV';
    const GOOGLEFAVICON = 'Google Favicon';
    const METAURI = 'MetaURI';
    const TLSPROBE = 'TLSProbe';
    const SCOOPIT = 'Scoop.it';
    const NETCRAFT = 'Netcraft SSL';
    const CURL = 'Curl';
    const PYTHON = 'Python';
    const GOLANG = 'GoLang';
    const PERL = 'Perl';
    const VERISIGN = 'Verisign';
    const WGET = 'Wget';
    const ZGRAB = 'ZGrab';
    const JAVA = 'Java';
    const SHELLSHOCK = 'ShellShock exploit';
    const BROWSERSHOTS = 'BrowserShots';
    const WHOIS = 'Who.is';
    const MAGEREPORT = 'MageReport';
    const ICQ = 'ICQ';
    const UBERMETRICS = 'Ubermetrics Technologies';
    const PROXIMIC = "Proximic";
    const GOOGLEPREVIEW = "Google";

    const SPIDER = "Spider";
    const SURVEY = "Survey";
    const EXPLOIT = "Exploit attempt";
    const PREVIEW = "Preview";
    const TOOL = "Tool";
    const GENERIC = "Scripted Agent";
    const ADVERTISING = "Ad bots";

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
    private $type;

    /**
     * @var string
     */
    private $url;

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
     * Set the name of the ScriptedAgent.
     *
     * @param string $name
     *
     * @return void
     */
    public function setName($name)
    {
        $this->name = (string)$name;
    }

    /**
     * Return the name of the ScriptedAgent.
     *
     * @return string
     */
    public function getName()
    {
        if (!isset($this->name)) {
            ScriptedAgentDetector::detect($this, $this->getUserAgent());
        }

        return $this->name;
    }

    /**
     * Set the type of the ScriptedAgent.
     *
     * @param string $type
     *
     * @return void
     */
    public function setType($type)
    {
        $this->type = (string)$type;
    }

    /**
     * Return the type of the ScriptedAgent.
     *
     * @return string
     */
    public function getType()
    {
        if (!isset($this->type)) {
            ScriptedAgentDetector::detect($this, $this->getUserAgent());
        }

        return $this->type;
    }

    /**
     * Set the info URL for the ScriptedAgent.
     *
     * @param string $url
     *
     * @return void
     */
    public function setInfoURL($url)
    {
        $this->url = (string)$url;
    }

    /**
     * Return the info URL for the ScriptedAgent.
     *
     * @return string
     */
    public function getInfoURL()
    {
        if (!isset($this->url)) {
            ScriptedAgentDetector::detect($this, $this->getUserAgent());
        }
        return $this->url;
    }

    /**
     * @param UserAgent $userAgent
     *
     * @return void
     */
    public function setUserAgent(UserAgent $userAgent)
    {
        $this->userAgent = $userAgent;
    }

    /**
     * @return UserAgent
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }
}
