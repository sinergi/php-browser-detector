<?php

namespace Sinergi\BrowserDetector\Tests;

use PHPUnit\Framework\TestCase;
use Sinergi\BrowserDetector\Os;

class OsDetectorTest extends TestCase
{
    public function testDetect()
    {
        $userAgentStringCollection = UserAgentStringMapper::map();
        foreach ($userAgentStringCollection as $userAgentString) {
            $os = new Os($userAgentString->getString());
            $this->assertSame($userAgentString->getOs(), $os->getName());
            $this->assertSame($userAgentString->getOsVersion(), $os->getVersion());
        }
    }
}
