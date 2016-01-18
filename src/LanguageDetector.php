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
        $acceptLanguageString = $acceptLanguage->getAcceptLanguageString();
        $languages = array();
        $language->setLanguages($languages);

        if (!empty($acceptLanguageString)) {
            $httpLanguages = preg_split(
                '/q=([\d\.]*)/',
                $acceptLanguageString,
                -1,
                PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE
            );

            $key = 0;
            foreach (array_reverse($httpLanguages) as $value) {
                $value = trim($value, ',; .');
                if (is_numeric($value)) {
                    $key = $value;
                } else {
                    $languages[$key] = explode(',', $value);
                }
            }
            krsort($languages);

            foreach ($languages as $value) {
                $language->setLanguages(array_merge($language->getLanguages(), $value));
            }
        }
    }
}
