<?php

namespace Sinergi\BrowserDetector;

class ScriptedAgentDetector implements DetectorInterface
{
    const FUNC_PREFIX = 'checkRobot';

    protected static $userAgentString;

    /**
     * @var ScriptedAgent
     */
    protected static $scriptedAgent;

    protected static $robotsList = array(
        'Google',
        'Baidu',
        'Facebook',
        'Bing',
        'Slurp',
        'Twitter',
        'Skype',
        'W3CValidator',
        'wkHTMLtoPDF',
        'Yandex',
        'Apple',
        'Paperli',
        'Ahrefs',
        'MJ12',
        'LiveLap',
        'Webdav',
        'MetaURI',
        'TLSProbe',
        'ScoopIt',
        'Netcraft',
        'Curl',
        'Python',
        'GoLang',
        'Perl',
        'Wget',
        'ZGrab',
        'Java',
        'Shellshock',
        'Browershots',
        'Whois',
        'MageReport',
        'Adbeat',
        'Ubermetrics',
        'Socialrank',
        'GlutenFree',
        'ICQ',
        'Proximic',
        'Verisign'
    );

    /**
     * Routine to determine the browser type.
     *
     * @param ScriptedAgent $scriptedAgent
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    public static function detect(ScriptedAgent $scriptedAgent, UserAgent $userAgent = null)
    {
        self::$scriptedAgent = $scriptedAgent;
        if (is_null($userAgent)) {
            $userAgent = self::$scriptedAgent->getUserAgent();
        }
        self::$userAgentString = $userAgent->getUserAgentString();

        self::$scriptedAgent->setName(ScriptedAgent::UNKNOWN);
        self::$scriptedAgent->setType(ScriptedAgent::UNKNOWN);
        self::$scriptedAgent->setInfoURL(ScriptedAgent::UNKNOWN);

        foreach (self::$robotsList as $robotName) {
            $funcName = self::FUNC_PREFIX . $robotName;

            if (self::$funcName()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the browser is wkHTMLtoPDF
     *
     * @return bool
     */
    public static function checkRobotwkHTMLtoPDF()
    {
        if (stripos(self::$userAgentString, 'wkhtmltopdf') !== false) {
            self::$scriptedAgent->setName(ScriptedAgent::WKHTMLTOPDF);
            self::$scriptedAgent->setType(ScriptedAgent::TOOL);
            self::$scriptedAgent->setInfoURL("https://wkhtmltopdf.org/");
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is the ICQ preview.
     *
     * @return bool
     */
    public static function checkRobotICQ()
    {
        //Chrome 51 always provides the Upgrade-Insecure-Requests header. ICQ does not.
        //But to be extra safe, also check for the russian language which the ICQ bot sets.
        if (stripos(self::$userAgentString, 'Chrome/51.0.2704.103') !== FALSE && !isset($_SERVER['HTTP_UPGRADE_INSECURE_REQUESTS']) && stristr($_SERVER['HTTP_ACCEPT_LANGUAGE'], "ru-RU") !== FALSE)
        {
            self::$scriptedAgent->setName(ScriptedAgent::ICQ);
            self::$scriptedAgent->setType(ScriptedAgent::PREVIEW);
            self::$scriptedAgent->setInfoURL("https://icq.com");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is GoogleBot, or a google ads bot.
     *
     * @return bool
     */
    public static function checkRobotGoogle()
    {
        if (stripos(self::$userAgentString, "Googlebot") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::GOOGLEBOT);
            self::$scriptedAgent->setType(ScriptedAgent::SPIDER);
            self::$scriptedAgent->setInfoURL("https://support.google.com/webmasters/answer/1061943?hl=en");
            return true;
        }
        if (stripos(self::$userAgentString, "AdsBot-Google") !== false
            || stripos(self::$userAgentString, "Mediapartners-Google") !== false
            || stripos(self::$userAgentString, "Google-Adwords") !== false
            || stripos(self::$userAgentString, "AdXVastFetcher-Google") !== false
        )
        {
            self::$scriptedAgent->setName(ScriptedAgent::GOOGLEADS);
            self::$scriptedAgent->setType(ScriptedAgent::ADVERTISING);
            self::$scriptedAgent->setInfoURL("https://support.google.com/webmasters/answer/1061943?hl=en");
            return true;
        }
        if (stripos(self::$userAgentString, "Google Favicon") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::GOOGLEFAVICON);
            self::$scriptedAgent->setType(ScriptedAgent::GENERIC);
            self::$scriptedAgent->setInfoURL("https://www.webmasterworld.com/search_engine_spiders/4626518.htm");
            return true;
        }
        if (stripos(self::$userAgentString, "Google Web Preview") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::GOOGLEPREVIEW);
            self::$scriptedAgent->setType(ScriptedAgent::PREVIEW);
            self::$scriptedAgent->setInfoURL("https://www.distilnetworks.com/bot-directory/bot/google-web-preview/");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the Baidu spider.
     *
     * @return bool
     */
    public static function checkRobotBaidu()
    {
        if (stripos(self::$userAgentString, "Baiduspider") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::BAIDU);
            self::$scriptedAgent->setType(ScriptedAgent::SPIDER);
            self::$scriptedAgent->setInfoURL("https://support.google.com/webmasters/answer/1061943?hl=en");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the Facebook preview bot.
     *
     * @return bool
     */
    public static function checkRobotFacebook()
    {
        if (stripos(self::$userAgentString, "facebookexternalhit") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::FACEBOOK);
            self::$scriptedAgent->setType(ScriptedAgent::PREVIEW);
            self::$scriptedAgent->setInfoURL("https://www.facebook.com/externalhit_uatext.php");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the bing spider, bing preview bot, or MSN bot
     *
     * @return bool
     */
    public static function checkRobotBing()
    {

        if (stripos(self::$userAgentString, "adidxbot/") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::BING);
            self::$scriptedAgent->setType(ScriptedAgent::ADVERTISING);
            self::$scriptedAgent->setInfoURL("https://www.bing.com/webmaster/help/which-crawlers-does-bing-use-8c184ec0");
            return true;
        }
        if (stripos(self::$userAgentString, "/bingbot.htm") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::BING);
            self::$scriptedAgent->setType(ScriptedAgent::SPIDER);
            self::$scriptedAgent->setInfoURL("https://www.bing.com/webmaster/help/which-crawlers-does-bing-use-8c184ec0");
            return true;
        }
        if (stripos(self::$userAgentString, "/msnbot.htm") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::MSNBOT);
            self::$scriptedAgent->setType(ScriptedAgent::SPIDER);
            self::$scriptedAgent->setInfoURL("https://www.bing.com/webmaster/help/which-crawlers-does-bing-use-8c184ec0");
            return true;
        }
        if (stripos(self::$userAgentString, "BingPreview/") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::BING_PREVIEW);
            self::$scriptedAgent->setType(ScriptedAgent::PREVIEW);
            self::$scriptedAgent->setInfoURL("https://www.bing.com/webmaster/help/which-crawlers-does-bing-use-8c184ec0");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the Yahoo Slurp! Spider.
     *
     * @return bool
     *
     */
    public static function checkRobotSlurp()
    {
        if (stripos(self::$userAgentString, "Yahoo! Slurp") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::SLURP);
            self::$scriptedAgent->setType(ScriptedAgent::SPIDER);
            self::$scriptedAgent->setInfoURL("https://help.yahoo.com/kb/SLN22600.html");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the twitter preview bot.
     *
     * @return bool
     */
    public static function checkRobotTwitter()
    {
        if (stripos(self::$userAgentString, "Twitterbot/") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::TWITTER);
            self::$scriptedAgent->setType(ScriptedAgent::PREVIEW);
            self::$scriptedAgent->setInfoURL("http://stackoverflow.com/questions/22362215/twitter-user-agent-on-sharing");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the skype preview bot.
     *
     * @return bool
     */
    public static function checkRobotSkype()
    {
        if (stripos(self::$userAgentString, "SkypeUriPreview") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::SKYPE);
            self::$scriptedAgent->setType(ScriptedAgent::PREVIEW);
            self::$scriptedAgent->setInfoURL("http://www.skype.com");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the W3C Validator tool.
     *
     * @return bool
     */
    public static function checkRobotW3CValidator()
    {
        if (stripos(self::$userAgentString, "W3C_Validator/") !== false ||
            stripos(self::$userAgentString, "Validator.nu/") !== false ||
            stripos(self::$userAgentString, "W3C-mobileOK/DDC-") !== false ||
            stripos(self::$userAgentString, "W3C_I18n-Checker/") !== false ||
            stripos(self::$userAgentString, "FeedValidator/") !== false ||
            stripos(self::$userAgentString, "Jigsaw/") !== false ||
            stripos(self::$userAgentString, "JW3C_Unicorn/") !== false
        )
        {
            self::$scriptedAgent->setName(ScriptedAgent::W3CVALIDATOR);
            self::$scriptedAgent->setType(ScriptedAgent::TOOL);
            self::$scriptedAgent->setInfoURL("https://validator.w3.org/services");
            return true;
        }
        if (stripos(self::$userAgentString, "NING/") !== false ||
            stripos(self::$userAgentString, "W3C-checklink") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::W3CVALIDATOR);
            self::$scriptedAgent->setType(ScriptedAgent::SPIDER);
            self::$scriptedAgent->setInfoURL("https://validator.w3.org/services");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the Yandex spider.
     *
     * @return bool
     */
    public static function checkRobotYandex()
    {
        if (stripos(self::$userAgentString, "YandexBot/") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::YANDEX);
            self::$scriptedAgent->setType(ScriptedAgent::SPIDER);
            self::$scriptedAgent->setInfoURL("http://yandex.com/bots");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the AppleBot
     *
     * @return bool
     */
    public static function checkRobotApple()
    {
        if (stripos(self::$userAgentString, "AppleBot/") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::APPLEBOT);
            self::$scriptedAgent->setType(ScriptedAgent::SPIDER);
            self::$scriptedAgent->setInfoURL("https://support.apple.com/en-gb/HT204683");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the Paper.li bot.
     *
     * @return bool
     */
    public static function checkRobotPaperli()
    {
        if (stripos(self::$userAgentString, "PaperLiBot/") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::PAPERLI);
            self::$scriptedAgent->setType(ScriptedAgent::SPIDER);
            self::$scriptedAgent->setInfoURL("https://support.paper.li/hc/en-us/articles/204105253-What-is-Paper-li-");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the Ahrefs survey.
     *
     * @return bool
     */
    public static function checkRobotAhrefs()
    {
        if (stripos(self::$userAgentString, "AhrefsBot/") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::AHREFS);
            self::$scriptedAgent->setType(ScriptedAgent::SURVEY);
            self::$scriptedAgent->setInfoURL("https://ahrefs.com/robot");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the Majestic 12 spider.
     *
     * @return bool
     */
    public static function checkRobotMJ12()
    {
        if (stripos(self::$userAgentString, "MJ12Bot/") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::MJ12);
            self::$scriptedAgent->setType(ScriptedAgent::SPIDER);
            self::$scriptedAgent->setInfoURL("http://www.majestic12.co.uk/projects/dsearch/mj12bot.php");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the LiveLap spider.
     *
     * @return bool
     */
    public static function checkRobotLiveLap()
    {
        if (stripos(self::$userAgentString, "LivelapBot/") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::LIVELAP);
            self::$scriptedAgent->setType(ScriptedAgent::SPIDER);
            self::$scriptedAgent->setInfoURL("http://site.livelap.com/crawler.html");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is a Web Distributed Authoring and Versioning client. Usually unexpected WebDAV requests are hack attempts.
     *
     * @return bool
     */
    public static function checkRobotWebdav()
    {
        if (stripos(self::$userAgentString, "WEBDAV Client") !== false ||
            stripos(self::$userAgentString, "Microsoft Office Existence Discovery") !== false) //Office Webdav probe
        {
            self::$scriptedAgent->setName(ScriptedAgent::WEBDAV);
            self::$scriptedAgent->setType(ScriptedAgent::TOOL);
            self::$scriptedAgent->setInfoURL("https://en.wikipedia.org/wiki/WebDAV");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the MetaURI scraper.
     *
     * @return bool
     */
    public static function checkRobotMetaURI()
    {
        if (stripos(self::$userAgentString, "MetaURI API/") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::METAURI);
            self::$scriptedAgent->setType(ScriptedAgent::SURVEY);
            self::$scriptedAgent->setInfoURL("https://github.com/stateless-systems/uri-meta");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the TLSProbe tool.
     *
     * @return bool
     */
    public static function checkRobotTLSProbe()
    {
        if (stripos(self::$userAgentString, "TLSProbe/") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::TLSPROBE);
            self::$scriptedAgent->setType(ScriptedAgent::TOOL);
            self::$scriptedAgent->setInfoURL("https://bitbucket.org/marco-bellaccini/tlsprobe");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the scoop.it bots.
     *
     * @return bool
     */
    public static function checkRobotScoopIt()
    {
        if (stripos(self::$userAgentString, "wpif Safari") !== false
            || stripos(self::$userAgentString, "imgsizer Safari") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::SCOOPIT);
            self::$scriptedAgent->setType(ScriptedAgent::SPIDER);
            self::$scriptedAgent->setInfoURL("https://www.webmasterworld.com/search_engine_spiders/4785385.htm");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the Netcraft SSL Survey.
     *
     * @return bool
     */
    public static function checkRobotNetcraft()
    {
        if (stripos(self::$userAgentString, "Netcraft SSL Server Survey") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::NETCRAFT);
            self::$scriptedAgent->setType(ScriptedAgent::SURVEY);
            self::$scriptedAgent->setInfoURL("https://www.netcraft.com/internet-data-mining/ssl-survey/");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the curl library/cli tool.
     *
     * @return bool
     */
    public static function checkRobotCurl()
    {
        if (stripos(self::$userAgentString, "curl/") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::CURL);
            self::$scriptedAgent->setType(ScriptedAgent::GENERIC);
            self::$scriptedAgent->setInfoURL("https://curl.haxx.se/");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the python programming language.
     *
     * @return bool
     */
    public static function checkRobotPython()
    {
        if (stripos(self::$userAgentString, "python-requests/") !== false ||
            stripos(self::$userAgentString, "python-urllib/") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::PYTHON);
            self::$scriptedAgent->setType(ScriptedAgent::GENERIC);
            self::$scriptedAgent->setInfoURL("https://www.python.org/");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the GoLang programming language.
     *
     * @return bool
     */
    public static function checkRobotGoLang()
    {
        if (stripos(self::$userAgentString, "Go-http-client") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::GOLANG);
            self::$scriptedAgent->setType(ScriptedAgent::GENERIC);
            self::$scriptedAgent->setInfoURL("https://golang.org/");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the perl programming language.
     *
     * @return bool
     */
    public static function checkRobotPerl()
    {
        if (stripos(self::$userAgentString, "libwww-perl/") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::PERL);
            self::$scriptedAgent->setType(ScriptedAgent::GENERIC);
            self::$scriptedAgent->setInfoURL("https://www.perl.org/");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the wget tool.
     *
     * @return bool
     */
    public static function checkRobotWget()
    {
        if (stripos(self::$userAgentString, "Wget/") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::WGET);
            self::$scriptedAgent->setType(ScriptedAgent::TOOL);
            self::$scriptedAgent->setInfoURL("https://www.gnu.org/software/wget/");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the zgrab TLS banner tool.
     *
     * @return bool
     */
    public static function checkRobotZGrab()
    {
        if (stripos(self::$userAgentString, "zgrab/") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::ZGRAB);
            self::$scriptedAgent->setType(ScriptedAgent::TOOL);
            self::$scriptedAgent->setInfoURL("https://github.com/zmap/zgrab");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the Java programming language.
     *
     * @return bool
     */
    public static function checkRobotJava()
    {
        if (stripos(self::$userAgentString, "Java/") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::JAVA);
            self::$scriptedAgent->setType(ScriptedAgent::GENERIC);
            self::$scriptedAgent->setInfoURL("https://www.java.com/en/");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the ShellShock exploit.
     *
     * @return bool
     */
    public static function checkRobotShellshock()
    {
        if (stripos(self::$userAgentString, "() { :;}; /bin/bash -c") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::SHELLSHOCK);
            self::$scriptedAgent->setType(ScriptedAgent::EXPLOIT);
            self::$scriptedAgent->setInfoURL("https://blog.cloudflare.com/inside-shellshock/");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the browsershots testing tool.
     *
     * @return bool
     */
    public static function checkRobotBrowershots()
    {
        if (stripos(self::$userAgentString, "Browsershots") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::BROWSERSHOTS);
            self::$scriptedAgent->setType(ScriptedAgent::SURVEY);
            self::$scriptedAgent->setInfoURL("http://browsershots.org/");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the who.is spider.
     *
     * @return bool
     */
    public static function checkRobotWhois()
    {
        if (stripos(self::$userAgentString, "who.is bot") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::WHOIS);
            self::$scriptedAgent->setType(ScriptedAgent::SPIDER);
            self::$scriptedAgent->setInfoURL("http://www.who.is/");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the MageReport exploit survey.
     *
     * @return bool
     */
    public static function checkRobotMageReport()
    {
        if (stripos(self::$userAgentString, "MageReport") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::MAGEREPORT);
            self::$scriptedAgent->setType(ScriptedAgent::SURVEY);
            self::$scriptedAgent->setInfoURL("https://www.magereport.com/");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the AdBeat advertising survey.
     *
     * @return bool
     */
    public static function checkRobotAdbeat()
    {
        if (stripos(self::$userAgentString, "adbeat.com") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::ADBEAT);
            self::$scriptedAgent->setType(ScriptedAgent::ADVERTISING);
            self::$scriptedAgent->setInfoURL("https://www.adbeat.com/operation_policy");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the SocialRankIO crawler.
     *
     * @return bool
     */
    public static function checkRobotSocialrank()
    {
        if (stripos(self::$userAgentString, "SocialRankIOBot") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::SOCIALRANK);
            self::$scriptedAgent->setType(ScriptedAgent::SURVEY);
            self::$scriptedAgent->setInfoURL("http://socialrank.io/about");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the Gluten Free crawler.
     *
     * @return bool
     */
    public static function checkRobotGlutenFree()
    {
        if (stripos(self::$userAgentString, "Gluten Free Crawler/") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::GLUTENFREE);
            self::$scriptedAgent->setType(ScriptedAgent::SURVEY);
            self::$scriptedAgent->setInfoURL("http://glutenfreepleasure.com/");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the Proximic spider.
     *
     * @return bool
     */
    public static function checkRobotProximic()
    {
        if (stripos(self::$userAgentString, "proximic;") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::PROXIMIC);
            self::$scriptedAgent->setType(ScriptedAgent::SPIDER);
            self::$scriptedAgent->setInfoURL("http://www.proximic.com/info/spider.php");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the Ubermetrics survey.
     *
     * @return bool
     */
    public static function checkRobotUbermetrics()
    {
        if (stripos(self::$userAgentString, "@ubermetrics-technologies.com") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::UBERMETRICS);
            self::$scriptedAgent->setType(ScriptedAgent::SURVEY);
            self::$scriptedAgent->setInfoURL("https://www.ubermetrics-technologies.com/");
            return true;
        }
        return false;
    }

    /**
     * Determine if the agent is the Verisign ips-agent.
     *
     * @return bool
     */
    public static function checkRobotVerisign()
    {
        if (stripos(self::$userAgentString, "ips-agent") !== false)
        {
            self::$scriptedAgent->setName(ScriptedAgent::VERISIGN);
            self::$scriptedAgent->setType(ScriptedAgent::SURVEY);
            self::$scriptedAgent->setInfoURL("http://www.spambotsecurity.com/forum/viewtopic.php?f=7&t=1453");
            return true;
        }
        return false;
    }
}