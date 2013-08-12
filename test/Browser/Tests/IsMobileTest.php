<?php

namespace Browser\Tests;

use Browser\Browser, Browser\OS, PHPUnit_Framework_TestCase;

class IsMobileTest extends PHPUnit_Framework_TestCase
{
    public function testSafariIpod()
    {
        Browser::setUserAgent("Mozilla/5.0 (iPod; CPU iPhone OS 6_1_3 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) CriOS/28.0.1500.16 Mobile/10B329 Safari/8536.25");
        $this->assertEquals(Browser::SAFARI, Browser::getBrowser());
        $this->assertEquals(OS::IOS, OS::getOS());
        $this->assertTrue(Browser::isMobile());
    }
}