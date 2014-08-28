<?php
namespace Browser\Tests;

use Browser\Browser;
use PHPUnit_Framework_TestCase;

class BrowserTest extends PHPUnit_Framework_TestCase
{
    public function testBlackBerry()
    {
        $browser = new Browser("BlackBerry8100/4.5.0.124 Profile/MIDP-2.0 Configuration/CLDC-1.1 VendorID/100");
        $this->assertEquals(Browser::BLACKBERRY, $browser->getName());
        $this->assertEquals('4.5.0.124', $browser->getVersion());
    }

    public function testFirefox()
    {
        $browser = new Browser("Mozilla/5.0 (X11; Linux x86_64; rv:18.0) Gecko/20100101 Firefox/18.0");
        $this->assertEquals(Browser::FIREFOX, $browser->getName());
        $this->assertEquals('18.0', $browser->getVersion());
    }

    public function testInternetExplorer11()
    {
        $browser = new Browser("Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko");
        $this->assertEquals(Browser::IE, $browser->getName());
        $this->assertEquals('11.0', $browser->getVersion());
    }

    public function testSeaMonkey()
    {
        $browser = new Browser("Mozilla/5.0 (Windows; U; Windows NT 5.1; RW; rv:1.8.0.7) Gecko/20110321 MultiZilla/4.33.2.6a SeaMonkey/8.6.55");
        $this->assertEquals(Browser::SEAMONKEY, $browser->getName());
        $this->assertEquals('8.6.55', $browser->getVersion());
    }

    public function testUnknown()
    {
        $browser = new Browser();
        $this->assertEquals(Browser::UNKNOWN, $browser->getName());
        $this->assertEquals(Browser::VERSION_UNKNOWN, $browser->getVersion());
    }
}
