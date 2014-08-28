<?php
namespace Browser\Tests;

use Browser\Browser;
use PHPUnit_Framework_TestCase;

class BrowserDetectorTest extends PHPUnit_Framework_TestCase
{
    public function testBlackBerry()
    {
        $userAgentStringCollection = UserAgentStringMapper::map();
        foreach ($userAgentStringCollection as $userAgentString) {
            $browser = new Browser($userAgentString->getString());
            $this->assertEquals($userAgentString->getBrowser(), $browser->getName());
            $this->assertEquals($userAgentString->getBrowserVersion(), $browser->getVersion());
        }
    }
}
