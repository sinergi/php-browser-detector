<?php

namespace Browser;

class OsDetector implements DetectorInterface
{
    /**
     * Determine the user's operating system.
     *
     * @param Os $os
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    public static function detect(Os $os, UserAgent $userAgent)
    {
        $os->setName($os::UNKNOWN);
        $os->setVersion($os::VERSION_UNKNOWN);
        $os->setIsMobile(false);

        self::checkMobileBrowsers($os, $userAgent);

        return (
            // iOS before OS X
            self::checkIOS($os, $userAgent) ||
            self::checkOSX($os, $userAgent) ||
            self::checkSymbOS($os, $userAgent) ||
            self::checkWindows($os, $userAgent) ||
            self::checkFreeBSD($os, $userAgent) ||
            self::checkOpenBSD($os, $userAgent) ||
            self::checkNetBSD($os, $userAgent) ||
            self::checkOpenSolaris($os, $userAgent) ||
            self::checkSunOS($os, $userAgent) ||
            self::checkOS2($os, $userAgent) ||
            self::checkBeOS($os, $userAgent) ||
            // Android before Linux
            self::checkAndroid($os, $userAgent) ||
            self::checkLinux($os, $userAgent) ||
            self::checkNokia($os, $userAgent) ||
            self::checkBlackBerry($os, $userAgent)
        );
    }

    /**
     * Determine if the user's browser is on a mobile device.
     *
     * @param Os $os
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    public static function checkMobileBrowsers(Os $os, UserAgent $userAgent)
    {
        // Check for Opera Mini
        if (stripos($userAgent->getUserAgentString(), 'opera mini') !== false) {
            $os->setIsMobile(true);
        } // Set is mobile for Pocket IE
        elseif (stripos($userAgent->getUserAgentString(), 'mspie') !== false || stripos($userAgent->getUserAgentString(), 'pocket') !== false) {
            $os->setIsMobile(true);
        }
    }

    /**
     * Determine if the user's operating system is iOS.
     *
     * @param Os $os
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkIOS(Os $os, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'CPU OS') !== false || stripos($userAgent->getUserAgentString(), 'iPhone OS') !== false && stripos($userAgent->getUserAgentString(), 'OS X')) {
            $os->setName($os::IOS);
            if (preg_match('/CPU( iPhone)? OS ([\d_]*)/i', $userAgent->getUserAgentString(), $matches)) {
                $os->setVersion(str_replace('_', '.', $matches[2]));
            }
            $os->setIsMobile(true);

            return true;
        }

        return false;
    }

    /**
     * Determine if the user's operating system is OS X.
     *
     * @param Os $os
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkOSX(Os $os, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'OS X') !== false) {
            $os->setName($os::OSX);
            if (preg_match('/OS X ([\d\._]*)/i', $userAgent->getUserAgentString(), $matches)) {
                $os->setVersion(str_replace('_', '.', $matches[1]));
            }

            return true;
        }

        return false;
    }

    /**
     * Determine if the user's operating system is Windows.
     *
     * @param Os $os
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkWindows(Os $os, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'Windows NT') !== false) {
            $os->setName($os::WINDOWS);
            // Windows version
            if (preg_match('/Windows NT ([\d\.]*)/i', $userAgent->getUserAgentString(), $matches)) {
                switch (str_replace('_', '.', $matches[1])) {
                    case '6.3':
                         $os->setVersion('8.1');
                         break;
                    case '6.2':
                        $os->setVersion('8');
                        break;
                    case '6.1':
                        $os->setVersion('7');
                        break;
                    case '6.0':
                        $os->setVersion('Vista');
                        break;
                    case '5.2':
                    case '5.1':
                        $os->setVersion('XP');
                        break;
                    case '5.01':
                    case '5.0':
                        $os->setVersion('2000');
                        break;
                    case '4.0':
                        $os->setVersion('NT 4.0');
                        break;
                    default:
                        if ((float) $matches[1] >= 10.0) {
                            $os->setVersion((float) $matches[1]);
                        }
                        break;
                }
            }

            return true;
        } // Windows Me, Windows 98, Windows 95, Windows CE
        elseif (preg_match('/(Windows 98; Win 9x 4\.90|Windows 98|Windows 95|Windows CE)/i', $userAgent->getUserAgentString(), $matches)) {
            $os->setName($os::WINDOWS);
            switch (strtolower($matches[0])) {
                case 'windows 98; win 9x 4.90':
                    $os->setVersion('Me');
                    break;
                case 'windows 98':
                    $os->setVersion('98');
                    break;
                case 'windows 95':
                    $os->setVersion('95');
                    break;
                case 'windows ce':
                    $os->setVersion('CE');
                    break;
            }

            return true;
        }

        return false;
    }

    /**
     * Determine if the user's operating system is SymbOS.
     *
     * @param Os $os
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkSymbOS(Os $os, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'SymbOS') !== false) {
            $os->setName($os::SYMBOS);

            return true;
        }

        return false;
    }

    /**
     * Determine if the user's operating system is Linux.
     *
     * @param Os $os
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkLinux(Os $os, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'Linux') !== false) {
            $os->setVersion($os::VERSION_UNKNOWN);
            $os->setName($os::LINUX);

            return true;
        }

        return false;
    }

    /**
     * Determine if the user's operating system is Nokia.
     *
     * @param Os $os
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkNokia(Os $os, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'Nokia') !== false) {
            $os->setVersion($os::VERSION_UNKNOWN);
            $os->setName($os::NOKIA);
            $os->setIsMobile(true);

            return true;
        }

        return false;
    }

    /**
     * Determine if the user's operating system is BlackBerry.
     *
     * @param Os $os
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkBlackBerry(Os $os, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'BlackBerry') !== false) {
            $os->setVersion($os::VERSION_UNKNOWN);
            $os->setName($os::BLACKBERRY);
            $os->setIsMobile(true);

            return true;
        }

        return false;
    }

    /**
     * Determine if the user's operating system is Android.
     *
     * @param Os $os
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkAndroid(Os $os, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'Android') !== false) {
            if (preg_match('/Android ([\d\.]*)/i', $userAgent->getUserAgentString(), $matches)) {
                $os->setVersion($matches[1]);
            } else {
                $os->setVersion($os::VERSION_UNKNOWN);
            }
            $os->setName($os::ANDROID);
            $os->setIsMobile(true);

            return true;
        }

        return false;
    }

    /**
     * Determine if the user's operating system is FreeBSD.
     *
     * @param Os $os
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkFreeBSD(Os $os, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'FreeBSD') !== false) {
            $os->setVersion($os::VERSION_UNKNOWN);
            $os->setName($os::FREEBSD);

            return true;
        }

        return false;
    }

    /**
     * Determine if the user's operating system is OpenBSD.
     *
     * @param Os $os
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkOpenBSD(Os $os, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'OpenBSD') !== false) {
            $os->setVersion($os::VERSION_UNKNOWN);
            $os->setName($os::OPENBSD);

            return true;
        }

        return false;
    }

    /**
     * Determine if the user's operating system is SunOS.
     *
     * @param Os $os
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkSunOS(Os $os, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'SunOS') !== false) {
            $os->setVersion($os::VERSION_UNKNOWN);
            $os->setName($os::SUNOS);

            return true;
        }

        return false;
    }

    /**
     * Determine if the user's operating system is NetBSD.
     *
     * @param Os $os
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkNetBSD(Os $os, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'NetBSD') !== false) {
            $os->setVersion($os::VERSION_UNKNOWN);
            $os->setName($os::NETBSD);

            return true;
        }

        return false;
    }

    /**
     * Determine if the user's operating system is OpenSolaris.
     *
     * @param Os $os
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkOpenSolaris(Os $os, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'OpenSolaris') !== false) {
            $os->setVersion($os::VERSION_UNKNOWN);
            $os->setName($os::OPENSOLARIS);

            return true;
        }

        return false;
    }

    /**
     * Determine if the user's operating system is OS2.
     *
     * @param Os $os
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkOS2(Os $os, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'OS\/2') !== false) {
            $os->setVersion($os::VERSION_UNKNOWN);
            $os->setName($os::OS2);

            return true;
        }

        return false;
    }

    /**
     * Determine if the user's operating system is BeOS.
     *
     * @param Os $os
     * @param UserAgent $userAgent
     *
     * @return bool
     */
    private static function checkBeOS(Os $os, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'BeOS') !== false) {
            $os->setVersion($os::VERSION_UNKNOWN);
            $os->setName($os::BEOS);

            return true;
        }

        return false;
    }
}
