<?php
namespace Browser\Tests;

use Browser\Os;
use PHPUnit_Framework_TestCase;

// todo: move to os detector tests
class OsTest extends PHPUnit_Framework_TestCase
{
    public function testIOs()
    {
        $os = new Os("Mozilla/5.0 (iPad; CPU OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5355d Safari/8536.25");
        $this->assertEquals(Os::IOS, $os->getName());
        $this->assertEquals('6.0', $os->getVersion());
    }

    public function testOsX()
    {
        $os = new Os("Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/536.26.17 (KHTML, like Gecko) Version/6.0.2 Safari/536.26.17");
        $this->assertEquals(Os::OSX, $os->getName());
        $this->assertEquals('10.8.2', $os->getVersion());
    }

    public function testOsX1010()
    {
        $os = new Os("Mozilla/5.0 (Macintosh; Intel Mac OS X 10.10; rv:34.0) Gecko/20100101 Firefox/34.0");
        $this->assertEquals(Os::OSX, $os->getName());
        $this->assertEquals('10.10', $os->getVersion());
    }

    public function testBlackberry()
    {
        $os = new Os("Mozilla/5.0 (BlackBerry; U; BlackBerry 9900; en) AppleWebKit/534.11+ (KHTML, like Gecko) Version/7.1.0.346 Mobile Safari/534.11+");
        $this->assertEquals(Os::BLACKBERRY, $os->getName());
        $this->assertEquals(Os::VERSION_UNKNOWN, $os->getVersion());
    }

    public function testIsMobile()
    {
        $os = new Os("Mozilla/5.0 (iPod; CPU iPhone OS 6_1_3 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) CriOS/28.0.1500.16 Mobile/10B329 Safari/8536.25");
        $this->assertTrue($os->isMobile());
    }

    public function testWindows() {
        $os = new Os("Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0)");
        $this->assertEquals(Os::WINDOWS, $os->getName());
        $this->assertEquals('7', $os->getVersion());

    }

    public function testUnknown()
    {
        $os = new Os("");
        $this->assertEquals(Os::UNKNOWN, $os->getName());
        $this->assertEquals(Os::VERSION_UNKNOWN, $os->getVersion());
    }
}
