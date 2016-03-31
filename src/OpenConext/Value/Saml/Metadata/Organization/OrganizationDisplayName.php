<?php

namespace OpenConext\Value\Saml\Metadata\Organization;

use OpenConext\Value\Assert\Assertion;
use OpenConext\Value\Serializable;

final class OrganizationDisplayName implements Serializable
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
     * @param string $displayName
     * @param string $language
     */
    public function __construct($displayName, $language)
    {
        Assertion::nonEmptyString($displayName, 'displayName');
        Assertion::nonEmptyString($language, 'language');

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

    public static function deserialize($data)
    {
        Assertion::isArray($data);
        Assertion::keysExist($data, array('display_name', 'language'));

        return new self($data['display_name'], $data['language']);
    }

    public function serialize()
    {
        return array(
            'display_name' => $this->displayName,
            'language' => $this->language
        );
    }

    public function __toString()
    {
        return sprintf('[%s] %s', $this->language, $this->displayName);
    }
}
