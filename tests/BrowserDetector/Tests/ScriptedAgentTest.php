<?php

namespace Sinergi\BrowserDetector\Tests;

use PHPUnit_Framework_TestCase;
use Sinergi\BrowserDetector\ScriptedAgent;

class ScriptedAgentTest extends PHPUnit_Framework_TestCase
{
    public function testDetect()
    {
        $agent = new ScriptedAgent();
        $this->assertSame(ScriptedAgent::UNKNOWN, $agent->getName());
    }
}
