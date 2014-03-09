<?php

namespace Browser\Tests;

use Browser\Browser;
use Browser\Os;
use PHPUnit_Framework_TestCase;

class OsTest extends PHPUnit_Framework_TestCase
{
    public function testIOs()
    {
        Browser::setUserAgent("Mozilla/5.0 (iPad; CPU OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5355d Safari/8536.25");
        $this->assertEquals(Os::IOS, Os::getOS());
        $this->assertEquals('6.0', Os::getVersion());
    }

    public function testOsX()
    {
        Browser::setUserAgent("Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/536.26.17 (KHTML, like Gecko) Version/6.0.2 Safari/536.26.17");
        $this->assertEquals(Os::OSX, Os::getOS());
        $this->assertEquals('10.8.2', Os::getVersion());
    }

    public function testBlackberry()
    {
        Browser::setUserAgent("Mozilla/5.0 (BlackBerry; U; BlackBerry 9900; en) AppleWebKit/534.11+ (KHTML, like Gecko) Version/7.1.0.346 Mobile Safari/534.11+");
        $this->assertEquals(Os::BLACKBERRY, Os::getOS());
        $this->assertEquals(Os::VERSION_UNKNOWN, Os::getVersion());
    }

    public function testUnknown()
    {
        Browser::setUserAgent("");
        $this->assertEquals(Os::UNKNOWN, Os::getOS());
        $this->assertEquals(Os::VERSION_UNKNOWN, Os::getVersion());
    }
}
