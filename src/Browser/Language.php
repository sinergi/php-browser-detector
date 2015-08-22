<?php

namespace Browser;

/**
 * Language Detection.
 */
class Language
{
    /**
     * @var AcceptLanguage
     */
    private $acceptLanguage;

    /**
     * @var array
     */
    private $languages;

    /**
     * @param null|string|AcceptLanguage $acceptLanguage
     *
     * @throws \Browser\InvalidArgumentException
     */
    public function __construct($acceptLanguage = null)
    {
        if ($acceptLanguage instanceof AcceptLanguage) {
            $this->setAcceptLanguage($acceptLanguage);
        } elseif (null === $acceptLanguage || is_string($acceptLanguage)) {
            $this->setAcceptLanguage(new AcceptLanguage($acceptLanguage));
        } else {
            throw new InvalidArgumentException();
        }
    }

    /**
     * Get all user's languages.
     *
     * @return array
     */
    public function getLanguages()
    {
        if (!is_array($this->languages)) {
            LanguageDetector::detect($this, $this->getAcceptLanguage());
        }

        return $this->languages;
    }

    /**
     * Set languages.
     *
     * @param string $languages
     *
     * @return $this
     */
    public function setLanguages($languages)
    {
        $this->languages = $languages;

        return $this;
    }

    /**
     * Get a user's language.
     *
     * @return string
     */
    public function getLanguage()
    {
        if (!is_array($this->languages)) {
            LanguageDetector::detect($this, $this->getAcceptLanguage());
        }

        return strtolower(substr(reset($this->languages), 0, 2));
    }

    /**
     * Get a user's language and locale.
     *
     * @param string $separator
     *
     * @return string
     */
    public function getLanguageLocale($separator = '-')
    {
        if (!is_array($this->languages)) {
            LanguageDetector::detect($this, $this->getAcceptLanguage());
        }

        $userLanguage = $this->getLanguage();
        foreach ($this->languages as $language) {
            if (strlen($language) === 5 && strpos($language, $userLanguage) === 0) {
                $locale = substr($language, -2);
                break;
            }
        }

        if (!empty($locale)) {
            return $userLanguage.$separator.strtoupper($locale);
        } else {
            return $userLanguage;
        }
    }

    /**
     * @param AcceptLanguage $acceptLanguage
     *
     * @return $this
     */
    public function setAcceptLanguage(AcceptLanguage $acceptLanguage)
    {
        $this->acceptLanguage = $acceptLanguage;

        return $this;
    }

    /**
     * @return AcceptLanguage
     */
    public function getAcceptLanguage()
    {
        return $this->acceptLanguage;
    }
}
