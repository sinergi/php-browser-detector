<?php
namespace Browser\Tests;

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
        foreach ($xml->strings as $string) {
            $string = $string->string->field;
            $userAgentString = new UserAgentString();
            $userAgentString->setBrowser((string)$string[0]);
            $userAgentString->setVersion((string)$string[1]);
            $userAgentString->setOs((string)$string[2]);
            $userAgentString->setOsVersion((string)$string[3]);
            $userAgentString->setString(str_replace(array(PHP_EOL, '  '), ' ', (string)$string[4]));
            $collection[] = $userAgentString;
        }
        return $collection;
    }
}