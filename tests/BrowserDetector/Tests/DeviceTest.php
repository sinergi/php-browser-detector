<?php

namespace Sinergi\BrowserDetector\Tests;

use PHPUnit\Framework\TestCase;
use Sinergi\BrowserDetector\Device;

class DeviceTest extends TestCase
{
    public function testDevice()
    {
        $device = new Device();
        $this->assertSame(Device::UNKNOWN, $device->getName());
    }
}
