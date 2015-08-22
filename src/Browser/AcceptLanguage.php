<?php

namespace Browser;

class AcceptLanguage
{
    /**
     * @var string
     */
    private $acceptLanguageString;

    /**
     * @param string $acceptLanguageString
     */
    public function __construct($acceptLanguageString = null)
    {
        if (null !== $acceptLanguageString) {
            $this->setAcceptLanguageString($acceptLanguageString);
        }
    }

    /**
     * @param string $acceptLanguageString
     *
     * @return $this
     */
    public function setAcceptLanguageString($acceptLanguageString)
    {
        $this->acceptLanguageString = $acceptLanguageString;

        return $this;
    }

    /**
     * @return string
     */
    public function getAcceptLanguageString()
    {
        if (null === $this->acceptLanguageString) {
            $this->createAcceptLanguageString();
        }

        return $this->acceptLanguageString;
    }

    /**
     * @return string
     */
    public function createAcceptLanguageString()
    {
        $acceptLanguageString = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : null;
        $this->setAcceptLanguageString($acceptLanguageString);

        return $acceptLanguageString;
    }
}
