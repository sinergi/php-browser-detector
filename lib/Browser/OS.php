<?php

namespace Browser;

/**
 * OS Detection
 *
 * @package browser
 */
class OS {	
	const UNKNOWN = 'unknown';
	const OSX = 'OS X';
	const IOS = 'iOS';
	const SYMBOS = 'SymbOS';
	const WINDOWS = 'Windows';
	const ANDROID = 'Android';
	const LINUX = 'Linux';
	const NOKIA = 'Nokia';
	const BLACKBERRY = 'BlackBerry';
	const FREEBSD = 'FreeBSD';
	const OPENBSD = 'OpenBSD';
	const NETBSD = 'NetBSD';
	const OPENSOLARIS = 'OpenSolaris';
	const SUNOS = 'SunOS';
	const OS2 = 'OS2';
	const BEOS = 'BeOS';
	
	const VERSION_UNKNOWN = 'unknown';

	private static $name;
	private static $version;
	
	/**
	 * Return the name of the OS.
	 * 
	 * @return  string
	 */
	public static function getOS() {
		if (!isset(self::$name)) {
			self::checkOS();
		}
		return self::$name;
	}
	
	/**
	 * Set the name of the OS.
	 * 
	 * @param   string  $value
	 * @return  void
	 */
	public static function setOS($value) {
		self::$name = $value;
	}
	
	/**
	 * Return the version of the OS.
	 * 
	 * @return  string
	 */
	public static function getVersion() {
		if (isset(self::$version)) {
			return self::$version;
		} else {
			self::checkOS();
			return self::$version;
		}
	}
	
	/**
	 * Set the version of the OS.
	 * 
	 * @param   string  $value
	 * @return  void
	 */
	public static function setVersion($value) {
		self::$version = $value;
	}
	
	/**
	 * Determine the user's operating system
	 * 
	 * @return  void
	 */
	private static function checkOS() {
		self::$name = self::UNKNOWN;
		self::$version = self::VERSION_UNKNOWN;
				
		return (
			// iOS before OS X
			self::checkIOS() ||
			self::checkOSX() || 
			self::checkSymbOS() || 
			self::checkWindows() || 
			self::checkFreeBSD() ||
			self::checkOpenBSD() || 
			self::checkNetBSD() || 
			self::checkOpenSolaris() || 
			self::checkSunOS() || 
			self::checkOS2() || 
			self::checkBeOS() || 
			// Android before Linux
			self::checkAndroid() ||
			self::checkLinux() ||
			self::checkNokia() ||
			self::checkBlackBerry() 
		);
	}
	
	/**
	 * Determine if the user's operating system is iOS.
	 * 
	 * @return   void
	 */
	private static function checkIOS() {
		if ( stripos(Browser::getUserAgent(), 'CPU OS') !== false || stripos(Browser::getUserAgent(), 'iPhone OS') !== false && stripos(Browser::getUserAgent(), 'OS X')) {
			self::$name = self::IOS;
			if (preg_match('/CPU( iPhone)? OS ([\d_]*)/i', Browser::getUserAgent(), $matches)) {
				self::$version = str_replace('_', '.', $matches[2]);
			}
			return true;
		}
		return false;
	}
	
	/**
	 * Determine if the user's operating system is OS X.
	 * 
	 * @return   void
	 */
	private static function checkOSX() {
		if (stripos(Browser::getUserAgent(), 'OS X') !== false) {
			self::$name = self::OSX;
			if (preg_match('/OS X ([\d_]*)/i', Browser::getUserAgent(), $matches)) {
				self::$version = str_replace('_', '.', $matches[1]);
			}
			return true;
		}
		return false;
	}
	
	
	/**
	 * Determine if the user's operating system is Windows.
	 * 
	 * @return   void
	 */
	private static function checkWindows() {
		if ( stripos(Browser::getUserAgent(), 'Windows NT') !== false) {
			self::$name = self::WINDOWS;
			// Windows version
			if (preg_match('/Windows NT ([\d\.]*)/i', Browser::getUserAgent(), $matches)) {
				switch(str_replace('_', '.', $matches[1])) {
					case '6.2':
						self::$version = '8';
						break;
					case '6.1':
						self::$version = '7';
						break;
					case '6.0':
						self::$version = 'Vista';
						break;
					case '5.2': case '5.1':
						self::$version = 'XP';
						break;
					case '5.01': case '5.0':
						self::$version = '2000';
						break;
					case '4.0':
						self::$version = 'NT 4.0';
						break;
				}
			}
			return true;
		}
		// Windows Me, Windows 98, Windows 95, Windows CE
		else if (preg_match('/(Windows 98; Win 9x 4\.90|Windows 98|Windows 95|Windows CE)/i', Browser::getUserAgent(), $matches)) {
			self::$name = self::WINDOWS;
			switch(strtolower($matches[0])) {
				case 'windows 98; win 9x 4.90':
					self::$version = 'Me';
					break;
				case 'windows 98':
					self::$version = '98';
					break;
				case 'windows 95':
					self::$version = '95';
					break;
				case 'windows ce':
					self::$version = 'CE';
					break;
			}
			return true;
		}

		return false;
	}

	/**
	 * Determine if the user's operating system is SymbOS.
	 * 
	 * @return   void
	 */
	private static function checkSymbOS() {
		if (stripos(Browser::getUserAgent(), 'SymbOS') !== false) {
			self::$name = self::SYMBOS;
			return true;
		}
		return false;
	}
		
	/**
	 * Determine if the user's operating system is Linux.
	 * 
	 * @return   void
	 */
	private static function checkLinux() {
		if (stripos(Browser::getUserAgent(), 'Linux') !== false) {
			self::$version = self::VERSION_UNKNOWN;
			self::$name = self::LINUX;
			return true;
		}
		return false;
	}
	
	/**
	 * Determine if the user's operating system is Nokia.
	 * 
	 * @return   void
	 */
	private static function checkNokia() {
		if (stripos(Browser::getUserAgent(), 'Nokia') !== false) {
			self::$version = self::VERSION_UNKNOWN;
			self::$name = self::NOKIA;
			return true;
		}
		return false;
	}
	
	/**
	 * Determine if the user's operating system is BlackBerry.
	 * 
	 * @return   void
	 */
	private static function checkBlackBerry() {
		if (stripos(Browser::getUserAgent(), 'BlackBerry') !== false) {
			self::$version = self::VERSION_UNKNOWN;
			self::$name = self::BLACKBERRY;
			return true;
		}
		return false;
	}
		
	/**
	 * Determine if the user's operating system is Android.
	 * 
	 * @return   void
	 */
	private static function checkAndroid() {
		if (stripos(Browser::getUserAgent(), 'Android') !== false) {
			if (preg_match('/Android ([\d\.]*)/i', Browser::getUserAgent(), $matches)) {
				self::$version = $matches[1];
			} else {
				self::$version = self::VERSION_UNKNOWN;
			}
			self::$name = self::ANDROID;
			return true;
		}
		return false;
	}
		
	/**
	 * Determine if the user's operating system is FreeBSD.
	 * 
	 * @return   void
	 */
	private static function checkFreeBSD() {
		if (stripos(Browser::getUserAgent(), 'FreeBSD') !== false) {
			self::$version = self::VERSION_UNKNOWN;
			self::$name = self::FREEBSD;
			return true;
		}
		return false;
	}
		
	/**
	 * Determine if the user's operating system is OpenBSD.
	 * 
	 * @return   void
	 */
	private static function checkOpenBSD() {
		if (stripos(Browser::getUserAgent(), 'OpenBSD') !== false) {
			self::$version = self::VERSION_UNKNOWN;
			self::$name = self::OPENBSD;
			return true;
		}
		return false;
	}
		
	/**
	 * Determine if the user's operating system is SunOS.
	 * 
	 * @return   void
	 */
	private static function checkSunOS() {
		if (stripos(Browser::getUserAgent(), 'SunOS') !== false) {
			self::$version = self::VERSION_UNKNOWN;
			self::$name = self::SUNOS;
			return true;
		}
		return false;
	}
	
	/**
	 * Determine if the user's operating system is NetBSD.
	 * 
	 * @return   void
	 */
	private static function checkNetBSD() {
		if (stripos(Browser::getUserAgent(), 'NetBSD') !== false) {
			self::$version = self::VERSION_UNKNOWN;
			self::$name = self::NETBSD;
			return true;
		}
		return false;
	}
		
	/**
	 * Determine if the user's operating system is OpenSolaris.
	 * 
	 * @return   void
	 */
	private static function checkOpenSolaris() {
		if (stripos(Browser::getUserAgent(), 'OpenSolaris') !== false) {
			self::$version = self::VERSION_UNKNOWN;
			self::$name = self::OPENSOLARIS;
			return true;
		}
		return false;
	}
		
	/**
	 * Determine if the user's operating system is OS2.
	 * 
	 * @return   void
	 */
	private static function checkOS2() {
		if (stripos(Browser::getUserAgent(), 'OS\/2') !== false) {
			self::$version = self::VERSION_UNKNOWN;
			self::$name = self::OS2;
			return true;
		}
		return false;
	}
	
	/**
	 * Determine if the user's operating system is BeOS.
	 * 
	 * @return   void
	 */
	private static function checkBeOS() {
		if (stripos(Browser::getUserAgent(), 'BeOS') !== false) {
			self::$version = self::VERSION_UNKNOWN;
			self::$name = self::BEOS;
			return true;
		}
		return false;
	}
}
