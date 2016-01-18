<?php

namespace Sinergi\BrowserDetector\Tests;

use PHPUnit_Framework_TestCase;
use Sinergi\BrowserDetector\AcceptLanguage;

class AcceptLanguageTest extends PHPUnit_Framework_TestCase
{
    public function testObject()
    {
        $acceptLanguage = new AcceptLanguage();
        $this->assertNull($acceptLanguage->getAcceptLanguageString());

        $acceptLanguage = new AcceptLanguage('my_accept_language_string');
        $this->assertSame('my_accept_language_string', $acceptLanguage->getAcceptLanguageString());

        $acceptLanguage->setAcceptLanguageString('my_new_accept_language_string');
        $this->assertSame('my_new_accept_language_string', $acceptLanguage->getAcceptLanguageString());
    }
}
