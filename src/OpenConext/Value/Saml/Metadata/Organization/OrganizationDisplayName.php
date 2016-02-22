<?php

namespace OpenConext\Value\Saml\Metadata\Organization;

use OpenConext\Value\Exception\InvalidArgumentException;

final class OrganizationDisplayName
{
    /**
     * @var string
     */
    private $displayName;

    /**
     * @var string
     */
    private $language;

    /**
     * @param $displayName
     * @param $language
     */
    public function __construct($displayName, $language)
    {
        if (!is_string($displayName) || trim($displayName) === '') {
            throw InvalidArgumentException::invalidType('non-empty string', 'displayName', $displayName);
        }

        if (!is_string($language) || trim($language) === '') {
            throw InvalidArgumentException::invalidType('non-empty string', 'language', $language);
        }

        $this->displayName = $displayName;
        $this->language = $language;
    }

    /**
     * @param OrganizationDisplayName $other
     * @return bool
     */
    public function equals(OrganizationDisplayName $other)
    {
        return ($this->displayName === $other->displayName && $this->language === $other->language);
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    public function __toString()
    {
        return sprintf('[%s] %s', $this->language, $this->displayName);
    }
}
