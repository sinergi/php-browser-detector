<?php

namespace Sinergi\BrowserDetector\Tests;

use PHPUnit\Framework\TestCase;
use Sinergi\BrowserDetector\AcceptLanguage;
use Sinergi\BrowserDetector\InvalidArgumentException;
use Sinergi\BrowserDetector\Language;

class LanguageTest extends TestCase
{
    /**
     * @var Language
     */
    private $language;

    public function setUp(): void
    {
        $httpAcceptLanguage = 'fr-CA,fr;q=0.8,en-CA;q=0.6,en;q=0.4,en-US;q=0.2';
        $this->language = new Language($httpAcceptLanguage);
    }

    public function testGetLanguage()
    {
        $this->assertSame('fr', $this->language->getLanguage());
    }

    public function testGetLanguages()
    {
        $this->assertGreaterThan(0, count($this->language->getLanguages()));
    }

    public function testGetLanguageLocal()
    {
        $this->assertSame('fr-CA', $this->language->getLanguageLocale());
    }

    public function testConstructor()
    {
        $acceptLanguage = new AcceptLanguage('my_accept_language_string');
        $language = new Language($acceptLanguage);

        $this->assertInstanceOf("\\Sinergi\\BrowserDetector\\AcceptLanguage", $acceptLanguage);
        $this->assertInstanceOf("\\Sinergi\\BrowserDetector\\Language", $language);
    }

    public function testConstructorException()
    {
        $this->expectException(InvalidArgumentException::class);
        new Language(1);
    }

    public function testGetLanguageLocale()
    {
        $language = new Language('ru,en-us;q=0.5,en;q=0.3');
        $this->assertSame('ru', $language->getLanguageLocale());
    }
}
