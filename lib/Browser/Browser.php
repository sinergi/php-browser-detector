<?php

namespace Browser;

/**
 * Browser Detection
 *
 * @package browser
 */
class Browser {
	private static $userAgent;
	private static $name;
	private static $version;
	private static $isRobot;
	private static $isMobile;

	const UNKNOWN = 'unknown';
	const OPERA = 'Opera';
	const OPERA_MINI = 'Opera Mini';
	const WEBTV = 'WebTV';
	const IE = 'Internet Explorer';
	const POCKET_IE = 'Pocket Internet Explorer';
	const KONQUEROR = 'Konqueror';
	const ICAB = 'iCab';
	const OMNIWEB = 'OmniWeb';
	const FIREBIRD = 'Firebird';
	const FIREFOX = 'Firefox';
	const SEAMONKEY = 'SeaMonkey';
	const ICEWEASEL = 'Iceweasel';
	const SHIRETOKO = 'Shiretoko';
	const MOZILLA = 'Mozilla';
	const AMAYA = 'Amaya';
	const LYNX = 'Lynx';
	const SAFARI = 'Safari';
	const IPHONE = 'iPhone';
	const IPOD = 'iPod';
	const IPAD = 'iPad';
	const CHROME = 'Chrome';
	const NAVIGATOR = 'Navigator';
	const GOOGLEBOT = 'GoogleBot';
	const SLURP = 'Yahoo! Slurp';
	const W3CVALIDATOR = 'W3C Validator';
	const BLACKBERRY = 'BlackBerry';
	const ICECAT = 'IceCat';
	const NOKIA_S60 = 'Nokia S60 OSS Browser';
	const NOKIA = 'Nokia Browser';
	const MSN = 'MSN Browser';
	const MSNBOT = 'MSN Bot';
	const NETSCAPE_NAVIGATOR = 'Netscape Navigator';
	const GALEON = 'Galeon';
	const NETPOSITIVE = 'NetPositive';
	const PHOENIX = 'Phoenix';
	
	const VERSION_UNKNOWN = 'unknown';
	
	/**
	 * Get the user agent value in use to determine the browser.
	 *
	 * @return  string
	 */
	public static function getUserAgent() {
		if (!isset(self::$userAgent)) {
			self::setUserAgent(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "");
		}
		return self::$userAgent;
	}

	/**
	 * Set the user agent value in use to determine the browser.
	 *
	 * @param   string  $value
	 * @return  void
	 */
	public static function setUserAgent($value) {
		self::$userAgent = $value;
		self::$name = self::$version = self::$isRobot = self::$isMobile = null;
		OS::setOS(null);
		OS::setVersion(null);
	}
	
	/**
	 * Return the name of the Browser.
	 * 
	 * @return  string
	 */
	public static function getBrowser() {
		if (!isset(self::$name)) {
			self::checkBrowser();
		}
		return self::$name;
	}
	
	/**
	 * Check to see if the specific browser is valid
	 *
	 * @param   string  $name
	 * @return  bool
	 */
	public static function isBrowser($name) {
		if (!isset(self::$name)) {
			self::checkBrowser();
		}
		return (0 == strcasecmp(self::$name, trim($name)));
	}
	
	/**
	 * Set the name of the OS.
	 * 
	 * @param   string  $value
	 * @return  void
	 */
	public static function setBrowser($value) {
		self::$name = $value;
	}
			
	/**
	 * The version of the browser.
	 * 
	 * @return  string
	 */
	public static function getVersion() {
		if (!isset(self::$name)) {
			self::checkBrowser();
		}
		return self::$version;
	}
	
	/**
	 * Set the version of the browser
	 * 
	 * @param   string  $value
	 * @return  void
	 */
	public static function setVersion($value) {
		self::$version = $value;
	}

	/**
	 * Is the browser from a robot (ex Slurp,GoogleBot)?
	 * 
	 * @return  bool
	 */
	public static function isRobot() {
		return self::$isRobot;
	}
	
	/**
	 * Set the Browser to be a robot
	 * 
	 * @param   string  $value
	 * @return  void
	 */
	public static function setRobot($value = true) {
		self::$isRobot = $value;
	}
	
	/**
	 * Is the browser from a mobile device?
	 * 
	 * @return  bool
	 */
	public static function isMobile() {
		return self::$isMobile;
	}
		
	/**
	 * Set the Browser to be mobile
	 * 
	 * @param   string  $value
	 * @return  void
	 */
	public static function setMobile($value = true) {
		self::$isMobile = $value;
	}
	
	/**
	 * Used to determine if the browser is actually "chromeframe"
	 * 
	 * @return  bool
	 */
	public static function isChromeFrame() {
		return (strpos(self::$userAgent, "chromeframe") !== false );
	}
	
	/**
	 * Routine to determine the browser type
	 * 
	 * @return  bool
	 */
	private static function checkBrowser() {
		self::$name = self::UNKNOWN;
		self::$version = self::VERSION_UNKNOWN;
		
		return (
			// well-known, well-used
			// Special Notes:
			// (1) Opera must be checked before FireFox due to the odd
			//     user agents used in some older versions of Opera
			// (2) WebTV is strapped onto Internet Explorer so we must
			//     check for WebTV before IE
			// (3) (deprecated) Galeon is based on Firefox and needs to be
			//     tested before Firefox is tested
			// (4) OmniWeb is based on Safari so OmniWeb check must occur
			//     before Safari
			// (5) Netscape 9+ is based on Firefox so Netscape checks
			//     before FireFox are necessary
			self::checkBrowserWebTv() ||
			self::checkBrowserInternetExplorer() ||
			self::checkBrowserOpera() ||
			self::checkBrowserGaleon() ||
			self::checkBrowserNetscapeNavigator9Plus() ||
			self::checkBrowserSeaMonkey() || 
			self::checkBrowserFirefox() ||
			self::checkBrowserChrome() ||
			self::checkBrowserOmniWeb() ||

			// common mobile
			self::checkBrowserAndroid() ||
			self::checkBrowserBlackBerry() ||
			self::checkBrowserNokia() ||

			// common bots
			self::checkBrowserRobot() ||

			// WebKit base check (post mobile and others)
			self::checkBrowserSafari() ||

			// everyone else
			self::checkBrowserNetPositive() ||
			self::checkBrowserFirebird() ||
			self::checkBrowserKonqueror() ||
			self::checkBrowserIcab() ||
			self::checkBrowserPhoenix() ||
			self::checkBrowserAmaya() ||
			self::checkBrowserLynx() ||
			self::checkBrowserShiretoko() ||
			self::checkBrowserIceCat() ||
			self::checkBrowserMozilla() /* Mozilla is such an open standard that you must check it last */
		);
	}

	/**
	 * Determine if the user is using a BlackBerry.
	 * 
	 * @return  bool
	 */
	private static function checkBrowserBlackBerry() {
		if (stripos(self::$userAgent, 'blackberry') !== false) {
			$aresult = explode("/", stristr(self::$userAgent, "BlackBerry"));
			$aversion = explode(' ', $aresult[1]);
			self::setVersion($aversion[0]);
			self::setBrowser(self::BLACKBERRY);
			self::setMobile(true);
			return true;
		}
		return false;
	}
	
	/**
	 * Determine if the browser is a robot.
	 * 
	 * @return  bool
	 */
	private static function checkBrowserRobot() {
		if (
			stripos(self::$userAgent, 'bot') !== false || 
			stripos(self::$userAgent, 'spider') !== false || 
			stripos(self::$userAgent, 'crawler') !== false
		) {
			self::setRobot(true);
			return true;
		}
		return false;
	}

	/**
	 * Determine if the browser is Internet Explorer.
	 * 
	 * @return  bool
	 */
	private static function checkBrowserInternetExplorer() {

		// Test for v1 - v1.5 IE
		if( stripos(self::$userAgent,'microsoft internet explorer') !== false ) {
			self::setBrowser(self::IE);
			self::setVersion('1.0');
			$aresult = stristr(self::$userAgent, '/');
			if( preg_match('/308|425|426|474|0b1/i', $aresult) ) {
				self::setVersion('1.5');
			}
			return true;
		}
		// Test for versions > 1.5
		else if( stripos(self::$userAgent,'msie') !== false && stripos(self::$userAgent,'opera') === false ) {
			// See if the browser is the odd MSN Explorer
			if( stripos(self::$userAgent,'msnb') !== false ) {
				$aresult = explode(' ',stristr(str_replace(';','; ',self::$userAgent),'MSN'));
				self::setBrowser( self::MSN );
				self::setVersion(str_replace(array('(',')',';'),'',$aresult[1]));
				return true;
			}
			$aresult = explode(' ',stristr(str_replace(';','; ',self::$userAgent),'msie'));
			self::setBrowser( self::IE );
			self::setVersion(str_replace(array('(',')',';'),'',$aresult[1]));
			return true;
		}
		// Test for Pocket IE
		else if( stripos(self::$userAgent,'mspie') !== false || stripos(self::$userAgent,'pocket') !== false ) {
			$aresult = explode(' ',stristr(self::$userAgent,'mspie'));
			self::setBrowser( self::POCKET_IE );
			self::setMobile(true);

			if( stripos(self::$userAgent,'mspie') !== false ) {
				self::setVersion($aresult[1]);
			}
			else {
				$aversion = explode('/',self::$userAgent);
				self::setVersion($aversion[1]);
			}
			return true;
		}
		return false;
	}

	/**
	 * Determine if the browser is Opera.
	 * 
	 * @return  bool
	 */
	private static function checkBrowserOpera() {
		if( stripos(self::$userAgent,'opera mini') !== false ) {
			$resultant = stristr(self::$userAgent, 'opera mini');
			if( preg_match('/\//',$resultant) ) {
				$aresult = explode('/',$resultant);
				$aversion = explode(' ',$aresult[1]);
				self::setVersion($aversion[0]);
			}
			else {
				$aversion = explode(' ',stristr($resultant,'opera mini'));
				self::setVersion($aversion[1]);
			}
			self::setBrowser(self::OPERA_MINI);
			self::setMobile(true);
			return true;
		}
		else if( stripos(self::$userAgent,'opera') !== false ) {
			$resultant = stristr(self::$userAgent, 'opera');
			if( preg_match('/Version\/(10.*)$/',$resultant,$matches) ) {
				self::setVersion($matches[1]);
			}
			else if( preg_match('/\//',$resultant) ) {
				$aresult = explode('/',str_replace("("," ",$resultant));
				$aversion = explode(' ',$aresult[1]);
				self::setVersion($aversion[0]);
			}
			else {
				$aversion = explode(' ',stristr($resultant,'opera'));
				self::setVersion(isset($aversion[1])?$aversion[1]:"");
			}
			self::setBrowser(self::OPERA);
			return true;
		}
		return false;
	}

	/**
	 * Determine if the browser is Chrome.
	 * 
	 * @return  bool
	 */
	private static function checkBrowserChrome() {
		if( stripos(self::$userAgent,'Chrome') !== false ) {
			$aresult = explode('/',stristr(self::$userAgent,'Chrome'));
			$aversion = explode(' ',$aresult[1]);
			self::setVersion($aversion[0]);
			self::setBrowser(self::CHROME);
			return true;
		}
		return false;
	}


	/**
	 * Determine if the browser is WebTv.
	 * 
	 * @return  bool
	 */
	private static function checkBrowserWebTv() {
		if( stripos(self::$userAgent,'webtv') !== false ) {
			$aresult = explode('/',stristr(self::$userAgent,'webtv'));
			$aversion = explode(' ',$aresult[1]);
			self::setVersion($aversion[0]);
			self::setBrowser(self::WEBTV);
			return true;
		}
		return false;
	}

	/**
	 * Determine if the browser is NetPositive.
	 * 
	 * @return  bool
	 */
	private static function checkBrowserNetPositive() {
		if( stripos(self::$userAgent,'NetPositive') !== false ) {
			$aresult = explode('/',stristr(self::$userAgent,'NetPositive'));
			$aversion = explode(' ',$aresult[1]);
			self::setVersion(str_replace(array('(',')',';'),'',$aversion[0]));
			self::setBrowser(self::NETPOSITIVE);
			return true;
		}
		return false;
	}

	/**
	 * Determine if the browser is Galeon.
	 * 
	 * @return  bool
	 */
	private static function checkBrowserGaleon() {
		if( stripos(self::$userAgent,'galeon') !== false ) {
			$aresult = explode(' ',stristr(self::$userAgent,'galeon'));
			$aversion = explode('/',$aresult[0]);
			self::setVersion($aversion[1]);
			self::setBrowser(self::GALEON);
			return true;
		}
		return false;
	}

	/**
	 * Determine if the browser is Konqueror.
	 * 
	 * @return  bool
	 */
	private static function checkBrowserKonqueror() {
		if( stripos(self::$userAgent,'Konqueror') !== false ) {
			$aresult = explode(' ',stristr(self::$userAgent,'Konqueror'));
			$aversion = explode('/',$aresult[0]);
			self::setVersion($aversion[1]);
			self::setBrowser(self::KONQUEROR);
			return true;
		}
		return false;
	}

	/**
	 * Determine if the browser is iCab.
	 * 
	 * @return  bool
	 */
	private static function checkBrowserIcab() {
		if( stripos(self::$userAgent,'icab') !== false ) {
			$aversion = explode(' ',stristr(str_replace('/',' ',self::$userAgent),'icab'));
			self::setVersion($aversion[1]);
			self::setBrowser(self::ICAB);
			return true;
		}
		return false;
	}

	/**
	 * Determine if the browser is OmniWeb.
	 * 
	 * @return  bool
	 */
	private static function checkBrowserOmniWeb() {
		if( stripos(self::$userAgent,'omniweb') !== false ) {
			$aresult = explode('/',stristr(self::$userAgent,'omniweb'));
			$aversion = explode(' ',isset($aresult[1])?$aresult[1]:"");
			self::setVersion($aversion[0]);
			self::setBrowser(self::OMNIWEB);
			return true;
		}
		return false;
	}

	/**
	 * Determine if the browser is Phoenix.
	 * 
	 * @return  bool
	 */
	private static function checkBrowserPhoenix() {
		if( stripos(self::$userAgent,'Phoenix') !== false ) {
			$aversion = explode('/',stristr(self::$userAgent,'Phoenix'));
			self::setVersion($aversion[1]);
			self::setBrowser(self::PHOENIX);
			return true;
		}
		return false;
	}

	/**
	 * Determine if the browser is Firebird.
	 * 
	 * @return  bool
	 */
	private static function checkBrowserFirebird() {
		if( stripos(self::$userAgent,'Firebird') !== false ) {
			$aversion = explode('/',stristr(self::$userAgent,'Firebird'));
			self::setVersion($aversion[1]);
			self::setBrowser(self::FIREBIRD);
			return true;
		}
		return false;
	}

	/**
	 * Determine if the browser is Netscape Navigator 9+.
	 * 
	 * @return  bool
	 */
	private static function checkBrowserNetscapeNavigator9Plus() {
		if( stripos(self::$userAgent,'Firefox') !== false && preg_match('/Navigator\/([^ ]*)/i',self::$userAgent,$matches) ) {
			self::setVersion($matches[1]);
			self::setBrowser(self::NETSCAPE_NAVIGATOR);
			return true;
		}
		else if( stripos(self::$userAgent,'Firefox') === false && preg_match('/Netscape6?\/([^ ]*)/i',self::$userAgent,$matches) ) {
			self::setVersion($matches[1]);
			self::setBrowser(self::NETSCAPE_NAVIGATOR);
			return true;
		}
		return false;
	}

	/**
	 * Determine if the browser is Shiretoko.
	 * 
	 * @return  bool
	 */
	private static function checkBrowserShiretoko() {
		if( stripos(self::$userAgent,'Mozilla') !== false && preg_match('/Shiretoko\/([^ ]*)/i',self::$userAgent,$matches) ) {
			self::setVersion($matches[1]);
			self::setBrowser(self::SHIRETOKO);
			return true;
		}
		return false;
	}

	/**
	 * Determine if the browser is Ice Cat.
	 * 
	 * @return  bool
	 */
	private static function checkBrowserIceCat() {
		if( stripos(self::$userAgent,'Mozilla') !== false && preg_match('/IceCat\/([^ ]*)/i',self::$userAgent,$matches) ) {
			self::setVersion($matches[1]);
			self::setBrowser(self::ICECAT);
			return true;
		}
		return false;
	}

	/**
	 * Determine if the browser is Nokia.
	 * 
	 * @return  bool
	 */
	private static function checkBrowserNokia() {
		if( preg_match("/Nokia([^\/]+)\/([^ SP]+)/i",self::$userAgent,$matches) ) {
			self::setVersion($matches[2]);
			if( stripos(self::$userAgent,'Series60') !== false || strpos(self::$userAgent,'S60') !== false ) {
				self::setBrowser(self::NOKIA_S60);
			}
			else {
				self::setBrowser( self::NOKIA );
			}
			self::setMobile(true);
			return true;
		}
		return false;
	}

	/**
	 * Determine if the browser is Firefox.
	 * 
	 * @return  bool
	 */
	private static function checkBrowserFirefox() {
		if( stripos(self::$userAgent,'safari') === false ) {
			if( preg_match("/Firefox[\/ \(]([^ ;\)]+)/i",self::$userAgent,$matches) ) {
				self::setVersion($matches[1]);
				self::setBrowser(self::FIREFOX);
				return true;
			}
			else if( preg_match("/Firefox$/i",self::$userAgent,$matches) ) {
				self::setVersion("");
				self::setBrowser(self::FIREFOX);
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Determine if the browser is SeaMonkey.
	 * 
	 * @return  bool
	 */
	private static function checkBrowserSeaMonkey() {
		if (stripos(self::$userAgent, 'safari') === false) {
			if (preg_match("/SeaMonkey[\/ \(]([^ ;\)]+)/i", self::$userAgent, $matches)) {
				self::setVersion($matches[1]);
				self::setBrowser(self::SEAMONKEY);
				return true;
			}
			else if (preg_match("/SeaMonkey$/i", self::$userAgent, $matches)) {
				self::setVersion("");
				self::setBrowser(self::SEAMONKEY);
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Determine if the browser is Firefox.
	 * 
	 * @return  bool
	 */
	private static function checkBrowserIceweasel() {
		if( stripos(self::$userAgent,'Iceweasel') !== false ) {
			$aresult = explode('/',stristr(self::$userAgent,'Iceweasel'));
			$aversion = explode(' ',$aresult[1]);
			self::setVersion($aversion[0]);
			self::setBrowser(self::ICEWEASEL);
			return true;
		}
		return false;
	}
	
	/**
	 * Determine if the browser is Mozilla.
	 * 
	 * @return  bool
	 */
	private static function checkBrowserMozilla() {
		if( stripos(self::$userAgent,'mozilla') !== false  && preg_match('/rv:[0-9].[0-9][a-b]?/i',self::$userAgent) && stripos(self::$userAgent,'netscape') === false) {
			$aversion = explode(' ',stristr(self::$userAgent,'rv:'));
			preg_match('/rv:[0-9].[0-9][a-b]?/i',self::$userAgent,$aversion);
			self::setVersion(str_replace('rv:','',$aversion[0]));
			self::setBrowser(self::MOZILLA);
			return true;
		}
		else if( stripos(self::$userAgent,'mozilla') !== false && preg_match('/rv:[0-9]\.[0-9]/i',self::$userAgent) && stripos(self::$userAgent,'netscape') === false ) {
			$aversion = explode('',stristr(self::$userAgent,'rv:'));
			self::setVersion(str_replace('rv:','',$aversion[0]));
			self::setBrowser(self::MOZILLA);
			return true;
		}
		else if( stripos(self::$userAgent,'mozilla') !== false  && preg_match('/mozilla\/([^ ]*)/i',self::$userAgent,$matches) && stripos(self::$userAgent,'netscape') === false ) {
			self::setVersion($matches[1]);
			self::setBrowser(self::MOZILLA);
			return true;
		}
		return false;
	}

	/**
	 * Determine if the browser is Lynx.
	 * 
	 * @return  bool
	 */
	private static function checkBrowserLynx() {
		if( stripos(self::$userAgent,'lynx') !== false ) {
			$aresult = explode('/',stristr(self::$userAgent,'Lynx'));
			$aversion = explode(' ',(isset($aresult[1])?$aresult[1]:""));
			self::setVersion($aversion[0]);
			self::setBrowser(self::LYNX);
			return true;
		}
		return false;
	}

	/**
	 * Determine if the browser is Amaya.
	 * 
	 * @return  bool
	 */
	private static function checkBrowserAmaya() {
		if( stripos(self::$userAgent,'amaya') !== false ) {
			$aresult = explode('/',stristr(self::$userAgent,'Amaya'));
			$aversion = explode(' ',$aresult[1]);
			self::setVersion($aversion[0]);
			self::setBrowser(self::AMAYA);
			return true;
		}
		return false;
	}

	/**
	 * Determine if the browser is Safari.
	 * 
	 * @return  bool
	 */
	private static function checkBrowserSafari() {
		if( stripos(self::$userAgent,'Safari') !== false ) {
			$aresult = explode('/',stristr(self::$userAgent,'Version'));
			if( isset($aresult[1]) ) {
				$aversion = explode(' ',$aresult[1]);
				self::setVersion($aversion[0]);
			}
			else {
				self::setVersion(self::VERSION_UNKNOWN);
			}
			self::setBrowser(self::SAFARI);
			return true;
		}
		return false;
	}

	/**
	 * Determine if the browser is Android.
	 * 
	 * @return  bool
	 */
	private static function checkBrowserAndroid() {
		// Navigator
		if (stripos(self::$userAgent, 'Android') !== false) {
			if (preg_match('/Version\/([\d\.]*)/i', self::$userAgent, $matches)) {
				self::setVersion($matches[1]);
			} else {
				self::setVersion(self::VERSION_UNKNOWN);
			}
			self::setMobile(true);
			self::setBrowser(self::NAVIGATOR);
			return true;
		}
		return false;
	}
}