<?php

namespace Sinergi\BrowserDetector;

class BrowserDetector implements DetectorInterface
{
    const FUNC_PREFIX = 'checkBrowser';

    protected static $userAgentString;

    /**
     * @var Browser
     */
    protected static $browser;

    protected static $browsersList = array(
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
        // (7) Microsoft Edge must be checked before Chrome and Safari
        // (7) Vivaldi must be checked before Chrome
        'WebTv',
        'InternetExplorer',
        'Edge',
        'Opera',
        'Vivaldi',
        'Dragon',
        'Galeon',
        'NetscapeNavigator9Plus',
        'SeaMonkey',
        'Firefox',
        'Yandex',
        'Samsung',
        'Chrome',
        'OmniWeb',
        'UCBrowser', //before Android
        // common mobile
        'Android',
        'BlackBerry',
        'Nokia',
        'Gsa',
        // WebKit base check (post mobile and others)
        'AppleNews',
        'Safari',
        // everyone else
        'NetPositive',
        'Firebird',
        'Konqueror',
        'Icab',
        'Phoenix',
        'Amaya',
        'Lynx',
        'NSPlayer',
        'Office',
        'Shiretoko',
        'IceCat',
        'Iceweasel',
        'Mozilla', /* Mozilla is such an open standard that you must check it last */
    );

    //https://en.wikipedia.org/wiki/Microsoft_Edge
    protected static $edgeHTML = [
        "12.10049" => "0.10.10049",
        "12.10051" => "0.11.10051",
        "12.10052" => "0.11.10052",
        "12.10061" => "0.11.10061",
        "12.10074" => "0.11.10074",
        "12.1008" => "0.11.10080",
        "12.10122" => "13.10122",
        "12.1013" => "15.1013",
        "12.10136" => "16.10136",
        "12.10149" => "19.10149",
        "12.10158" => "20.10158",
        "12.10159" => "20.10159",
        "12.10162" => "20.10162",
        "12.10166" => "20.10166",
        "12.1024" => "20.1024",
        "12.10512" => "20.10512",
        "12.10514" => "20.10514",
        "12.10525" => "20.10525",
        "12.10532" => "20.10532",
        "12.10536" => "20.10536",
        "13.10547" => "21.10547",
        "13.10549" => "21.10549",
        "13.10565" => "23.10565",
        "13.10572" => "25.10572",
        "13.10576" => "25.10576",
        "13.10581" => "25.10581",
        "13.10586" => "25.10586",
        "13.11082" => "25.11082",
        "13.11099" => "27.11099",
        "13.11102" => "28.11102",
        "13.14251" => "28.14251",
        "13.14257" => "28.14257",
        "14.14267" => "31.14267",
        "14.14271" => "31.14271",
        "14.14279" => "31.14279",
        "14.14283" => "31.14283",
        "14.14291" => "34.14291",
        "14.14295" => "34.14295",
        "14.143" => "34.143",
        "14.14316" => "37.14316",
        "14.14322" => "37.14322",
        "14.14327" => "37.14327",
        "14.14328" => "37.14328",
        "14.14332" => "37.14332",
        "14.14342" => "38.14342",
        "14.14352" => "38.14352",
        "14.14393" => "38.14393",
        "14.14901" => "39.14901",
        "14.14905" => "39.14905",
        "14.14915" => "39.14915",
        "14.14926" => "39.14926",
        "14.14931" => "39.14931",
        "14.14936" => "39.14936",
        "15.14942" => "39.14942",
        "15.14946" => "39.14946",
        "15.14951" => "39.14951",
        "15.14955" => "39.14955",
        "15.14959" => "39.14959",
        "15.14965" => "39.14965",
        "15.14971" => "39.14971",
        "15.14977" => "39.14977",
        "15.14986" => "39.14986"
    ];

    /**
     * Routine to determine the browser type.
     *
     * @param Browser $browser
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    public static function detect(Browser $browser, UserAgent $userAgent = null)
    {
        self::$browser = $browser;
        if (is_null($userAgent)) {
            $userAgent = self::$browser->getUserAgent();
        }
        self::$userAgentString = $userAgent->getUserAgentString();

        self::$browser->setName(Browser::UNKNOWN);
        self::$browser->setVersion(Browser::VERSION_UNKNOWN);

        self::checkChromeFrame();
        self::checkFacebookWebView();
        self::checkTwitterWebView();
        self::checkWebkit();

        foreach (self::$browsersList as $browserName) {
            $funcName = self::FUNC_PREFIX . $browserName;

            if (self::$funcName()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the user is using Chrome Frame.
     *
     * @return bool
     */
    public static function checkChromeFrame()
    {
        if (strpos(self::$userAgentString, 'chromeframe') !== false) {
            self::$browser->setIsChromeFrame(true);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is a wekit webview.
     *
     * @return bool
     */
    public static function checkWebkit()
    {
        if (strpos(self::$userAgentString, 'AppleWebKit/') !== false) {
            self::$browser->setIsWebkit(true);

            return true;
        }

        return false;
    }

    /**
     * Determine if the user is using Facebook.
     *
     * @return bool
     */
    public static function checkFacebookWebView()
    {
        if (strpos(self::$userAgentString, 'FBAV') !== false) {
            self::$browser->setIsFacebookWebView(true);

            return true;
        }

        return false;
    }

    /**
     * Determine if the user is using Twitter.
     *
     * @return bool
     */
    public static function checkTwitterWebView()
    {
        if (strpos(self::$userAgentString, 'Twitter for') !== false) {
            self::$browser->setIsTwitterWebView(true);

            return true;
        }

        return false;
    }

    /**
     * Determine if the user is using a BlackBerry.
     *
     * @return bool
     */
    public static function checkBrowserBlackBerry()
    {
        if (stripos(self::$userAgentString, 'blackberry') !== false) {
            if (stripos(self::$userAgentString, 'Version/') !== false) {
                $aresult = explode('Version/', self::$userAgentString);
                if (isset($aresult[1])) {
                    $aversion = explode(' ', $aresult[1]);
                    self::$browser->setVersion($aversion[0]);
                }
            } else {
                $aresult = explode('/', stristr(self::$userAgentString, 'BlackBerry'));
                if (isset($aresult[1])) {
                    $aversion = explode(' ', $aresult[1]);
                    self::$browser->setVersion($aversion[0]);
                }
            }
            self::$browser->setName(Browser::BLACKBERRY);

            return true;
        } elseif (stripos(self::$userAgentString, 'BB10') !== false) {
            $aresult = explode('Version/10.', self::$userAgentString);
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion('10.' . $aversion[0]);
            }
            self::$browser->setName(Browser::BLACKBERRY);
            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Internet Explorer.
     *
     * @return bool
     */
    public static function checkBrowserInternetExplorer()
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
                    if (isset($aresult[1])) {
                        self::$browser->setVersion(str_replace(array('(', ')', ';'), '', $aresult[1]));
                    }

                    return true;
                }
                $aresult = explode(' ', stristr(str_replace(';', '; ', self::$userAgentString), 'msie'));
                self::$browser->setName(Browser::IE);
                if (isset($aresult[1])) {
                    self::$browser->setVersion(str_replace(array('(', ')', ';'), '', $aresult[1]));
                }
                // See https://msdn.microsoft.com/en-us/library/ie/hh869301%28v=vs.85%29.aspx
                // Might be 11, anyway !
                if (stripos(self::$userAgentString, 'trident') !== false) {
                    preg_match('/rv:(\d+\.\d+)/', self::$userAgentString, $matches);
                    if (isset($matches[1])) {
                        self::$browser->setVersion($matches[1]);
                    }

                    // At this poing in the method, we know the MSIE and Trident
                    // strings are present in the $userAgentString. If we're in
                    // compatibility mode, we need to determine the true version.
                    // If the MSIE version is 7.0, we can look at the Trident
                    // version to *approximate* the true IE version. If we don't
                    // find a matching pair, ( e.g. MSIE 7.0 && Trident/7.0 )
                    // we're *not* in compatibility mode and the browser really
                    // is version 7.0.
                    if (stripos(self::$userAgentString, 'MSIE 7.0;')) {
                        if (stripos(self::$userAgentString, 'Trident/7.0;')) {
                            // IE11 in compatibility mode
                            self::$browser->setVersion('11.0');
                            self::$browser->setIsCompatibilityMode(true);
                        } elseif (stripos(self::$userAgentString, 'Trident/6.0;')) {
                            // IE10 in compatibility mode
                            self::$browser->setVersion('10.0');
                            self::$browser->setIsCompatibilityMode(true);
                        } elseif (stripos(self::$userAgentString, 'Trident/5.0;')) {
                            // IE9 in compatibility mode
                            self::$browser->setVersion('9.0');
                            self::$browser->setIsCompatibilityMode(true);
                        } elseif (stripos(self::$userAgentString, 'Trident/4.0;')) {
                            // IE8 in compatibility mode
                            self::$browser->setVersion('8.0');
                            self::$browser->setIsCompatibilityMode(true);
                        }
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
                    if (stripos(self::$userAgentString, 'mspie') !== false ||
                        stripos(
                            self::$userAgentString,
                            'pocket'
                        ) !== false
                    ) {
                        $aresult = explode(' ', stristr(self::$userAgentString, 'mspie'));
                        self::$browser->setName(Browser::POCKET_IE);

                        if (stripos(self::$userAgentString, 'mspie') !== false) {
                            if (isset($aresult[1])) {
                                self::$browser->setVersion($aresult[1]);
                            }
                        } else {
                            $aversion = explode('/', self::$userAgentString);
                            if (isset($aversion[1])) {
                                self::$browser->setVersion($aversion[1]);
                            }
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
     * @return bool
     */
    public static function checkBrowserOpera()
    {
        if (stripos(self::$userAgentString, 'opera mini') !== false) {
            $resultant = stristr(self::$userAgentString, 'opera mini');
            if (preg_match('/\//', $resultant)) {
                $aresult = explode('/', $resultant);
                if (isset($aresult[1])) {
                    $aversion = explode(' ', $aresult[1]);
                    self::$browser->setVersion($aversion[0]);
                }
            } else {
                $aversion = explode(' ', stristr($resultant, 'opera mini'));
                if (isset($aversion[1])) {
                    self::$browser->setVersion($aversion[1]);
                }
            }
            self::$browser->setName(Browser::OPERA_MINI);

            return true;
        } elseif (stripos(self::$userAgentString, 'OPiOS') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'OPiOS'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion($aversion[0]);
            }
            self::$browser->setName(Browser::OPERA_MINI);

            return true;
        } elseif (stripos(self::$userAgentString, 'opera') !== false) {
            $resultant = stristr(self::$userAgentString, 'opera');
            if (preg_match('/Version\/(1[0-2].*)$/', $resultant, $matches)) {
                if (isset($matches[1])) {
                    self::$browser->setVersion($matches[1]);
                }
            } elseif (preg_match('/\//', $resultant)) {
                $aresult = explode('/', str_replace('(', ' ', $resultant));
                if (isset($aresult[1])) {
                    $aversion = explode(' ', $aresult[1]);
                    self::$browser->setVersion($aversion[0]);
                }
            } else {
                $aversion = explode(' ', stristr($resultant, 'opera'));
                self::$browser->setVersion(isset($aversion[1]) ? $aversion[1] : '');
            }
            self::$browser->setName(Browser::OPERA);

            return true;
        } elseif (stripos(self::$userAgentString, ' OPR/') !== false) {
            self::$browser->setName(Browser::OPERA);
            if (preg_match('/OPR\/([\d\.]*)/', self::$userAgentString, $matches)) {
                if (isset($matches[1])) {
                    self::$browser->setVersion($matches[1]);
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Samsung.
     *
     * @return bool
     */
    public static function checkBrowserSamsung()
    {
        if (stripos(self::$userAgentString, 'SamsungBrowser') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'SamsungBrowser'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion($aversion[0]);
            }
            self::$browser->setName(Browser::SAMSUNG_BROWSER);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Chrome.
     *
     * @return bool
     */
    public static function checkBrowserChrome()
    {
        if (stripos(self::$userAgentString, 'Chrome') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'Chrome'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion($aversion[0]);
            }
            self::$browser->setName(Browser::CHROME);

            return true;
        } elseif (stripos(self::$userAgentString, 'CriOS') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'CriOS'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion($aversion[0]);
            }
            self::$browser->setName(Browser::CHROME);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Vivaldi.
     *
     * @return bool
     */
    public static function checkBrowserVivaldi()
    {
        if (stripos(self::$userAgentString, 'Vivaldi') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'Vivaldi'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion($aversion[0]);
            }
            self::$browser->setName(Browser::VIVALDI);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Microsoft Edge.
     *
     * @return bool
     */
    public static function checkBrowserEdge()
    {
        if (stripos(self::$userAgentString, 'Edge') !== false) {
            preg_match('/Edge[\\/ \\(]([a-zA-Z\\d\\.]*)/i', self::$userAgentString, $matches);
            if (sizeof($matches)>1) {
                if (isset(self::$edgeHTML[$matches[1]])) {
                    self::$browser->setName(Browser::EDGE);
                    self::$browser->setVersion(self::$edgeHTML[$matches[1]]);
                } else {
                    self::$browser->setName(Browser::EDGE_HTML);
                    self::$browser->setVersion($matches[1]);
                }
            } else {
                self::$browser->setName(Browser::EDGE);
            }

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Google Search Appliance.
     *
     * @return bool
     */
    public static function checkBrowserGsa()
    {
        if (stripos(self::$userAgentString, 'GSA') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'GSA'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion($aversion[0]);
            }
            self::$browser->setName(Browser::GSA);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is WebTv.
     *
     * @return bool
     */
    public static function checkBrowserWebTv()
    {
        if (stripos(self::$userAgentString, 'webtv') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'webtv'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion($aversion[0]);
            }
            self::$browser->setName(Browser::WEBTV);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is NetPositive.
     *
     * @return bool
     */
    public static function checkBrowserNetPositive()
    {
        if (stripos(self::$userAgentString, 'NetPositive') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'NetPositive'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion(str_replace(array('(', ')', ';'), '', $aversion[0]));
            }
            self::$browser->setName(Browser::NETPOSITIVE);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Galeon.
     *
     * @return bool
     */
    public static function checkBrowserGaleon()
    {
        if (stripos(self::$userAgentString, 'galeon') !== false) {
            $aresult = explode(' ', stristr(self::$userAgentString, 'galeon'));
            $aversion = explode('/', $aresult[0]);
            if (isset($aversion[1])) {
                self::$browser->setVersion($aversion[1]);
            }
            self::$browser->setName(Browser::GALEON);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Konqueror.
     *
     * @return bool
     */
    public static function checkBrowserKonqueror()
    {
        if (stripos(self::$userAgentString, 'Konqueror') !== false) {
            $aresult = explode(' ', stristr(self::$userAgentString, 'Konqueror'));
            $aversion = explode('/', $aresult[0]);
            if (isset($aversion[1])) {
                self::$browser->setVersion($aversion[1]);
            }
            self::$browser->setName(Browser::KONQUEROR);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is iCab.
     *
     * @return bool
     */
    public static function checkBrowserIcab()
    {
        if (stripos(self::$userAgentString, 'icab') !== false) {
            $aversion = explode(' ', stristr(str_replace('/', ' ', self::$userAgentString), 'icab'));
            if (isset($aversion[1])) {
                self::$browser->setVersion($aversion[1]);
            }
            self::$browser->setName(Browser::ICAB);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is OmniWeb.
     *
     * @return bool
     */
    public static function checkBrowserOmniWeb()
    {
        if (stripos(self::$userAgentString, 'omniweb') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'omniweb'));
            $aversion = explode(' ', isset($aresult[1]) ? $aresult[1] : '');
            self::$browser->setVersion($aversion[0]);
            self::$browser->setName(Browser::OMNIWEB);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Phoenix.
     *
     * @return bool
     */
    public static function checkBrowserPhoenix()
    {
        if (stripos(self::$userAgentString, 'Phoenix') !== false) {
            $aversion = explode('/', stristr(self::$userAgentString, 'Phoenix'));
            if (isset($aversion[1])) {
                self::$browser->setVersion($aversion[1]);
            }
            self::$browser->setName(Browser::PHOENIX);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Firebird.
     *
     * @return bool
     */
    public static function checkBrowserFirebird()
    {
        if (stripos(self::$userAgentString, 'Firebird') !== false) {
            $aversion = explode('/', stristr(self::$userAgentString, 'Firebird'));
            if (isset($aversion[1])) {
                self::$browser->setVersion($aversion[1]);
            }
            self::$browser->setName(Browser::FIREBIRD);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Netscape Navigator 9+.
     *
     * @return bool
     */
    public static function checkBrowserNetscapeNavigator9Plus()
    {
        if (stripos(self::$userAgentString, 'Firefox') !== false &&
            preg_match('/Navigator\/([^ ]*)/i', self::$userAgentString, $matches)
        ) {
            if (isset($matches[1])) {
                self::$browser->setVersion($matches[1]);
            }
            self::$browser->setName(Browser::NETSCAPE_NAVIGATOR);

            return true;
        } elseif (stripos(self::$userAgentString, 'Firefox') === false &&
            preg_match('/Netscape6?\/([^ ]*)/i', self::$userAgentString, $matches)
        ) {
            if (isset($matches[1])) {
                self::$browser->setVersion($matches[1]);
            }
            self::$browser->setName(Browser::NETSCAPE_NAVIGATOR);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Shiretoko.
     *
     * @return bool
     */
    public static function checkBrowserShiretoko()
    {
        if (stripos(self::$userAgentString, 'Mozilla') !== false &&
            preg_match('/Shiretoko\/([^ ]*)/i', self::$userAgentString, $matches)
        ) {
            if (isset($matches[1])) {
                self::$browser->setVersion($matches[1]);
            }
            self::$browser->setName(Browser::SHIRETOKO);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Ice Cat.
     *
     * @return bool
     */
    public static function checkBrowserIceCat()
    {
        if (stripos(self::$userAgentString, 'Mozilla') !== false &&
            preg_match('/IceCat\/([^ ]*)/i', self::$userAgentString, $matches)
        ) {
            if (isset($matches[1])) {
                self::$browser->setVersion($matches[1]);
            }
            self::$browser->setName(Browser::ICECAT);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Nokia.
     *
     * @return bool
     */
    public static function checkBrowserNokia()
    {
        if (preg_match("/Nokia([^\\/]+)\\/([^ SP]+)/i", self::$userAgentString, $matches)) {
            self::$browser->setVersion($matches[2]);
            if (stripos(self::$userAgentString, 'Series60') !== false ||
                strpos(self::$userAgentString, 'S60') !== false
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
     * @return bool
     */
    public static function checkBrowserFirefox()
    {
        if (stripos(self::$userAgentString, 'safari') === false) {
            if (preg_match("/Firefox[\\/ \\(]([a-zA-Z\\d\\.]*)/i", self::$userAgentString, $matches)) {
                if (isset($matches[1])) {
                    self::$browser->setVersion($matches[1]);
                }
                self::$browser->setName(Browser::FIREFOX);

                return true;
            } elseif (preg_match('/Firefox$/i', self::$userAgentString, $matches)) {
                self::$browser->setVersion('');
                self::$browser->setName(Browser::FIREFOX);

                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the browser is SeaMonkey.
     *
     * @return bool
     */
    public static function checkBrowserSeaMonkey()
    {
        if (stripos(self::$userAgentString, 'safari') === false) {
            if (preg_match("/SeaMonkey[\\/ \\(]([a-zA-Z\\d\\.]*)/i", self::$userAgentString, $matches)) {
                if (isset($matches[1])) {
                    self::$browser->setVersion($matches[1]);
                }
                self::$browser->setName(Browser::SEAMONKEY);

                return true;
            } elseif (preg_match('/SeaMonkey$/i', self::$userAgentString, $matches)) {
                self::$browser->setVersion('');
                self::$browser->setName(Browser::SEAMONKEY);

                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the browser is Iceweasel.
     *
     * @return bool
     */
    public static function checkBrowserIceweasel()
    {
        if (stripos(self::$userAgentString, 'Iceweasel') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'Iceweasel'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion($aversion[0]);
            }
            self::$browser->setName(Browser::ICEWEASEL);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Mozilla.
     *
     * @return bool
     */
    public static function checkBrowserMozilla()
    {
        if (stripos(self::$userAgentString, 'mozilla') !== false &&
            preg_match('/rv:[0-9].[0-9][a-b]?/i', self::$userAgentString) &&
            stripos(self::$userAgentString, 'netscape') === false
        ) {
            $aversion = explode(' ', stristr(self::$userAgentString, 'rv:'));
            preg_match('/rv:[0-9].[0-9][a-b]?/i', self::$userAgentString, $aversion);
            self::$browser->setVersion(str_replace('rv:', '', $aversion[0]));
            self::$browser->setName(Browser::MOZILLA);

            return true;
        } elseif (stripos(self::$userAgentString, 'mozilla') !== false &&
            preg_match('/rv:[0-9]\.[0-9]/i', self::$userAgentString) &&
            stripos(self::$userAgentString, 'netscape') === false
        ) {
            $aversion = explode('', stristr(self::$userAgentString, 'rv:'));
            self::$browser->setVersion(str_replace('rv:', '', $aversion[0]));
            self::$browser->setName(Browser::MOZILLA);

            return true;
        } elseif (stripos(self::$userAgentString, 'mozilla') !== false &&
            preg_match('/mozilla\/([^ ]*)/i', self::$userAgentString, $matches) &&
            stripos(self::$userAgentString, 'netscape') === false
        ) {
            if (isset($matches[1])) {
                self::$browser->setVersion($matches[1]);
            }
            self::$browser->setName(Browser::MOZILLA);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Lynx.
     *
     * @return bool
     */
    public static function checkBrowserLynx()
    {
        if (stripos(self::$userAgentString, 'lynx') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'Lynx'));
            $aversion = explode(' ', (isset($aresult[1]) ? $aresult[1] : ''));
            self::$browser->setVersion($aversion[0]);
            self::$browser->setName(Browser::LYNX);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Amaya.
     *
     * @return bool
     */
    public static function checkBrowserAmaya()
    {
        if (stripos(self::$userAgentString, 'amaya') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'Amaya'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion($aversion[0]);
            }
            self::$browser->setName(Browser::AMAYA);

            return true;
        }

        return false;
    }


    /**
     * Determine if the browser is Safari.
     *
     * @return bool
     */
    public static function checkBrowserSafari()
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
     * Determine if the browser is Yandex.
     *
     * @return bool
     */
    public static function checkBrowserYandex()
    {
        if (stripos(self::$userAgentString, 'YaBrowser') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'YaBrowser'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion($aversion[0]);
            }
            self::$browser->setName(Browser::YANDEX);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Comodo Dragon / Ice Dragon / Chromodo.
     *
     * @return bool
     */
    public static function checkBrowserDragon()
    {
        if (stripos(self::$userAgentString, 'Dragon') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'Dragon'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion($aversion[0]);
            }
            self::$browser->setName(Browser::DRAGON);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Android.
     *
     * @return bool
     */
    public static function checkBrowserAndroid()
    {
        // Android Navigator
        if (stripos(self::$userAgentString, 'Android') !== false) {
            if (preg_match('/Version\/([\d\.]*)/i', self::$userAgentString, $matches)) {
                if (isset($matches[1])) {
                    self::$browser->setVersion($matches[1]);
                }
            } else {
                self::$browser->setVersion(Browser::VERSION_UNKNOWN);
            }
            self::$browser->setName(Browser::NAVIGATOR);

            return true;
        }

        // Dalvik (Android OS)
        if (stripos(self::$userAgentString, 'Dalvik/') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'Dalvik'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion($aversion[0]);
            }
            self::$browser->setName(Browser::DALVIK);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is UCBrowser.
     *
     * @return bool
     */
    public static function checkBrowserUCBrowser()
    {
        // Navigator
        if (stripos(self::$userAgentString, 'UCBrowser/') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'UCBrowser'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion($aversion[0]);
            }
            self::$browser->setName(Browser::UCBROWSER);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Windows Media Player.
     *
     * @return bool
     */
    public static function checkBrowserNSPlayer()
    {
        // Navigator
        if (stripos(self::$userAgentString, 'NSPlayer/') !== false) {
            $aresult = explode('/', stristr(self::$userAgentString, 'NSPlayer'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                self::$browser->setVersion($aversion[0]);
            }
            self::$browser->setName(Browser::NSPLAYER);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is Microsoft Office.
     *
     * @return bool
     */
    public static function checkBrowserOffice()
    {
        // Navigator
        if (stripos(self::$userAgentString, 'Microsoft Office') !== false) {
            self::$browser->setVersion(Browser::VERSION_UNKNOWN);
            self::$browser->setName(Browser::NSPLAYER);

            return true;
        }

        return false;
    }

    /**
     * Determine if the browser is the Apple News app.
     *
     * @return bool
     */
    public static function checkBrowserAppleNews()
    {
        // Navigator
        if (stripos(self::$userAgentString, 'AppleNews/') !== false) {
            if (preg_match('/Version\/([\d\.]*)/i', self::$userAgentString, $matches)) {
                if (isset($matches[1])) {
                    self::$browser->setVersion($matches[1]);
                }
            } else {
                self::$browser->setVersion(Browser::VERSION_UNKNOWN);
            }
            self::$browser->setName(Browser::APPLE_NEWS);

            return true;
        }

        return false;
    }
}
