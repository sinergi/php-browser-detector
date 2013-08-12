<?php

namespace Browser;

/**
 * OS Detection
 *
 * @package browser
 */
class OS
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

    const VERSION_UNKNOWN = 'unknown';

    private static $name;
    private static $version;
    private static $isMobile = false;

    /**
     * Return the name of the OS.
     *
     * @return  string
     */
    public static function getOS()
    {
        if (!isset(self::$name)) {
            self::checkOS();
        }
        return self::$name;
    }

    /**
     * Set the name of the OS.
     *
     * @param   string $value
     * @return  void
     */
    public static function setOS($value)
    {
        self::$name = $value;
    }

    /**
     * Return the version of the OS.
     *
     * @return  string
     */
    public static function getVersion()
    {
        if (isset(self::$version)) {
            return self::$version;
        } else {
            self::checkOS();
            return self::$version;
        }
    }

    /**
     * Set the version of the OS.
     *
     * @param   string $value
     * @return  void
     */
    public static function setVersion($value)
    {
        self::$version = $value;
    }

    /**
     * Is the browser from a mobile device?
     *
     * @return  bool
     */
    public static function isMobile()
    {
        if (!isset(self::$name)) {
            self::checkOS();
        }
        return self::$isMobile;
    }

    /**
     * Set the Browser to be mobile
     *
     * @param   bool $value
     * @return  void
     */
    public static function setMobile($value = true)
    {
        self::$isMobile = (bool)$value;
    }

    /**
     * Determine the user's operating system
     *
     * @return  bool
     */
    private static function checkOS()
    {
        self::$name = self::UNKNOWN;
        self::$version = self::VERSION_UNKNOWN;
        self::$isMobile = false;

        self::checkMobileBrowsers();

        return (
            // iOS before OS X
            self::checkIOS() ||
            self::checkOSX() ||
            self::checkSymbOS() ||
            self::checkWindows() ||
            self::checkFreeBSD() ||
            self::checkOpenBSD() ||
            self::checkNetBSD() ||
            self::checkOpenSolaris() ||
            self::checkSunOS() ||
            self::checkOS2() ||
            self::checkBeOS() ||
            // Android before Linux
            self::checkAndroid() ||
            self::checkLinux() ||
            self::checkNokia() ||
            self::checkBlackBerry()
        );
    }

    /**
     * Determine if the user's browser is on a mobile device.
     *
     * @return   bool
     */
    private static function checkMobileBrowsers()
    {
        // Check for Opera Mini
        if (stripos(Browser::getUserAgent(), 'opera mini') !== false) {
            self::setMobile(true);
        }
        // Set is mobile for Pocket IE
        else if (stripos(Browser::getUserAgent(), 'mspie') !== false || stripos(Browser::getUserAgent(), 'pocket') !== false) {
            self::setMobile(true);
        }
    }

    /**
     * Determine if the user's operating system is iOS.
     *
     * @return   bool
     */
    private static function checkIOS()
    {
        if (stripos(Browser::getUserAgent(), 'CPU OS') !== false || stripos(Browser::getUserAgent(), 'iPhone OS') !== false && stripos(Browser::getUserAgent(), 'OS X')) {
            self::$name = self::IOS;
            if (preg_match('/CPU( iPhone)? OS ([\d_]*)/i', Browser::getUserAgent(), $matches)) {
                self::$version = str_replace('_', '.', $matches[2]);
            }
            self::setMobile(true);
            return true;
        }
        return false;
    }

    /**
     * Determine if the user's operating system is OS X.
     *
     * @return   bool
     */
    private static function checkOSX()
    {
        if (stripos(Browser::getUserAgent(), 'OS X') !== false) {
            self::$name = self::OSX;
            if (preg_match('/OS X ([\d_]*)/i', Browser::getUserAgent(), $matches)) {
                self::$version = str_replace('_', '.', $matches[1]);
            }
            return true;
        }
        return false;
    }


    /**
     * Determine if the user's operating system is Windows.
     *
     * @return   bool
     */
    private static function checkWindows()
    {
        if (stripos(Browser::getUserAgent(), 'Windows NT') !== false) {
            self::$name = self::WINDOWS;
            // Windows version
            if (preg_match('/Windows NT ([\d\.]*)/i', Browser::getUserAgent(), $matches)) {
                switch (str_replace('_', '.', $matches[1])) {
                    case '6.2':
                        self::$version = '8';
                        break;
                    case '6.1':
                        self::$version = '7';
                        break;
                    case '6.0':
                        self::$version = 'Vista';
                        break;
                    case '5.2':
                    case '5.1':
                        self::$version = 'XP';
                        break;
                    case '5.01':
                    case '5.0':
                        self::$version = '2000';
                        break;
                    case '4.0':
                        self::$version = 'NT 4.0';
                        break;
                }
            }
            return true;
        } // Windows Me, Windows 98, Windows 95, Windows CE
        else if (preg_match('/(Windows 98; Win 9x 4\.90|Windows 98|Windows 95|Windows CE)/i', Browser::getUserAgent(), $matches)) {
            self::$name = self::WINDOWS;
            switch (strtolower($matches[0])) {
                case 'windows 98; win 9x 4.90':
                    self::$version = 'Me';
                    break;
                case 'windows 98':
                    self::$version = '98';
                    break;
                case 'windows 95':
                    self::$version = '95';
                    break;
                case 'windows ce':
                    self::$version = 'CE';
                    break;
            }
            return true;
        }

        return false;
    }

    /**
     * Determine if the user's operating system is SymbOS.
     *
     * @return   bool
     */
    private static function checkSymbOS()
    {
        if (stripos(Browser::getUserAgent(), 'SymbOS') !== false) {
            self::$name = self::SYMBOS;
            return true;
        }
        return false;
    }

    /**
     * Determine if the user's operating system is Linux.
     *
     * @return   bool
     */
    private static function checkLinux()
    {
        if (stripos(Browser::getUserAgent(), 'Linux') !== false) {
            self::$version = self::VERSION_UNKNOWN;
            self::$name = self::LINUX;
            return true;
        }
        return false;
    }

    /**
     * Determine if the user's operating system is Nokia.
     *
     * @return   bool
     */
    private static function checkNokia()
    {
        if (stripos(Browser::getUserAgent(), 'Nokia') !== false) {
            self::$version = self::VERSION_UNKNOWN;
            self::$name = self::NOKIA;
            self::setMobile(true);
            return true;
        }
        return false;
    }

    /**
     * Determine if the user's operating system is BlackBerry.
     *
     * @return   bool
     */
    private static function checkBlackBerry()
    {
        if (stripos(Browser::getUserAgent(), 'BlackBerry') !== false) {
            self::$version = self::VERSION_UNKNOWN;
            self::$name = self::BLACKBERRY;
            self::setMobile(true);
            return true;
        }
        return false;
    }

    /**
     * Determine if the user's operating system is Android.
     *
     * @return   bool
     */
    private static function checkAndroid()
    {
        if (stripos(Browser::getUserAgent(), 'Android') !== false) {
            if (preg_match('/Android ([\d\.]*)/i', Browser::getUserAgent(), $matches)) {
                self::$version = $matches[1];
            } else {
                self::$version = self::VERSION_UNKNOWN;
            }
            self::$name = self::ANDROID;
            self::setMobile(true);
            return true;
        }
        return false;
    }

    /**
     * Determine if the user's operating system is FreeBSD.
     *
     * @return   bool
     */
    private static function checkFreeBSD()
    {
        if (stripos(Browser::getUserAgent(), 'FreeBSD') !== false) {
            self::$version = self::VERSION_UNKNOWN;
            self::$name = self::FREEBSD;
            return true;
        }
        return false;
    }

    /**
     * Determine if the user's operating system is OpenBSD.
     *
     * @return   bool
     */
    private static function checkOpenBSD()
    {
        if (stripos(Browser::getUserAgent(), 'OpenBSD') !== false) {
            self::$version = self::VERSION_UNKNOWN;
            self::$name = self::OPENBSD;
            return true;
        }
        return false;
    }

    /**
     * Determine if the user's operating system is SunOS.
     *
     * @return   bool
     */
    private static function checkSunOS()
    {
        if (stripos(Browser::getUserAgent(), 'SunOS') !== false) {
            self::$version = self::VERSION_UNKNOWN;
            self::$name = self::SUNOS;
            return true;
        }
        return false;
    }

    /**
     * Determine if the user's operating system is NetBSD.
     *
     * @return   bool
     */
    private static function checkNetBSD()
    {
        if (stripos(Browser::getUserAgent(), 'NetBSD') !== false) {
            self::$version = self::VERSION_UNKNOWN;
            self::$name = self::NETBSD;
            return true;
        }
        return false;
    }

    /**
     * Determine if the user's operating system is OpenSolaris.
     *
     * @return   bool
     */
    private static function checkOpenSolaris()
    {
        if (stripos(Browser::getUserAgent(), 'OpenSolaris') !== false) {
            self::$version = self::VERSION_UNKNOWN;
            self::$name = self::OPENSOLARIS;
            return true;
        }
        return false;
    }

    /**
     * Determine if the user's operating system is OS2.
     *
     * @return   bool
     */
    private static function checkOS2()
    {
        if (stripos(Browser::getUserAgent(), 'OS\/2') !== false) {
            self::$version = self::VERSION_UNKNOWN;
            self::$name = self::OS2;
            return true;
        }
        return false;
    }

    /**
     * Determine if the user's operating system is BeOS.
     *
     * @return   bool
     */
    private static function checkBeOS()
    {
        if (stripos(Browser::getUserAgent(), 'BeOS') !== false) {
            self::$version = self::VERSION_UNKNOWN;
            self::$name = self::BEOS;
            return true;
        }
        return false;
    }
}
