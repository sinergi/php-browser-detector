<?php

namespace Sinergi\BrowserDetector\Tests;

use PHPUnit_Framework_TestCase;
use Sinergi\BrowserDetector\UserAgent;

class UserAgentTest extends PHPUnit_Framework_TestCase
{
    public function testObject()
    {
        $userAgent = new UserAgent();
        $this->assertNull($userAgent->getUserAgentString());

        $userAgent = new UserAgent('my_agent_user_string');
        $this->assertEquals('my_agent_user_string', $userAgent->getUserAgentString());

        $userAgent->setUserAgentString('my_new_agent_user_string');
        $this->assertEquals('my_new_agent_user_string', $userAgent->getUserAgentString());
    }
}
