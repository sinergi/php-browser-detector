<?php

namespace Sinergi\BrowserDetector\Tests;

use PHPUnit\Framework\TestCase;
use Sinergi\BrowserDetector\Device;

class DeviceDetectorTest extends TestCase
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
