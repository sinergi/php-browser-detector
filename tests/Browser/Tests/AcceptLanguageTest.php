<?php

namespace Browser\Tests;

use Browser\AcceptLanguage;
use PHPUnit_Framework_TestCase;

class AcceptLanguageTest extends PHPUnit_Framework_TestCase
{
    public function testObject()
    {
        $acceptLanguage = new AcceptLanguage();
        $this->assertNull($acceptLanguage->getAcceptLanguageString());

        $acceptLanguage = new AcceptLanguage('my_accept_language_string');
        $this->assertEquals('my_accept_language_string', $acceptLanguage->getAcceptLanguageString());

        $acceptLanguage->setAcceptLanguageString('my_new_accept_language_string');
        $this->assertEquals('my_new_accept_language_string', $acceptLanguage->getAcceptLanguageString());
    }
}
