<?php

namespace Browser\Tests;

use Browser, PHPUnit_Framework_TestCase;

class LanguageTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $httpAcceptLanguage = "fr-CA,fr;q=0.8,en-CA;q=0.6,en;q=0.4,en-US;q=0.2";
        Browser\Language::setAcceptLanguage($httpAcceptLanguage);
    }

    public function testGetLanguage()
    {
        $language = Browser\Language::getLanguage();
        $this->assertEquals('fr', $language);
    }

    public function testGetLanguages()
    {
        $languages = Browser\Language::getLanguages();
        $this->assertGreaterThan(0, sizeof($languages));
    }

    public function testGetLanguageLocal()
    {
        $language = Browser\Language::getLanguageLocale();
        $this->assertEquals('fr-CA', $language);
    }
}