<?php
namespace Browser;

class BrowserDetector implements DetectorInterface
{
    /**
     * Routine to determine the browser type
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     * @return bool
     */
    public static function detect(Browser $browser, UserAgent $userAgent)
    {
        $browser->setName($browser::UNKNOWN);
        $browser->setVersion($browser::VERSION_UNKNOWN);

        self::checkChromeFrame($browser, $userAgent);

        return (
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
            self::checkBrowserWebTv($browser, $userAgent) ||
            self::checkBrowserInternetExplorer($browser, $userAgent) ||
            self::checkBrowserOpera($browser, $userAgent) ||
            self::checkBrowserGaleon($browser, $userAgent) ||
            self::checkBrowserNetscapeNavigator9Plus($browser, $userAgent) ||
            self::checkBrowserSeaMonkey($browser, $userAgent) ||
            self::checkBrowserFirefox($browser, $userAgent) ||
            self::checkBrowserChrome($browser, $userAgent) ||
            self::checkBrowserOmniWeb($browser, $userAgent) ||

            // common mobile
            self::checkBrowserAndroid($browser, $userAgent) ||
            self::checkBrowserBlackBerry($browser, $userAgent) ||
            self::checkBrowserNokia($browser, $userAgent) ||

            // common bots
            self::checkBrowserRobot($browser, $userAgent) ||

            // WebKit base check (post mobile and others)
            self::checkBrowserSafari($browser, $userAgent) ||

            // everyone else
            self::checkBrowserNetPositive($browser, $userAgent) ||
            self::checkBrowserFirebird($browser, $userAgent) ||
            self::checkBrowserKonqueror($browser, $userAgent) ||
            self::checkBrowserIcab($browser, $userAgent) ||
            self::checkBrowserPhoenix($browser, $userAgent) ||
            self::checkBrowserAmaya($browser, $userAgent) ||
            self::checkBrowserLynx($browser, $userAgent) ||
            self::checkBrowserShiretoko($browser, $userAgent) ||
            self::checkBrowserIceCat($browser, $userAgent) ||
            self::checkBrowserIceweasel($browser, $userAgent) ||
            self::checkBrowserMozilla($browser, $userAgent) /* Mozilla is such an open standard that you must check it last */
        );
    }

    /**
     * Determine if the user is using Chrome Frame.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     * @return bool
     */
    private static function checkChromeFrame(Browser $browser, UserAgent $userAgent)
    {
        if (strpos($userAgent->getUserAgentString(), "chromeframe") !== false) {
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
    private static function checkBrowserBlackBerry(Browser $browser, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'blackberry') !== false) {
            $aresult = explode("/", stristr($userAgent->getUserAgentString(), "BlackBerry"));
            $aversion = explode(' ', $aresult[1]);
            $browser->setVersion($aversion[0]);
            $browser->setName($browser::BLACKBERRY);
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
    private static function checkBrowserRobot(Browser $browser, UserAgent $userAgent)
    {
        if (
            stripos($userAgent->getUserAgentString(), 'bot') !== false ||
            stripos($userAgent->getUserAgentString(), 'spider') !== false ||
            stripos($userAgent->getUserAgentString(), 'crawler') !== false
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
    private static function checkBrowserInternetExplorer(Browser $browser, UserAgent $userAgent)
    {
        // Test for v1 - v1.5 IE
        if (stripos($userAgent->getUserAgentString(), 'microsoft internet explorer') !== false) {
            $browser->setName($browser::IE);
            $browser->setVersion('1.0');
            $aresult = stristr($userAgent->getUserAgentString(), '/');
            if (preg_match('/308|425|426|474|0b1/i', $aresult)) {
                $browser->setVersion('1.5');
            }
            return true;
        } // Test for versions > 1.5 and < 11
        else if (stripos($userAgent->getUserAgentString(), 'msie') !== false && stripos($userAgent->getUserAgentString(), 'opera') === false) {
            // See if the browser is the odd MSN Explorer
            if (stripos($userAgent->getUserAgentString(), 'msnb') !== false) {
                $aresult = explode(' ', stristr(str_replace(';', '; ', $userAgent->getUserAgentString()), 'MSN'));
                $browser->setName($browser::MSN);
                $browser->setVersion(str_replace(array('(', ')', ';'), '', $aresult[1]));
                return true;
            }
            $aresult = explode(' ', stristr(str_replace(';', '; ', $userAgent->getUserAgentString()), 'msie'));
            $browser->setName($browser::IE);
            $browser->setVersion(str_replace(array('(', ')', ';'), '', $aresult[1]));
            return true;
        } // Test for versions >= 11
        else if (stripos($userAgent->getUserAgentString(), 'trident') !== false) {
            $browser->setName($browser::IE);

            preg_match('/rv:(\d+\.\d+)/', $userAgent->getUserAgentString(), $matches);
            if (isset($matches[1])) {
                $browser->setVersion($matches[1]);
                return true;
            } else {
                return false;
            }
        } // Test for Pocket IE
        else if (stripos($userAgent->getUserAgentString(), 'mspie') !== false || stripos($userAgent->getUserAgentString(), 'pocket') !== false) {
            $aresult = explode(' ', stristr($userAgent->getUserAgentString(), 'mspie'));
            $browser->setName($browser::POCKET_IE);

            if (stripos($userAgent->getUserAgentString(), 'mspie') !== false) {
                $browser->setVersion($aresult[1]);
            } else {
                $aversion = explode('/', $userAgent->getUserAgentString());
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
    private static function checkBrowserOpera(Browser $browser, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'opera mini') !== false) {
            $resultant = stristr($userAgent->getUserAgentString(), 'opera mini');
            if (preg_match('/\//', $resultant)) {
                $aresult = explode('/', $resultant);
                $aversion = explode(' ', $aresult[1]);
                $browser->setVersion($aversion[0]);
            } else {
                $aversion = explode(' ', stristr($resultant, 'opera mini'));
                $browser->setVersion($aversion[1]);
            }
            $browser->setName($browser::OPERA_MINI);
            return true;
        } elseif (stripos($userAgent->getUserAgentString(), 'opera') !== false) {
            $resultant = stristr($userAgent->getUserAgentString(), 'opera');
            if (preg_match('/Version\/(10.*)$/', $resultant, $matches)) {
                $browser->setVersion($matches[1]);
            } elseif (preg_match('/\//', $resultant)) {
                $aresult = explode('/', str_replace("(", " ", $resultant));
                $aversion = explode(' ', $aresult[1]);
                $browser->setVersion($aversion[0]);
            } else {
                $aversion = explode(' ', stristr($resultant, 'opera'));
                $browser->setVersion(isset($aversion[1]) ? $aversion[1] : "");
            }
            $browser->setName($browser::OPERA);
            return true;
        } elseif (stripos($userAgent->getUserAgentString(), ' OPR/') !== false) {
            $browser->setName($browser::OPERA);
            if (preg_match('/OPR\/([\d\.]*)/', $userAgent->getUserAgentString(), $matches)) {
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
    private static function checkBrowserChrome(Browser $browser, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'Chrome') !== false) {
            $aresult = explode('/', stristr($userAgent->getUserAgentString(), 'Chrome'));
            $aversion = explode(' ', $aresult[1]);
            $browser->setVersion($aversion[0]);
            $browser->setName($browser::CHROME);
            return true;
        }

        elseif (stripos($userAgent->getUserAgentString(), 'CriOS') !== false) {
            $aresult = explode('/', stristr($userAgent->getUserAgentString(), 'CriOS'));
            $aversion = explode(' ', $aresult[1]);
            $browser->setVersion($aversion[0]);
            $browser->setName($browser::CHROME);
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
    private static function checkBrowserGsa(Browser $browser, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'GSA') !== false) {
            $aresult = explode('/', stristr($userAgent->getUserAgentString(), 'GSA'));
            $aversion = explode(' ', $aresult[1]);
            $browser->setVersion($aversion[0]);
            $browser->setName($browser::GSA);
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
    private static function checkBrowserWebTv(Browser $browser, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'webtv') !== false) {
            $aresult = explode('/', stristr($userAgent->getUserAgentString(), 'webtv'));
            $aversion = explode(' ', $aresult[1]);
            $browser->setVersion($aversion[0]);
            $browser->setName($browser::WEBTV);
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
    private static function checkBrowserNetPositive(Browser $browser, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'NetPositive') !== false) {
            $aresult = explode('/', stristr($userAgent->getUserAgentString(), 'NetPositive'));
            $aversion = explode(' ', $aresult[1]);
            $browser->setVersion(str_replace(array('(', ')', ';'), '', $aversion[0]));
            $browser->setName($browser::NETPOSITIVE);
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
    private static function checkBrowserGaleon(Browser $browser, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'galeon') !== false) {
            $aresult = explode(' ', stristr($userAgent->getUserAgentString(), 'galeon'));
            $aversion = explode('/', $aresult[0]);
            $browser->setVersion($aversion[1]);
            $browser->setName($browser::GALEON);
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
    private static function checkBrowserKonqueror(Browser $browser, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'Konqueror') !== false) {
            $aresult = explode(' ', stristr($userAgent->getUserAgentString(), 'Konqueror'));
            $aversion = explode('/', $aresult[0]);
            $browser->setVersion($aversion[1]);
            $browser->setName($browser::KONQUEROR);
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
    private static function checkBrowserIcab(Browser $browser, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'icab') !== false) {
            $aversion = explode(' ', stristr(str_replace('/', ' ', $userAgent->getUserAgentString()), 'icab'));
            $browser->setVersion($aversion[1]);
            $browser->setName($browser::ICAB);
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
    private static function checkBrowserOmniWeb(Browser $browser, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'omniweb') !== false) {
            $aresult = explode('/', stristr($userAgent->getUserAgentString(), 'omniweb'));
            $aversion = explode(' ', isset($aresult[1]) ? $aresult[1] : "");
            $browser->setVersion($aversion[0]);
            $browser->setName($browser::OMNIWEB);
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
    private static function checkBrowserPhoenix(Browser $browser, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'Phoenix') !== false) {
            $aversion = explode('/', stristr($userAgent->getUserAgentString(), 'Phoenix'));
            $browser->setVersion($aversion[1]);
            $browser->setName($browser::PHOENIX);
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
    private static function checkBrowserFirebird(Browser $browser, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'Firebird') !== false) {
            $aversion = explode('/', stristr($userAgent->getUserAgentString(), 'Firebird'));
            $browser->setVersion($aversion[1]);
            $browser->setName($browser::FIREBIRD);
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
    private static function checkBrowserNetscapeNavigator9Plus(Browser $browser, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'Firefox') !== false && preg_match('/Navigator\/([^ ]*)/i', $userAgent->getUserAgentString(), $matches)) {
            $browser->setVersion($matches[1]);
            $browser->setName($browser::NETSCAPE_NAVIGATOR);
            return true;
        } elseif (stripos($userAgent->getUserAgentString(), 'Firefox') === false && preg_match('/Netscape6?\/([^ ]*)/i', $userAgent->getUserAgentString(), $matches)) {
            $browser->setVersion($matches[1]);
            $browser->setName($browser::NETSCAPE_NAVIGATOR);
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
    private static function checkBrowserShiretoko(Browser $browser, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'Mozilla') !== false && preg_match('/Shiretoko\/([^ ]*)/i', $userAgent->getUserAgentString(), $matches)) {
            $browser->setVersion($matches[1]);
            $browser->setName($browser::SHIRETOKO);
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
    private static function checkBrowserIceCat(Browser $browser, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'Mozilla') !== false && preg_match('/IceCat\/([^ ]*)/i', $userAgent->getUserAgentString(), $matches)) {
            $browser->setVersion($matches[1]);
            $browser->setName($browser::ICECAT);
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
    private static function checkBrowserNokia(Browser $browser, UserAgent $userAgent)
    {
        if (preg_match("/Nokia([^\/]+)\/([^ SP]+)/i", $userAgent->getUserAgentString(), $matches)) {
            $browser->setVersion($matches[2]);
            if (stripos($userAgent->getUserAgentString(), 'Series60') !== false || strpos($userAgent->getUserAgentString(), 'S60') !== false) {
                $browser->setName($browser::NOKIA_S60);
            } else {
                $browser->setName($browser::NOKIA);
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
    private static function checkBrowserFirefox(Browser $browser, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'safari') === false) {
            if (preg_match("/Firefox[\/ \(]([^ ;\)]+)/i", $userAgent->getUserAgentString(), $matches)) {
                $browser->setVersion($matches[1]);
                $browser->setName($browser::FIREFOX);
                return true;
            } elseif (preg_match("/Firefox$/i", $userAgent->getUserAgentString(), $matches)) {
                $browser->setVersion("");
                $browser->setName($browser::FIREFOX);
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
    private static function checkBrowserSeaMonkey(Browser $browser, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'safari') === false) {
            if (preg_match("/SeaMonkey[\/ \(]([^ ;\)]+)/i", $userAgent->getUserAgentString(), $matches)) {
                $browser->setVersion($matches[1]);
                $browser->setName($browser::SEAMONKEY);
                return true;
            } elseif (preg_match("/SeaMonkey$/i", $userAgent->getUserAgentString(), $matches)) {
                $browser->setVersion("");
                $browser->setName($browser::SEAMONKEY);
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
    private static function checkBrowserIceweasel(Browser $browser, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'Iceweasel') !== false) {
            $aresult = explode('/', stristr($userAgent->getUserAgentString(), 'Iceweasel'));
            $aversion = explode(' ', $aresult[1]);
            $browser->setVersion($aversion[0]);
            $browser->setName($browser::ICEWEASEL);
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
    private static function checkBrowserMozilla(Browser $browser, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'mozilla') !== false && preg_match('/rv:[0-9].[0-9][a-b]?/i', $userAgent->getUserAgentString()) && stripos($userAgent->getUserAgentString(), 'netscape') === false) {
            $aversion = explode(' ', stristr($userAgent->getUserAgentString(), 'rv:'));
            preg_match('/rv:[0-9].[0-9][a-b]?/i', $userAgent->getUserAgentString(), $aversion);
            $browser->setVersion(str_replace('rv:', '', $aversion[0]));
            $browser->setName($browser::MOZILLA);
            return true;
        } elseif (stripos($userAgent->getUserAgentString(), 'mozilla') !== false && preg_match('/rv:[0-9]\.[0-9]/i', $userAgent->getUserAgentString()) && stripos($userAgent->getUserAgentString(), 'netscape') === false) {
            $aversion = explode('', stristr($userAgent->getUserAgentString(), 'rv:'));
            $browser->setVersion(str_replace('rv:', '', $aversion[0]));
            $browser->setName($browser::MOZILLA);
            return true;
        } elseif (stripos($userAgent->getUserAgentString(), 'mozilla') !== false && preg_match('/mozilla\/([^ ]*)/i', $userAgent->getUserAgentString(), $matches) && stripos($userAgent->getUserAgentString(), 'netscape') === false) {
            $browser->setVersion($matches[1]);
            $browser->setName($browser::MOZILLA);
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
    private static function checkBrowserLynx(Browser $browser, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'lynx') !== false) {
            $aresult = explode('/', stristr($userAgent->getUserAgentString(), 'Lynx'));
            $aversion = explode(' ', (isset($aresult[1]) ? $aresult[1] : ""));
            $browser->setVersion($aversion[0]);
            $browser->setName($browser::LYNX);
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
    private static function checkBrowserAmaya(Browser $browser, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'amaya') !== false) {
            $aresult = explode('/', stristr($userAgent->getUserAgentString(), 'Amaya'));
            $aversion = explode(' ', $aresult[1]);
            $browser->setVersion($aversion[0]);
            $browser->setName($browser::AMAYA);
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
    private static function checkBrowserSafari(Browser $browser, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'Safari') !== false) {
            $aresult = explode('/', stristr($userAgent->getUserAgentString(), 'Version'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $browser->setVersion($aversion[0]);
            } else {
                $browser->setVersion($browser::VERSION_UNKNOWN);
            }
            $browser->setName($browser::SAFARI);
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
    private static function checkBrowserAndroid(Browser $browser, UserAgent $userAgent)
    {
        // Navigator
        if (stripos($userAgent->getUserAgentString(), 'Android') !== false) {
            if (preg_match('/Version\/([\d\.]*)/i', $userAgent->getUserAgentString(), $matches)) {
                $browser->setVersion($matches[1]);
            } else {
                $browser->setVersion($browser::VERSION_UNKNOWN);
            }
            $browser->setName($browser::NAVIGATOR);
            return true;
        }
        return false;
    }
}
