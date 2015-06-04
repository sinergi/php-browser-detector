<?php
namespace Browser;

class BrowserDetector implements DetectorInterface
{

    const FUNC_PREFIX = 'checkBrowser';

    protected static $userAgentString;

    protected static $browser;

    protected static $browsersList = [
        // well-known, well-used
        // Special Notes:
        // (1) Opera must be checked before FireFox due to the odd
        //     user agents used in some older versions of Opera
        // (2) WebTV is strapped onto Internet Explorer so we must
        //     check for WebTV before IE
        // (3) Because of Internet Explorer 11 using
        //     "Mozilla/5.0 ([...] Trident/7.0; rv:11.0) like Gecko"
        //     as user agent, tests for IE must be run before any
        //     tests checking for "Mozilla"
        // (4) (deprecated) Galeon is based on Firefox and needs to be
        //     tested before Firefox is tested
        // (5) OmniWeb is based on Safari so OmniWeb check must occur
        //     before Safari
        // (6) Netscape 9+ is based on Firefox so Netscape checks
        //     before FireFox are necessary
        'WebTv',
        'InternetExplorer',
        'Opera',
        'Galeon',
        'NetscapeNavigator9Plus',
        'SeaMonkey',
        'Firefox',
        'Chrome',
        'OmniWeb',
        // common mobile
        'Android',
        'BlackBerry',
        'Nokia',
        'Gsa',
        // common bots
        'Robot',
        // WebKit base check (post mobile and others)
        'Safari',
        // everyone else
        'NetPositive',
        'Firebird',
        'Konqueror',
        'Icab',
        'Phoenix',
        'Amaya',
        'Lynx',
        'Shiretoko',
        'IceCat',
        'Iceweasel',
        'Mozilla' /* Mozilla is such an open standard that you must check it last */
    ];

    /**
     * Routine to determine the browser type
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    public static function detect(Browser $browser, $userAgent = null)
    {
        self::$browser = $browser;
        if (is_null($userAgent)) {
            self::$userAgentString = self::$browser->getUserAgent()->getUserAgentString();
        } else {
            self::$userAgentString = $userAgent->getUserAgentString();
        }

        self::$browser->setName(Browser::UNKNOWN);
        self::$browser->setVersion(Browser::VERSION_UNKNOWN);

        self::checkChromeFrame($browser);

        foreach (self::$browsersList as $browserName) {
            $funcName = self::FUNC_PREFIX . $browserName;

            if(self::$funcName())
                return true;
        }

        return false;
    }

    /**
     * Determine if the user is using Chrome Frame.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkChromeFrame()
    {
        if (strpos(self::$userAgentString, "chromeframe") !== false) {
            self::$browser->setIsChromeFrame(true);
            return true;
        }
        return false;
    }

    /**
     * Determine if the user is using a BlackBerry.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkBrowserBlackBerry()
    {
        if (stripos(self::$userAgentString, 'blackberry') !== false) {
            $aresult = explode("/", stristr(self::$userAgentString, "BlackBerry"));
            $aversion = explode(' ', $aresult[1]);
            self::$browser->setVersion($aversion[0]);
            self::$browser->setName(Browser::BLACKBERRY);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is a robot.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkBrowserRobot()
    {
        if (
            stripos(self::$userAgentString, 'bot') !== false ||
            stripos(self::$userAgentString, 'spider') !== false ||
            stripos(self::$userAgentString, 'crawler') !== false
        ) {
            self::$browser->setIsRobot(true);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Internet Explorer.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkBrowserInternetExplorer()
    {
        // Test for v1 - v1.5 IE
        if (stripos(self::$userAgentString, 'microsoft internet explorer') !== false) {
            self::$browser->setName(Browser::IE);
            self::$browser->setVersion('1.0');
            $aresult = stristr(self::$userAgentString, '/');
            if (preg_match('/308|425|426|474|0b1/i', $aresult)) {
                self::$browser->setVersion('1.5');
            }
            return true;
        } // Test for versions > 1.5 and < 11 and some cases of 11
        else {
            if (stripos(self::$userAgentString, 'msie') !== false && stripos(self::$userAgentString, 'opera') === false
            ) {
                // See if the browser is the odd MSN Explorer
                if (stripos(self::$userAgentString, 'msnb') !== false) {
                    $aresult = explode(' ', stristr(str_replace(';', '; ', self::$userAgentString), 'MSN'));
                    self::$browser->setName(Browser::MSN);
                    self::$browser->setVersion(str_replace(array('(', ')', ';'), '', $aresult[1]));
                    return true;
                }
                $aresult = explode(' ', stristr(str_replace(';', '; ', self::$userAgentString), 'msie'));
                self::$browser->setName(Browser::IE);
                self::$browser->setVersion(str_replace(array('(', ')', ';'), '', $aresult[1]));
                // See https://msdn.microsoft.com/en-us/library/ie/hh869301%28v=vs.85%29.aspx
                // Might be 11, anyway !
                if (stripos(self::$userAgentString, 'trident') !== false) {
                    preg_match('/rv:(\d+\.\d+)/', self::$userAgentString, $matches);
                    if (isset($matches[1])) {
                        self::$browser->setVersion($matches[1]);
                    }
                }
                return true;
            } // Test for versions >= 11
            else {
                if (stripos(self::$userAgentString, 'trident') !== false) {
                    self::$browser->setName(Browser::IE);

                    preg_match('/rv:(\d+\.\d+)/', self::$userAgentString, $matches);
                    if (isset($matches[1])) {
                        self::$browser->setVersion($matches[1]);
                        return true;
                    } else {
                        return false;
                    }
                } // Test for Pocket IE
                else {
                    if (stripos(self::$userAgentString, 'mspie') !== false || stripos(self::$userAgentString,
                            'pocket') !== false
                    ) {
                        $aresult = explode(' ', stristr(self::$userAgentString, 'mspie'));
                        self::$browser->setName(Browser::POCKET_IE);

                        if (stripos(self::$userAgentString, 'mspie') !== false) {
                            self::$browser->setVersion($aresult[1]);
                        } else {
                            $aversion = explode('/', self::$userAgentString);
                            self::$browser->setVersion($aversion[1]);
                        }
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Determine if the browser is Opera.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkBrowserOpera()
    {
        if (stripos(self::$userAgentString, 'opera mini') !== false) {
            $resultant = stristr(self::$userAgentString, 'opera mini');
            if (preg_match('/\//', $resultant)) {
                $aresult = explode('/', $resultant);
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion($aversion[0]);
            } else {
                $aversion = explode(' ', stristr($resultant, 'opera mini'));
                self::$browser->setVersion($aversion[1]);
            }
            self::$browser->setName(Browser::OPERA_MINI);
            return true;
        } elseif (stripos(self::$userAgentString, 'opera') !== false) {
            $resultant = stristr(self::$userAgentString, 'opera');
            if (preg_match('/Version\/(1[0-2].*)$/', $resultant, $matches)) {
                self::$browser->setVersion($matches[1]);
            } elseif (preg_match('/\//', $resultant)) {
                $aresult = explode('/', str_replace("(", " ", $resultant));
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion($aversion[0]);
            } else {
                $aversion = explode(' ', stristr($resultant, 'opera'));
                self::$browser->setVersion(isset($aversion[1]) ? $aversion[1] : "");
            }
            self::$browser->setName(Browser::OPERA);
            return true;
        } elseif (stripos(self::$userAgentString, ' OPR/') !== false) {
            self::$browser->setName(Browser::OPERA);
            if (preg_match('/OPR\/([\d\.]*)/', self::$userAgentString, $matches)) {
                self::$browser->setVersion($matches[1]);
            }
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Chrome.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkBrowserChrome()
    {
        if (stripos(self::$userAgentString, 'Chrome') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'Chrome'));
            $aversion = explode(' ', $aresult[1]);
            self::$browser->setVersion($aversion[0]);
            self::$browser->setName(Browser::CHROME);
            return true;
        } elseif (stripos(self::$userAgentString, 'CriOS') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'CriOS'));
            $aversion = explode(' ', $aresult[1]);
            self::$browser->setVersion($aversion[0]);
            self::$browser->setName(Browser::CHROME);
            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Google Search Appliance.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkBrowserGsa()
    {
        if (stripos(self::$userAgentString, 'GSA') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'GSA'));
            $aversion = explode(' ', $aresult[1]);
            self::$browser->setVersion($aversion[0]);
            self::$browser->setName(Browser::GSA);
            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is WebTv.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkBrowserWebTv()
    {
        if (stripos(self::$userAgentString, 'webtv') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'webtv'));
            $aversion = explode(' ', $aresult[1]);
            self::$browser->setVersion($aversion[0]);
            self::$browser->setName(Browser::WEBTV);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is NetPositive.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkBrowserNetPositive()
    {
        if (stripos(self::$userAgentString, 'NetPositive') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'NetPositive'));
            $aversion = explode(' ', $aresult[1]);
            self::$browser->setVersion(str_replace(array('(', ')', ';'), '', $aversion[0]));
            self::$browser->setName(Browser::NETPOSITIVE);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Galeon.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkBrowserGaleon()
    {
        if (stripos(self::$userAgentString, 'galeon') !== false) {
            $aresult = explode(' ', stristr(self::$userAgentString, 'galeon'));
            $aversion = explode('/', $aresult[0]);
            self::$browser->setVersion($aversion[1]);
            self::$browser->setName(Browser::GALEON);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Konqueror.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkBrowserKonqueror()
    {
        if (stripos(self::$userAgentString, 'Konqueror') !== false) {
            $aresult = explode(' ', stristr(self::$userAgentString, 'Konqueror'));
            $aversion = explode('/', $aresult[0]);
            self::$browser->setVersion($aversion[1]);
            self::$browser->setName(Browser::KONQUEROR);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is iCab.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkBrowserIcab()
    {
        if (stripos(self::$userAgentString, 'icab') !== false) {
            $aversion = explode(' ', stristr(str_replace('/', ' ', self::$userAgentString), 'icab'));
            self::$browser->setVersion($aversion[1]);
            self::$browser->setName(Browser::ICAB);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is OmniWeb.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkBrowserOmniWeb()
    {
        if (stripos(self::$userAgentString, 'omniweb') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'omniweb'));
            $aversion = explode(' ', isset($aresult[1]) ? $aresult[1] : "");
            self::$browser->setVersion($aversion[0]);
            self::$browser->setName(Browser::OMNIWEB);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Phoenix.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkBrowserPhoenix()
    {
        if (stripos(self::$userAgentString, 'Phoenix') !== false) {
            $aversion = explode('/', stristr(self::$userAgentString, 'Phoenix'));
            self::$browser->setVersion($aversion[1]);
            self::$browser->setName(Browser::PHOENIX);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Firebird.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkBrowserFirebird()
    {
        if (stripos(self::$userAgentString, 'Firebird') !== false) {
            $aversion = explode('/', stristr(self::$userAgentString, 'Firebird'));
            self::$browser->setVersion($aversion[1]);
            self::$browser->setName(Browser::FIREBIRD);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Netscape Navigator 9+.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkBrowserNetscapeNavigator9Plus()
    {
        if (stripos(self::$userAgentString, 'Firefox') !== false && preg_match('/Navigator\/([^ ]*)/i',
                self::$userAgentString, $matches)
        ) {
            self::$browser->setVersion($matches[1]);
            self::$browser->setName(Browser::NETSCAPE_NAVIGATOR);
            return true;
        } elseif (stripos(self::$userAgentString, 'Firefox') === false && preg_match('/Netscape6?\/([^ ]*)/i',
                self::$userAgentString, $matches)
        ) {
            self::$browser->setVersion($matches[1]);
            self::$browser->setName(Browser::NETSCAPE_NAVIGATOR);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Shiretoko.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkBrowserShiretoko()
    {
        if (stripos(self::$userAgentString, 'Mozilla') !== false && preg_match('/Shiretoko\/([^ ]*)/i',
                self::$userAgentString, $matches)
        ) {
            self::$browser->setVersion($matches[1]);
            self::$browser->setName(Browser::SHIRETOKO);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Ice Cat.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkBrowserIceCat()
    {
        if (stripos(self::$userAgentString, 'Mozilla') !== false && preg_match('/IceCat\/([^ ]*)/i',
                self::$userAgentString, $matches)
        ) {
            self::$browser->setVersion($matches[1]);
            self::$browser->setName(Browser::ICECAT);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Nokia.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkBrowserNokia()
    {
        if (preg_match("/Nokia([^\/]+)\/([^ SP]+)/i", self::$userAgentString, $matches)) {
            self::$browser->setVersion($matches[2]);
            if (stripos(self::$userAgentString, 'Series60') !== false || strpos(self::$userAgentString,
                    'S60') !== false
            ) {
                self::$browser->setName(Browser::NOKIA_S60);
            } else {
                self::$browser->setName(Browser::NOKIA);
            }
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Firefox.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkBrowserFirefox()
    {
        if (stripos(self::$userAgentString, 'safari') === false) {
            if (preg_match("/Firefox[\/ \(]([^ ;\)]+)/i", self::$userAgentString, $matches)) {
                self::$browser->setVersion($matches[1]);
                self::$browser->setName(Browser::FIREFOX);
                return true;
            } elseif (preg_match("/Firefox$/i", self::$userAgentString, $matches)) {
                self::$browser->setVersion("");
                self::$browser->setName(Browser::FIREFOX);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is SeaMonkey.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkBrowserSeaMonkey()
    {
        if (stripos(self::$userAgentString, 'safari') === false) {
            if (preg_match("/SeaMonkey[\/ \(]([^ ;\)]+)/i", self::$userAgentString, $matches)) {
                self::$browser->setVersion($matches[1]);
                self::$browser->setName(Browser::SEAMONKEY);
                return true;
            } elseif (preg_match("/SeaMonkey$/i", self::$userAgentString, $matches)) {
                self::$browser->setVersion("");
                self::$browser->setName(Browser::SEAMONKEY);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is Iceweasel.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkBrowserIceweasel()
    {
        if (stripos(self::$userAgentString, 'Iceweasel') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'Iceweasel'));
            $aversion = explode(' ', $aresult[1]);
            self::$browser->setVersion($aversion[0]);
            self::$browser->setName(Browser::ICEWEASEL);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Mozilla.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkBrowserMozilla()
    {
        if (stripos(self::$userAgentString, 'mozilla') !== false && preg_match('/rv:[0-9].[0-9][a-b]?/i',
                self::$userAgentString) && stripos(self::$userAgentString, 'netscape') === false
        ) {
            $aversion = explode(' ', stristr(self::$userAgentString, 'rv:'));
            preg_match('/rv:[0-9].[0-9][a-b]?/i', self::$userAgentString, $aversion);
            self::$browser->setVersion(str_replace('rv:', '', $aversion[0]));
            self::$browser->setName(Browser::MOZILLA);
            return true;
        } elseif (stripos(self::$userAgentString, 'mozilla') !== false && preg_match('/rv:[0-9]\.[0-9]/i',
                self::$userAgentString) && stripos(self::$userAgentString, 'netscape') === false
        ) {
            $aversion = explode('', stristr(self::$userAgentString, 'rv:'));
            self::$browser->setVersion(str_replace('rv:', '', $aversion[0]));
            self::$browser->setName(Browser::MOZILLA);
            return true;
        } elseif (stripos(self::$userAgentString, 'mozilla') !== false && preg_match('/mozilla\/([^ ]*)/i',
                self::$userAgentString, $matches) && stripos(self::$userAgentString, 'netscape') === false
        ) {
            self::$browser->setVersion($matches[1]);
            self::$browser->setName(Browser::MOZILLA);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Lynx.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkBrowserLynx()
    {
        if (stripos(self::$userAgentString, 'lynx') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'Lynx'));
            $aversion = explode(' ', (isset($aresult[1]) ? $aresult[1] : ""));
            self::$browser->setVersion($aversion[0]);
            self::$browser->setName(Browser::LYNX);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Amaya.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkBrowserAmaya()
    {
        if (stripos(self::$userAgentString, 'amaya') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'Amaya'));
            $aversion = explode(' ', $aresult[1]);
            self::$browser->setVersion($aversion[0]);
            self::$browser->setName(Browser::AMAYA);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Safari.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkBrowserSafari()
    {
        if (stripos(self::$userAgentString, 'Safari') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'Version'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion($aversion[0]);
            } else {
                self::$browser->setVersion(Browser::VERSION_UNKNOWN);
            }
            self::$browser->setName(Browser::SAFARI);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Android.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkBrowserAndroid()
    {
        // Navigator
        if (stripos(self::$userAgentString, 'Android') !== false) {
            if (preg_match('/Version\/([\d\.]*)/i', self::$userAgentString, $matches)) {
                self::$browser->setVersion($matches[1]);
            } else {
                self::$browser->setVersion(Browser::VERSION_UNKNOWN);
            }
            self::$browser->setName(Browser::NAVIGATOR);
            return true;
        }
        return false;
    }
}
