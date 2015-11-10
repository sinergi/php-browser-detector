Browser Detector
================

[![Build Status](https://travis-ci.org/sinergi/php-browser-detector.svg?branch=master)](https://travis-ci.org/sinergi/php-browser-detector)
[![StyleCI](https://styleci.io/repos/3752453/shield)](https://styleci.io/repos/3752453)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sinergi/php-browser-detector/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sinergi/php-browser-detector/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/sinergi/php-browser-detector/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/sinergi/php-browser-detector/?branch=master)
[![Latest Stable Version](http://img.shields.io/packagist/v/sinergi/browser-detector.svg?style=flat)](https://packagist.org/packages/gabrielbull/browser)
[![Total Downloads](https://img.shields.io/packagist/dt/gabrielbull/browser.svg?style=flat)](https://packagist.org/packages/gabrielbull/browser)
[![License](https://img.shields.io/packagist/l/sinergi/browser-detector.svg?style=flat)](https://packagist.org/packages/gabrielbull/browser)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/673d60ca-a836-47f5-ab32-44f406ba6896/mini.png)](https://insight.sensiolabs.com/projects/673d60ca-a836-47f5-ab32-44f406ba6896)
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

The Browser class allow you to detect a user's browser and version.

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
use Sinergi\BrowserDetector\Browser;

$browser = new Browser();

if ($browser->getName() === Browser::IE && $browser->getVersion() < 11) {
    echo 'Please upgrade your browser.';
}
```

## OS Detection

The OS class allow you to detect a user's operating system and version.

### OS Detected

 * Windows
 * Windows Phone
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
use Sinergi\BrowserDetector\Os;

$os = new Os();

if ($os->getName() === Os::IOS) {
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
use Sinergi\BrowserDetector\Device;

$device = new Device();

if ($device->getName() === Device::IPAD) {
    echo 'You are using an iPad.';
}
```

## Language Detection

The Language class allow you to detect a user's language.

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
