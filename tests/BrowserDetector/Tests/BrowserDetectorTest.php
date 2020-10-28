<?php

namespace Sinergi\BrowserDetector\Tests;

use PHPUnit\Framework\TestCase;
use Sinergi\BrowserDetector\Browser;

class BrowserDetectorTest extends TestCase
{
    public function testDetect()
    {
        $userAgentStringCollection = UserAgentStringMapper::map();
        foreach ($userAgentStringCollection as $userAgentString) {
            $browser = new Browser($userAgentString->getString());
            $this->assertSame($userAgentString->getBrowser(), $browser->getName());
            $this->assertSame($userAgentString->getBrowserVersion(), $browser->getVersion());
        }
    }
}
