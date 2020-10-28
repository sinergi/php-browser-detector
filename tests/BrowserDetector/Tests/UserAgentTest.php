<?php

namespace Sinergi\BrowserDetector\Tests;

use PHPUnit\Framework\TestCase;
use Sinergi\BrowserDetector\UserAgent;

class UserAgentTest extends TestCase
{
    public function testObject()
    {
        $userAgent = new UserAgent();
        $this->assertEmpty($userAgent->getUserAgentString());

        $userAgent = new UserAgent('my_agent_user_string');
        $this->assertSame('my_agent_user_string', $userAgent->getUserAgentString());

        $userAgent->setUserAgentString('my_new_agent_user_string');
        $this->assertSame('my_new_agent_user_string', $userAgent->getUserAgentString());
    }
}
