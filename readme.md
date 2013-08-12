# PHP Browser

Copyright 2013, Gabriel Bull, dual licensed under GNU GENERAL PUBLIC LICENSE and MIT License.

This project is a fork of [Chris Schuld's browser.php](http://chrisschuld.com/projects/browser-php-detecting-a-users-browser-from-php/).

Detecting the user's browser, operating system and language from PHP. Because browser detection is not always reliable and evolves at all time, use with care and feel free to contribute.

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

### Example

```php
if (Browser\Browser::getBrowser() === Browser\Browser::IE && Browser\Browser::getVersion() < 8) {
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

### Example

```php
if (Browser\OS::getOS() == Browser\OS::IOS) {
	echo 'You are using an iOS device.';
}
```

## Language Detection

The Language class allow you to detect a user's language.

### Example

```php
if (Browser\Language::getLanguage() == 'de') {
	echo 'Get this website in german.';
}
```

