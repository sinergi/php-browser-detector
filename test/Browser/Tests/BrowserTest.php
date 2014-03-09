<?php
namespace Browser\Tests;

use Browser\Browser;
use Browser\Os;
use PHPUnit_Framework_TestCase;

class BrowserTest extends PHPUnit_Framework_TestCase
{
    public function testBlackBerry()
    {
        Browser::setUserAgent("BlackBerry8100/4.5.0.124 Profile/MIDP-2.0 Configuration/CLDC-1.1 VendorID/100");
        $this->assertEquals(Browser::BLACKBERRY, Browser::getBrowser());
        $this->assertEquals('4.5.0.124', Browser::getVersion());
    }

    public function testFirefox()
    {
        Browser::setUserAgent("Mozilla/5.0 (X11; Linux x86_64; rv:18.0) Gecko/20100101 Firefox/18.0");
        $this->assertEquals(Browser::FIREFOX, Browser::getBrowser());
        $this->assertEquals('18.0', Browser::getVersion());
    }

    public function testInternetExplorer11()
    {
        Browser::setUserAgent("Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko");
        $this->assertEquals(Browser::IE, Browser::getBrowser());
        $this->assertEquals('11.0', Browser::getVersion());
    }

    public function testSeaMonkey()
    {
        Browser::setUserAgent("Mozilla/5.0 (Windows; U; Windows NT 5.1; RW; rv:1.8.0.7) Gecko/20110321 MultiZilla/4.33.2.6a SeaMonkey/8.6.55");
        $this->assertEquals(Browser::SEAMONKEY, Browser::getBrowser());
        $this->assertEquals('8.6.55', Browser::getVersion());
    }

    public function testUnknown()
    {
        Browser::setUserAgent("");
        $this->assertEquals(Browser::UNKNOWN, Browser::getBrowser());
        $this->assertEquals(Browser::VERSION_UNKNOWN, Browser::getVersion());
    }

    public function testSafariIpod()
    {
        Browser::setUserAgent("Mozilla/5.0 (iPod; CPU iPhone OS 6_1_3 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) CriOS/28.0.1500.16 Mobile/10B329 Safari/8536.25");
        $this->assertEquals(Browser::SAFARI, Browser::getBrowser());
        $this->assertEquals(Os::IOS, Os::getOS());
        $this->assertTrue(Browser::isMobile());
    }
}