<?php
namespace Browser\Tests;

use Browser\Device;
use Browser\DeviceDetector;
use Browser\UserAgent;
use PHPUnit_Framework_TestCase;

class DeviceDetectorTest extends PHPUnit_Framework_TestCase
{
    public function testIpad()
    {
        $userAgentStringCollection = UserAgentStringMapper::map();
        $userAgentString = $userAgentStringCollection[1];

        $device = new Device();
        $deviceDetector = new DeviceDetector();
        $deviceDetector->setUserAgent(new UserAgent($userAgentString->getString()));
        $deviceDetector->detect($device);

        $this->assertEquals($userAgentString->getDevice(), $device->getName());
        $this->assertEquals($userAgentString->getDeviceVersion(), $device->getVersion());
    }
}
