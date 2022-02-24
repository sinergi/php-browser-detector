<?php

namespace Sinergi\BrowserDetector;

class LanguageDetector implements DetectorInterface
{
    /**
     * Detect a user's languages and order them by priority.
     *
     * @param Language $language
     * @param AcceptLanguage $acceptLanguage
     *
     * @return null
     */
    public static function detect(Language $language, AcceptLanguage $acceptLanguage)
    {
        // Accept-Language header convention:
        // https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.4

        $acceptLanguageString = $acceptLanguage->getAcceptLanguageString();
        $languages = array();
        $language->setLanguages($languages);

        if (!empty($acceptLanguageString)) {
            // Split by ranges (en-US,en;q=0.8) and keep priority value (0.8)
            $httpLanguages = preg_split(
                '/q=([\d\.]*)/',
                $acceptLanguageString,
                -1,
                PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE
            );

            // Group by priority
            $key = 0;
            foreach (array_reverse($httpLanguages) as $value) {
                $value = trim($value, ',; .');
                if (is_numeric($value)) {
                    $key = $value;
                } else if($value) {
                    $languages[$key] = preg_split('/[^\w-]+/', $value);
                }
            }

            // Sort by priority
            krsort($languages);

            // Flatten array and remove duplicates
            $result = [];
            foreach ($languages as $values) {
                foreach ($values as $value) {
                    $result[$value] = true;
                }
            }

            $language->setLanguages(array_keys($result));
        }
    }
}
