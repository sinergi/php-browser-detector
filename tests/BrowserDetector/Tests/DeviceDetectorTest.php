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
            $device = new Device($userAgentString->getString());
            $this->assertSame($userAgentString->getDevice(), $device->getName());
        }
    }
}
