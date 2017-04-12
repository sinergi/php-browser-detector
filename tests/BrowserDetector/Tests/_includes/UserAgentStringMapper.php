<?php

namespace Sinergi\BrowserDetector\Tests;

use SimpleXmlElement;

class UserAgentStringMapper
{
    /**
     * @return UserAgentString[]
     */
    public static function map()
    {
        $collection = array();
        $xml = new SimpleXmlElement(file_get_contents(FILES . DIRECTORY_SEPARATOR . 'UserAgentStrings.xml'));

        foreach ($xml->strings->string as $string) {
            $userAgentString = new UserAgentString();
            foreach ($string->children() as $child) {
                $attributes = $child->attributes();
                switch ($attributes['name']) {
                    case "browser":
                        $userAgentString->setBrowser((string)$child[0]);
                        break;
                    case "version":
                        $userAgentString->setBrowserVersion((string)$child[0]);
                        break;
                    case "os":
                        $userAgentString->setOs((string)$child[0]);
                        break;
                    case "os_version":
                        $userAgentString->setOsVersion((string)$child[0]);
                        break;
                    case "device":
                        $userAgentString->setDevice((string)$child[0]);
                        break;
                    case "device_version":
                        $userAgentString->setDeviceVersion((string)$child[0]);
                        break;
                    case "scripted_agent":
                        $userAgentString->setScriptedAgent((string)$child[0]);
                        break;
                    case "scripted_agent_type":
                        $userAgentString->setScriptedAgentType((string)$child[0]);
                        break;
                    case "string":
                        $userAgentString->setString(str_replace(array(PHP_EOL, '  '), ' ', (string)(string)$child[0]));
                        break;
                }
            }
            $collection[] = $userAgentString;
        }

        return $collection;
    }
}
