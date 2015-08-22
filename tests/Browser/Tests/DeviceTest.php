<?php

namespace Browser\Tests;

use Browser\Device;
use PHPUnit_Framework_TestCase;

class DeviceTest extends PHPUnit_Framework_TestCase
{
    public function testDevice()
    {
        $device = new Device();
        $this->assertEquals(Device::UNKNOWN, $device->getName());
        $this->assertEquals(Device::UNKNOWN_VERSION, $device->getVersion());
    }
}
