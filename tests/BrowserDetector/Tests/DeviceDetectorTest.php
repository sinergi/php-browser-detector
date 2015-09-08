<?php

namespace Sinergi\BrowserDetector\Tests;

use PHPUnit_Framework_TestCase;
use Sinergi\BrowserDetector\Device;
use Sinergi\BrowserDetector\DeviceDetector;
use Sinergi\BrowserDetector\UserAgent;

class DeviceDetectorTest extends PHPUnit_Framework_TestCase
{
    public function testDeviceDetect()
    {
        $userAgentStringCollection = UserAgentStringMapper::map();

        foreach ($userAgentStringCollection as $userAgentString) {
            $device = new Device();
            $deviceDetector = new DeviceDetector();
            $deviceDetector->setUserAgent(new UserAgent($userAgentString->getString()));
            $deviceDetector->detect($device);

            $this->assertEquals($userAgentString->getDevice(), $device->getName());
            $this->assertEquals($userAgentString->getDeviceVersion(), $device->getVersion());
        }
    }

    public function testGetDevice()
    {
        $device = new Device();
        $deviceDetector = new DeviceDetector();

        $deviceDetector->setUserAgent(new UserAgent('Mozilla/5.0 (Macintosh; Intel Mac OS X 10.10; rv:35.0) Gecko/20100101 Firefox/35.0'));

        $deviceDetector->detect($device);

        $this->assertInstanceOf("\\Sinergi\\BrowserDetector\\Device", $deviceDetector->getDevice());
    }

    public function testSetDevice()
    {
        $device = new Device();
        $deviceDetector = new DeviceDetector();

        $deviceDetector->setDevice($device);

        $this->assertAttributeEquals($device, 'device', $deviceDetector);
    }

    public function testSetUserAgent()
    {
        $userAgent = new UserAgent('Mozilla/5.0 (Macintosh; Intel Mac OS X 10.10; rv:35.0) Gecko/20100101 Firefox/35.0');
        $deviceDetector = new DeviceDetector();

        $deviceDetector->setUserAgent($userAgent);

        $this->assertAttributeEquals($userAgent, 'userAgent', $deviceDetector);
    }

    public function testGetUserAgent()
    {
        $userAgent = new UserAgent('Mozilla/5.0 (Macintosh; Intel Mac OS X 10.10; rv:35.0) Gecko/20100101 Firefox/35.0');
        $deviceDetector = new DeviceDetector();

        $deviceDetector->setUserAgent($userAgent);

        $this->assertEquals($userAgent, $deviceDetector->getUserAgent());
    }
}
