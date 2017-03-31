Browser Detector
================

[![Build Status](https://travis-ci.org/sinergi/php-browser-detector.svg?branch=master)](https://travis-ci.org/sinergi/php-browser-detector)
[![StyleCI](https://styleci.io/repos/3752453/shield?style=flat)](https://styleci.io/repos/3752453)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sinergi/php-browser-detector/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sinergi/php-browser-detector/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/sinergi/php-browser-detector/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/sinergi/php-browser-detector/?branch=master)
[![Latest Stable Version](http://img.shields.io/packagist/v/sinergi/browser-detector.svg?style=flat)](https://packagist.org/packages/sinergi/browser-detector)
[![Total Downloads](https://img.shields.io/packagist/dt/sinergi/browser-detector.svg?style=flat)](https://packagist.org/packages/sinergi/browser-detector)
[![License](https://img.shields.io/packagist/l/sinergi/browser-detector.svg?style=flat)](https://packagist.org/packages/sinergi/browser-detector)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/1865a02e-284c-428a-a2b4-091c997e5935/mini.png)](https://insight.sensiolabs.com/projects/1865a02e-284c-428a-a2b4-091c997e5935)
[![Join the chat at https://gitter.im/sinergi/php-browser-detector](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/sinergi/php-browser-detector?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

Detecting the user's browser, operating system, device and language from PHP. Because browser detection is not always
reliable and evolves at all time, use with care and feel free to contribute.

## Requirements

This library uses PHP 5.3+.

## Install

It is recommended that you install the PHP Browser library [through composer](http://getcomposer.org). To do so, run the following command:

```sh
composer require sinergi/browser-detector
```

## Browser Detection

The Browser class allows you to detect a user's browser and version.

### Browsers Detected

 * Vivaldi
 * Opera
 * Opera Mini
 * WebTV
 * Internet Explorer
 * Pocket Internet Explorer
 * Microsoft Edge
 * Konqueror
 * iCab
 * OmniWeb
 * Firebird
 * Firefox
 * Iceweasel
 * Shiretoko
 * Mozilla
 * Amaya
 * Lynx
 * Safari
 * Chrome
 * Android Navigator
 * UC Browser
 * BlackBerry
 * IceCat
 * Nokia S60 OSS Browser
 * Nokia Browser
 * MSN Browser
 * Netscape Navigator
 * Galeon
 * NetPositive
 * Phoenix
 * SeaMonkey
 * Yandex Browser
 * Comodo Dragon
 * Samsung Browser

### Usage

```php
use Sinergi\BrowserDetector\Browser;

$browser = new Browser();  

//You can also provide a userAgent string if you don't wish to detec the current browser
//$browser = new Browser("Mozilla/5.0 (Windows NT 10.0; WOW64; rv:40.0) Gecko/20100101 Firefox/40.0");

if ($browser->getName() === Browser::IE && $browser->getVersion() < 11) {
    echo 'Please upgrade your browser.';
}
```

#### Compatibility Mode

Detect if Internet Explorer is in Compatibility Mode and send the correct header to have the browser render the page in its standard mode. This must be called before any output is sent to the browser.

```php
use Sinergi\BrowserDetector\Browser;

$browser = new Browser();

if ($browser->getName() === Browser::IE && $browser->isCompatibilityMode()) {
    $browser->endCompatibilityMode();
}
```

## Scripted Agent Detection

The ScriptedAgent class allows you to detect scripted agents (bots, spiders, tools)

### Scripted Agents Detected

Spiders

 * GoogleBot
 * Baidu
 * Bing
 * MSN
 * Yahoo! Slurp
 * W3C Spiders
 * Yandex
 * Apple
 * Paper.li
 * Majestic12
 * Livelap
 * Scoop.it
 * Who.is
 * Proximic

Web Surveys

 * Ahrefs
 * MetaURI
 * Netcraft
 * Browsershots
 * MageReport
 * SocialRank.io
 * Gluten Free
 * Ubermetrics
 * Verisign IPS-Agent

Exploits

 * ShellShock

Web Preview bots

 * ICQ
 * Google Web
 * Facebook
 * Bing
 * Twitter
 * Skype

Tools

 * wkHTMLtoPDF
 * W3C Validator
 * WebDAV
 * TLSProbe
 * Wget
 * Zgrab

Generic

 * Google Favicon
 * Curl
 * Python
 * GoLang
 * Perl
 * Java

Ad bots

 * Google
 * Microsoft
 * AdBeat

### Usage

```php
use Sinergi\BrowserDetector\Browser;

$browser = new Browser();

$scriptedAgent = $browser->detectScriptedAgent();
if ($scriptedAgent!==false)
{
    die("Detected ".$scriptedAgent->getName()." which is a ".$scriptedAgent->getType().". Info: ".$scriptedAgent->getInfoURL());
}
```

## OS Detection

The OS class allows you to detect a user's operating system and version.

### OS Detected

 * Windows
 * Windows Phone
 * OS X
 * iOS
 * Android
 * Chrome OS
 * Linux
 * SymbOS
 * Nokia
 * BlackBerry
 * FreeBSD
 * OpenBSD
 * NetBSD
 * OpenSolaris
 * SunOS
 * OS2
 * BeOS

### Usage

```php
use Sinergi\BrowserDetector\Os;

$os = new Os();

if ($os->getName() === Os::IOS) {
    echo 'You are using an iOS device.';
}
```

## Device Detection

The Device class allows you to detect a user's device.

### Device Detected

 * iPad
 * iPhone
 * Windows Phone
 * Lumia

### Usage

```php
use Sinergi\BrowserDetector\Device;

$device = new Device();

if ($device->getName() === Device::IPAD) {
    echo 'You are using an iPad.';
}
```

## Language Detection

The Language class allows you to detect a user's language.

### Usage

```php
use Sinergi\BrowserDetector\Language;

$language = new Language();

if ($language->getLanguage() === 'de') {
    echo 'Get this website in german.';
}
```

## License

PHP Browser is licensed under [The MIT License (MIT)](LICENSE).
