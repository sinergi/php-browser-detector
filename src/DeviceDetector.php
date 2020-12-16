<?php

namespace Sinergi\BrowserDetector;

class DeviceDetector implements DetectorInterface
{
    /**
     * Determine the user's device.
     *
     * @param Device $device
     * @param UserAgent $userAgent
     * @return bool
     */
    public static function detect(Device $device, UserAgent $userAgent)
    {
        $device->setName($device::UNKNOWN);

        return (
            self::checkIpad($device, $userAgent) ||
            self::checkIphone($device, $userAgent) ||
            self::checkWindowsPhone($device, $userAgent) ||
            self::checkSamsungPhone($device, $userAgent) ||
            self::checkAndroidPhone($device, $userAgent)
        );
    }

    /**
     * Determine if the device is iPad.
     *
     * @param Device $device
     * @param UserAgent $userAgent
     * @return bool
     */
    private static function checkIpad(Device $device, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'ipad') !== false) {
            $device->setName(Device::IPAD);
            return true;
        }

        return false;
    }

    /**
     * Determine if the device is iPhone.
     *
     * @param Device $device
     * @param UserAgent $userAgent
     * @return bool
     */
    private static function checkIphone(Device $device, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'iphone;') !== false) {
            $device->setName(Device::IPHONE);
            return true;
        }

        return false;
    }

    /**
     * Determine if the device is Windows Phone.
     *
     * @param Device $device
     * @param UserAgent $userAgent
     * @return bool
     */
    private static function checkWindowsPhone(Device $device, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'Windows Phone') !== false) {
            if (preg_match('/Microsoft; (Lumia [^)]*)\)/', $userAgent->getUserAgentString(), $matches)) {
                $device->setName($matches[1]);
                return true;
            }

            $device->setName($device::WINDOWS_PHONE);
            return true;
        }
        return false;
    }

    /**
     * Determine if the device is Samsung Phone.
     *
     * @param Device $device
     * @param UserAgent $userAgent
     * @return bool
     */
    private static function checkSamsungPhone(Device $device, UserAgent $userAgent)
    {
            if (preg_match('/SAMSUNG SM-([^ ]*)/i', $userAgent->getUserAgentString(), $matches)) {
                $device->setName(str_ireplace('SAMSUNG', 'Samsung', $matches[0]));
                return true;
            }

        return false;
    }


    /**
     * Determine if the device is an Android Phone if Chrome is in use, and returns the Model-Code in $device setName
     *
     * @param Device $device
     * @param UserAgent $userAgent
     * @return bool
     */
    private static function checkAndroidPhone(Device $device, UserAgent $userAgent)
    {
        if (stripos($userAgent->getUserAgentString(), 'Android') !== false) {
            if (preg_match('/Linux; (Android [0-9\.]*); (?<modell>.*)\) AppleWebKit/', $userAgent->getUserAgentString(), $matches)) {
                $device->setName("Android_".preg_replace('/ Build.*$/', '', $matches["modell"]));
                return true;
            }

            $device->setName($device::ANDROID_PHONE);
            return true;
        }
        return false;
    }
}
