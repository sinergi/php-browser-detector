PHP Browser
===========

[![Build Status](https://img.shields.io/travis/gabrielbull/php-browser/master.svg?style=flat)](https://travis-ci.org/gabrielbull/php-browser)
[![Latest Stable Version](http://img.shields.io/packagist/v/gabrielbull/php-browser.svg?style=flat)](https://packagist.org/packages/gabrielbull/php-browser)
[![Total Downloads](https://img.shields.io/packagist/dt/gabrielbull/php-browser.svg?style=flat)](https://packagist.org/packages/gabrielbull/php-browser)
[![License](https://img.shields.io/packagist/l/gabrielbull/php-browser.svg?style=flat)](https://packagist.org/packages/gabrielbull/php-browser)

Detecting the user's browser, operating system and language from PHP. Because browser detection is not always reliable and evolves at all time, use with care and feel free to contribute.

## Requirements

This library uses PHP 5.3+.

## Install

It is recommended that you install the PHP Browser library [through composer](http://getcomposer.org). To do so, add the following lines to your composer.json file.

```JSON
{
    "require": {
        "gabrielbull/browser": "dev-master"
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

### Usage

```php
$browser = new Browser\Browser;
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
$os = new Browser\Os;
if ($os->getName() == $os::IOS) {
	echo 'You are using an iOS device.';
}
```

## Language Detection

The Language class allow you to detect a user's language.

### Usage

```php
$language = new Browser\Language;
if ($language->getLanguage() == 'de') {
	echo 'Get this website in german.';
}
```
