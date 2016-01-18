<?php

namespace Sinergi\BrowserDetector\Tests;

use PHPUnit_Framework_TestCase;
use Sinergi\BrowserDetector\Device;

class DeviceTest extends PHPUnit_Framework_TestCase
{
    public function testDevice()
    {
        $device = new Device();
        $this->assertSame(Device::UNKNOWN, $device->getName());
    }
}
