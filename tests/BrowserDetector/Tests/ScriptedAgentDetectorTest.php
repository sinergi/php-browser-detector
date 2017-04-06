<?php

namespace Sinergi\BrowserDetector\Tests;

use PHPUnit_Framework_TestCase;
use Sinergi\BrowserDetector\ScriptedAgent;

class ScriptedAgentDetectorTest extends PHPUnit_Framework_TestCase
{
    public function testDetect()
    {
        $userAgentStringCollection = UserAgentStringMapper::map();
        foreach ($userAgentStringCollection as $userAgentString) {
            $agent = new ScriptedAgent($userAgentString->getString());
            $this->assertSame($userAgentString->getScriptedAgent(), $agent->getName());
            $this->assertSame($userAgentString->getScriptedAgentType(), $agent->getType());
        }
    }
}
