<?php

namespace Browser\Tests;

use Browser\AcceptLanguage;
use Browser\Language;
use PHPUnit_Framework_TestCase;

class LanguageTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Language
     */
    private $language;

    public function setUp()
    {
        $httpAcceptLanguage = 'fr-CA,fr;q=0.8,en-CA;q=0.6,en;q=0.4,en-US;q=0.2';
        $this->language = new Language($httpAcceptLanguage);
    }

    public function testGetLanguage()
    {
        $this->assertEquals('fr', $this->language->getLanguage());
    }

    public function testGetLanguages()
    {
        $this->assertGreaterThan(0, count($this->language->getLanguages()));
    }

    public function testGetLanguageLocal()
    {
        $this->assertEquals('fr-CA', $this->language->getLanguageLocale());
    }

    public function testConstructor()
    {
        $acceptLanguage = new AcceptLanguage('my_accept_language_string');
        $language = new Language($acceptLanguage);

        $this->assertInstanceOf('\Browser\AcceptLanguage', $acceptLanguage);
        $this->assertInstanceOf('\Browser\Language', $language);
    }

    /**
     * @expectedException \Browser\InvalidArgumentException
     */
    public function testConstructorException()
    {
        $language = new Language(1);
    }

    public function testGetLanguageLocale()
    {
        $language = new Language('ru,en-us;q=0.5,en;q=0.3');
        $this->assertEquals('ru', $language->getLanguageLocale());
    }
}
