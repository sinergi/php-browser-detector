<?php

namespace Sinergi\BrowserDetector\Tests;

use PHPUnit_Framework_TestCase;
use Sinergi\BrowserDetector\Browser;

// todo: move to browser detector tests
class BrowserTest extends PHPUnit_Framework_TestCase
{
    public function testBlackBerry()
    {
        $browser = new Browser('BlackBerry8100/4.5.0.124 Profile/MIDP-2.0 Configuration/CLDC-1.1 VendorID/100');
        $this->assertSame(Browser::BLACKBERRY, $browser->getName());
        $this->assertSame('4.5.0.124', $browser->getVersion());
    }

    public function testFirefox()
    {
        $browser = new Browser('Mozilla/5.0 (X11; Linux x86_64; rv:18.0) Gecko/20100101 Firefox/18.0');
        $this->assertSame(Browser::FIREFOX, $browser->getName());
        $this->assertSame('18.0', $browser->getVersion());
    }

    public function testInternetExplorer11()
    {
        $browser = new Browser('Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko');
        $this->assertSame(Browser::IE, $browser->getName());
        $this->assertSame('11.0', $browser->getVersion());

        $browser = new Browser('Mozilla/5.0 (MSIE 9.0; Windows NT 6.3; WOW64; Trident/7.0; rv:11.0) like Gecko');
        $this->assertSame(Browser::IE, $browser->getName());
        $this->assertSame('11.0', $browser->getVersion());

        $browser = new Browser('Mozilla/5.0 (MSIE 9.0; Windows NT 6.3; WOW64; Trident/7.0;) like Gecko');
        $this->assertSame(Browser::IE, $browser->getName());
        $this->assertSame('9.0', $browser->getVersion());
    }

    public function testSeaMonkey()
    {
        $browser = new Browser('Mozilla/5.0 (Windows; U; Windows NT 5.1; RW; rv:1.8.0.7) Gecko/20110321 MultiZilla/4.33.2.6a SeaMonkey/8.6.55');
        $this->assertSame(Browser::SEAMONKEY, $browser->getName());
        $this->assertSame('8.6.55', $browser->getVersion());
    }

    public function testUnknown()
    {
        $browser = new Browser();
        $this->assertSame(Browser::UNKNOWN, $browser->getName());
        $this->assertSame(Browser::VERSION_UNKNOWN, $browser->getVersion());
    }

    public function testOpera()
    {
        $browser = new Browser('Opera/9.80 (Windows NT 6.0) Presto/2.12.388 Version/12.14');
        $this->assertSame(Browser::OPERA, $browser->getName());
        $this->assertSame('12.14', $browser->getVersion());

        $browser = new Browser('Mozilla/5.0 (SunOS 5.8 sun4u; U) Opera 5.0 [en]');
        $this->assertSame(Browser::OPERA, $browser->getName());
        $this->assertSame('5.0', $browser->getVersion());
    }
}
