PHP Browser
===========

[![Build Status](https://img.shields.io/travis/gabrielbull/php-browser/master.svg?style=flat)](https://travis-ci.org/gabrielbull/php-browser)
[![StyleCI](https://styleci.io/repos/3752453/shield)](https://styleci.io/repos/3752453)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/gabrielbull/php-browser/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/gabrielbull/php-browser/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/gabrielbull/php-browser/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/gabrielbull/php-browser/?branch=master)
[![Latest Stable Version](http://img.shields.io/packagist/v/gabrielbull/browser.svg?style=flat)](https://packagist.org/packages/gabrielbull/browser)
[![Total Downloads](https://img.shields.io/packagist/dt/gabrielbull/browser.svg?style=flat)](https://packagist.org/packages/gabrielbull/browser)
[![License](https://img.shields.io/packagist/l/gabrielbull/browser.svg?style=flat)](https://packagist.org/packages/gabrielbull/browser)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/91aa3b4f-c58a-46f9-8ac6-c17658084034/mini.png)](https://insight.sensiolabs.com/projects/91aa3b4f-c58a-46f9-8ac6-c17658084034)

Detecting the user's browser, operating system, device and language from PHP. Because browser detection is not always
reliable and evolves at all time, use with care and feel free to contribute.

## Requirements

This library uses PHP 5.3+.

## Install
    
It is recommended that you install the PHP Browser library [through composer](http://getcomposer.org). To do so, add the following lines to your composer.json file.

```JSON
{
    "require": {
        "gabrielbull/browser": "^4.0"
    }
}
```

## Browser Detection

The Browser class allow you to detect a user's browser and version.

### Browsers Detected

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
 * Navigator
 * GoogleBot
 * Yahoo! Slurp
 * W3C Validator
 * BlackBerry
 * IceCat
 * Nokia S60 OSS Browser
 * Nokia Browser
 * MSN Browser
 * MSN Bot
 * Netscape Navigator
 * Galeon
 * NetPositive
 * Phoenix
 * SeaMonkey
 * Yandex Browser

### Usage

```php
use Browser\Browser;

$browser = new Browser;
if ($browser->getName() === $browser::IE && $browser->getVersion() < 8) {
	echo 'Please upgrade your browser.';
}
```

## OS Detection

The OS class allow you to detect a user's operating system and version.

### OS Detected

 * Windows
 * OS X
 * iOS
 * Android
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
use Browser\Os;

$os = new Os;
if ($os->getName() === $os::IOS) {
	echo 'You are using an iOS device.';
}
```

## Device Detection

The Device class allow you to detect a user's device.

### Device Detected

 * iPad
 * iPhone

### Usage

```php
use Browser\Device;

$device = new Device;
if ($device->getName() === $device::IPAD) {
	echo 'You are using an iPad.';
}
```

## Language Detection

The Language class allow you to detect a user's language.

### Usage

```php
use Browser\Language;

$language = new Language;
if ($language->getLanguage() === 'de') {
	echo 'Get this website in german.';
}
```

## License

PHP Browser is licensed under [The MIT License (MIT)](LICENSE).
