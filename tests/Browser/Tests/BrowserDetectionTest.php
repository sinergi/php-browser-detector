<?php
namespace Browser\Tests;

use Browser\Browser;
use PHPUnit_Framework_TestCase;

class BrowserDetectionTest extends PHPUnit_Framework_TestCase
{
    public function testBlackBerry()
    {
        $userAgentStringCollection = UserAgentStringMapper::map();
        foreach ($userAgentStringCollection as $userAgentString) {
            $browser = new Browser($userAgentString->getString());
            $this->assertEquals($userAgentString->getBrowser(), $browser->getName(), 'Failed asserting that ' . $browser->getName() . ' is equal to ' . $userAgentString->getBrowser());
            $this->assertEquals($userAgentString->getVersion(), $browser->getVersion(), 'Failed asserting that ' . $browser->getVersion() . ' is equal to ' . $userAgentString->getVersion());
        }
    }
}