<?php

namespace Sinergi\BrowserDetector\Tests;

use PHPUnit_Framework_TestCase;
use Sinergi\BrowserDetector\Os;

class OsDetectorTest extends PHPUnit_Framework_TestCase
{
    public function testDetect()
    {
        $userAgentStringCollection = UserAgentStringMapper::map();
        foreach ($userAgentStringCollection as $userAgentString) {
            $os = new Os($userAgentString->getString());
            $this->assertSame($userAgentString->getOs(), $os->getName());
            $this->assertSame($userAgentString->getosVersion(), $os->getVersion());
        }
    }
}
