<?php
namespace Browser;

class BrowserDetector implements DetectorInterface
{

    protected static $userAgentString;

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
     * @return bool
     */
    public static function detect(Browser $browser, $userAgent = null)
    {
        if(is_null($userAgent)) {
            self::$userAgentString = $browser->getUserAgent()->getUserAgentString();
        } else {
            self::$userAgentString = $userAgent->getUserAgentString();
        }

        $browser->setName(Browser::UNKNOWN);
        $browser->setVersion(Browser::VERSION_UNKNOWN);

        self::checkChromeFrame($browser);

        $checks = array_map(function($browserName) use ($browser) {
            $funcName = 'checkBrowser'.$browserName;

            return self::$funcName($browser);
        }, self::$browsersList);

        return in_array(true, $checks, true);
    }

    /**
     * Determine if the user is using Chrome Frame.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     * @return bool
     */
    private static function checkChromeFrame(Browser $browser)
    {
        if (strpos(self::$userAgentString, "chromeframe") !== false) {
            $browser->setIsChromeFrame(true);
            return true;
        }
        return false;
    }

    /**
     * Determine if the user is using a BlackBerry.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     * @return bool
     */
    private static function checkBrowserBlackBerry(Browser $browser)
    {
        if (stripos(self::$userAgentString, 'blackberry') !== false) {
            $aresult = explode("/", stristr(self::$userAgentString, "BlackBerry"));
            $aversion = explode(' ', $aresult[1]);
            $browser->setVersion($aversion[0]);
            $browser->setName(Browser::BLACKBERRY);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is a robot.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     * @return bool
     */
    private static function checkBrowserRobot(Browser $browser)
    {
        if (
            stripos(self::$userAgentString, 'bot') !== false ||
            stripos(self::$userAgentString, 'spider') !== false ||
            stripos(self::$userAgentString, 'crawler') !== false
        ) {
            $browser->setIsRobot(true);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Internet Explorer.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     * @return bool
     */
    private static function checkBrowserInternetExplorer(Browser $browser)
    {
        // Test for v1 - v1.5 IE
        if (stripos(self::$userAgentString, 'microsoft internet explorer') !== false) {
            $browser->setName(Browser::IE);
            $browser->setVersion('1.0');
            $aresult = stristr(self::$userAgentString, '/');
            if (preg_match('/308|425|426|474|0b1/i', $aresult)) {
                $browser->setVersion('1.5');
            }
            return true;
        } // Test for versions > 1.5 and < 11 and some cases of 11
        else if (stripos(self::$userAgentString, 'msie') !== false && stripos(self::$userAgentString, 'opera') === false) {
            // See if the browser is the odd MSN Explorer
            if (stripos(self::$userAgentString, 'msnb') !== false) {
                $aresult = explode(' ', stristr(str_replace(';', '; ', self::$userAgentString), 'MSN'));
                $browser->setName(Browser::MSN);
                $browser->setVersion(str_replace(array('(', ')', ';'), '', $aresult[1]));
                return true;
            }
            $aresult = explode(' ', stristr(str_replace(';', '; ', self::$userAgentString), 'msie'));
            $browser->setName(Browser::IE);
            $browser->setVersion(str_replace(array('(', ')', ';'), '', $aresult[1]));
            // See https://msdn.microsoft.com/en-us/library/ie/hh869301%28v=vs.85%29.aspx 
            // Might be 11, anyway !
            if (stripos(self::$userAgentString, 'trident') !== false) {
                preg_match('/rv:(\d+\.\d+)/', self::$userAgentString, $matches);
                if (isset($matches[1])) {
                    $browser->setVersion($matches[1]);
                }
            }
            return true;
        } // Test for versions >= 11
        else if (stripos(self::$userAgentString, 'trident') !== false) {
            $browser->setName(Browser::IE);

            preg_match('/rv:(\d+\.\d+)/', self::$userAgentString, $matches);
            if (isset($matches[1])) {
                $browser->setVersion($matches[1]);
                return true;
            } else {
                return false;
            }
        } // Test for Pocket IE
        else if (stripos(self::$userAgentString, 'mspie') !== false || stripos(self::$userAgentString, 'pocket') !== false) {
            $aresult = explode(' ', stristr(self::$userAgentString, 'mspie'));
            $browser->setName(Browser::POCKET_IE);

            if (stripos(self::$userAgentString, 'mspie') !== false) {
                $browser->setVersion($aresult[1]);
            } else {
                $aversion = explode('/', self::$userAgentString);
                $browser->setVersion($aversion[1]);
            }
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Opera.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     * @return bool
     */
    private static function checkBrowserOpera(Browser $browser)
    {
        if (stripos(self::$userAgentString, 'opera mini') !== false) {
            $resultant = stristr(self::$userAgentString, 'opera mini');
            if (preg_match('/\//', $resultant)) {
                $aresult = explode('/', $resultant);
                $aversion = explode(' ', $aresult[1]);
                $browser->setVersion($aversion[0]);
            } else {
                $aversion = explode(' ', stristr($resultant, 'opera mini'));
                $browser->setVersion($aversion[1]);
            }
            $browser->setName(Browser::OPERA_MINI);
            return true;
        } elseif (stripos(self::$userAgentString, 'opera') !== false) {
            $resultant = stristr(self::$userAgentString, 'opera');
            if (preg_match('/Version\/(1[0-2].*)$/', $resultant, $matches)) {
                $browser->setVersion($matches[1]);
            } elseif (preg_match('/\//', $resultant)) {
                $aresult = explode('/', str_replace("(", " ", $resultant));
                $aversion = explode(' ', $aresult[1]);
                $browser->setVersion($aversion[0]);
            } else {
                $aversion = explode(' ', stristr($resultant, 'opera'));
                $browser->setVersion(isset($aversion[1]) ? $aversion[1] : "");
            }
            $browser->setName(Browser::OPERA);
            return true;
        } elseif (stripos(self::$userAgentString, ' OPR/') !== false) {
            $browser->setName(Browser::OPERA);
            if (preg_match('/OPR\/([\d\.]*)/', self::$userAgentString, $matches)) {
                $browser->setVersion($matches[1]);
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
     * @return bool
     */
    private static function checkBrowserChrome(Browser $browser)
    {
        if (stripos(self::$userAgentString, 'Chrome') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'Chrome'));
            $aversion = explode(' ', $aresult[1]);
            $browser->setVersion($aversion[0]);
            $browser->setName(Browser::CHROME);
            return true;
        }

        elseif (stripos(self::$userAgentString, 'CriOS') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'CriOS'));
            $aversion = explode(' ', $aresult[1]);
            $browser->setVersion($aversion[0]);
            $browser->setName(Browser::CHROME);
            return true;
        }
        
        return false;
    }

    /**
     * Determine if the browser is Google Search Appliance.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     * @return bool
     */
    private static function checkBrowserGsa(Browser $browser)
    {
        if (stripos(self::$userAgentString, 'GSA') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'GSA'));
            $aversion = explode(' ', $aresult[1]);
            $browser->setVersion($aversion[0]);
            $browser->setName(Browser::GSA);
            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is WebTv.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     * @return bool
     */
    private static function checkBrowserWebTv(Browser $browser)
    {
        if (stripos(self::$userAgentString, 'webtv') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'webtv'));
            $aversion = explode(' ', $aresult[1]);
            $browser->setVersion($aversion[0]);
            $browser->setName(Browser::WEBTV);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is NetPositive.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     * @return bool
     */
    private static function checkBrowserNetPositive(Browser $browser)
    {
        if (stripos(self::$userAgentString, 'NetPositive') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'NetPositive'));
            $aversion = explode(' ', $aresult[1]);
            $browser->setVersion(str_replace(array('(', ')', ';'), '', $aversion[0]));
            $browser->setName(Browser::NETPOSITIVE);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Galeon.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     * @return bool
     */
    private static function checkBrowserGaleon(Browser $browser)
    {
        if (stripos(self::$userAgentString, 'galeon') !== false) {
            $aresult = explode(' ', stristr(self::$userAgentString, 'galeon'));
            $aversion = explode('/', $aresult[0]);
            $browser->setVersion($aversion[1]);
            $browser->setName(Browser::GALEON);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Konqueror.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     * @return bool
     */
    private static function checkBrowserKonqueror(Browser $browser)
    {
        if (stripos(self::$userAgentString, 'Konqueror') !== false) {
            $aresult = explode(' ', stristr(self::$userAgentString, 'Konqueror'));
            $aversion = explode('/', $aresult[0]);
            $browser->setVersion($aversion[1]);
            $browser->setName(Browser::KONQUEROR);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is iCab.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     * @return bool
     */
    private static function checkBrowserIcab(Browser $browser)
    {
        if (stripos(self::$userAgentString, 'icab') !== false) {
            $aversion = explode(' ', stristr(str_replace('/', ' ', self::$userAgentString), 'icab'));
            $browser->setVersion($aversion[1]);
            $browser->setName(Browser::ICAB);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is OmniWeb.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     * @return bool
     */
    private static function checkBrowserOmniWeb(Browser $browser)
    {
        if (stripos(self::$userAgentString, 'omniweb') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'omniweb'));
            $aversion = explode(' ', isset($aresult[1]) ? $aresult[1] : "");
            $browser->setVersion($aversion[0]);
            $browser->setName(Browser::OMNIWEB);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Phoenix.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     * @return bool
     */
    private static function checkBrowserPhoenix(Browser $browser)
    {
        if (stripos(self::$userAgentString, 'Phoenix') !== false) {
            $aversion = explode('/', stristr(self::$userAgentString, 'Phoenix'));
            $browser->setVersion($aversion[1]);
            $browser->setName(Browser::PHOENIX);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Firebird.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     * @return bool
     */
    private static function checkBrowserFirebird(Browser $browser)
    {
        if (stripos(self::$userAgentString, 'Firebird') !== false) {
            $aversion = explode('/', stristr(self::$userAgentString, 'Firebird'));
            $browser->setVersion($aversion[1]);
            $browser->setName(Browser::FIREBIRD);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Netscape Navigator 9+.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     * @return bool
     */
    private static function checkBrowserNetscapeNavigator9Plus(Browser $browser)
    {
        if (stripos(self::$userAgentString, 'Firefox') !== false && preg_match('/Navigator\/([^ ]*)/i', self::$userAgentString, $matches)) {
            $browser->setVersion($matches[1]);
            $browser->setName(Browser::NETSCAPE_NAVIGATOR);
            return true;
        } elseif (stripos(self::$userAgentString, 'Firefox') === false && preg_match('/Netscape6?\/([^ ]*)/i', self::$userAgentString, $matches)) {
            $browser->setVersion($matches[1]);
            $browser->setName(Browser::NETSCAPE_NAVIGATOR);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Shiretoko.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     * @return bool
     */
    private static function checkBrowserShiretoko(Browser $browser)
    {
        if (stripos(self::$userAgentString, 'Mozilla') !== false && preg_match('/Shiretoko\/([^ ]*)/i', self::$userAgentString, $matches)) {
            $browser->setVersion($matches[1]);
            $browser->setName(Browser::SHIRETOKO);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Ice Cat.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     * @return bool
     */
    private static function checkBrowserIceCat(Browser $browser)
    {
        if (stripos(self::$userAgentString, 'Mozilla') !== false && preg_match('/IceCat\/([^ ]*)/i', self::$userAgentString, $matches)) {
            $browser->setVersion($matches[1]);
            $browser->setName(Browser::ICECAT);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Nokia.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     * @return bool
     */
    private static function checkBrowserNokia(Browser $browser)
    {
        if (preg_match("/Nokia([^\/]+)\/([^ SP]+)/i", self::$userAgentString, $matches)) {
            $browser->setVersion($matches[2]);
            if (stripos(self::$userAgentString, 'Series60') !== false || strpos(self::$userAgentString, 'S60') !== false) {
                $browser->setName(Browser::NOKIA_S60);
            } else {
                $browser->setName(Browser::NOKIA);
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
     * @return bool
     */
    private static function checkBrowserFirefox(Browser $browser)
    {
        if (stripos(self::$userAgentString, 'safari') === false) {
            if (preg_match("/Firefox[\/ \(]([^ ;\)]+)/i", self::$userAgentString, $matches)) {
                $browser->setVersion($matches[1]);
                $browser->setName(Browser::FIREFOX);
                return true;
            } elseif (preg_match("/Firefox$/i", self::$userAgentString, $matches)) {
                $browser->setVersion("");
                $browser->setName(Browser::FIREFOX);
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
     * @return bool
     */
    private static function checkBrowserSeaMonkey(Browser $browser)
    {
        if (stripos(self::$userAgentString, 'safari') === false) {
            if (preg_match("/SeaMonkey[\/ \(]([^ ;\)]+)/i", self::$userAgentString, $matches)) {
                $browser->setVersion($matches[1]);
                $browser->setName(Browser::SEAMONKEY);
                return true;
            } elseif (preg_match("/SeaMonkey$/i", self::$userAgentString, $matches)) {
                $browser->setVersion("");
                $browser->setName(Browser::SEAMONKEY);
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
     * @return bool
     */
    private static function checkBrowserIceweasel(Browser $browser)
    {
        if (stripos(self::$userAgentString, 'Iceweasel') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'Iceweasel'));
            $aversion = explode(' ', $aresult[1]);
            $browser->setVersion($aversion[0]);
            $browser->setName(Browser::ICEWEASEL);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Mozilla.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     * @return bool
     */
    private static function checkBrowserMozilla(Browser $browser)
    {
        if (stripos(self::$userAgentString, 'mozilla') !== false && preg_match('/rv:[0-9].[0-9][a-b]?/i', self::$userAgentString) && stripos(self::$userAgentString, 'netscape') === false) {
            $aversion = explode(' ', stristr(self::$userAgentString, 'rv:'));
            preg_match('/rv:[0-9].[0-9][a-b]?/i', self::$userAgentString, $aversion);
            $browser->setVersion(str_replace('rv:', '', $aversion[0]));
            $browser->setName(Browser::MOZILLA);
            return true;
        } elseif (stripos(self::$userAgentString, 'mozilla') !== false && preg_match('/rv:[0-9]\.[0-9]/i', self::$userAgentString) && stripos(self::$userAgentString, 'netscape') === false) {
            $aversion = explode('', stristr(self::$userAgentString, 'rv:'));
            $browser->setVersion(str_replace('rv:', '', $aversion[0]));
            $browser->setName(Browser::MOZILLA);
            return true;
        } elseif (stripos(self::$userAgentString, 'mozilla') !== false && preg_match('/mozilla\/([^ ]*)/i', self::$userAgentString, $matches) && stripos(self::$userAgentString, 'netscape') === false) {
            $browser->setVersion($matches[1]);
            $browser->setName(Browser::MOZILLA);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Lynx.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     * @return bool
     */
    private static function checkBrowserLynx(Browser $browser)
    {
        if (stripos(self::$userAgentString, 'lynx') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'Lynx'));
            $aversion = explode(' ', (isset($aresult[1]) ? $aresult[1] : ""));
            $browser->setVersion($aversion[0]);
            $browser->setName(Browser::LYNX);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Amaya.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     * @return bool
     */
    private static function checkBrowserAmaya(Browser $browser)
    {
        if (stripos(self::$userAgentString, 'amaya') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'Amaya'));
            $aversion = explode(' ', $aresult[1]);
            $browser->setVersion($aversion[0]);
            $browser->setName(Browser::AMAYA);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Safari.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     * @return bool
     */
    private static function checkBrowserSafari(Browser $browser)
    {
        if (stripos(self::$userAgentString, 'Safari') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'Version'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $browser->setVersion($aversion[0]);
            } else {
                $browser->setVersion(Browser::VERSION_UNKNOWN);
            }
            $browser->setName(Browser::SAFARI);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Android.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     * @return bool
     */
    private static function checkBrowserAndroid(Browser $browser)
    {
        // Navigator
        if (stripos(self::$userAgentString, 'Android') !== false) {
            if (preg_match('/Version\/([\d\.]*)/i', self::$userAgentString, $matches)) {
                $browser->setVersion($matches[1]);
            } else {
                $browser->setVersion(Browser::VERSION_UNKNOWN);
            }
            $browser->setName(Browser::NAVIGATOR);
            return true;
        }
        return false;
    }
}
