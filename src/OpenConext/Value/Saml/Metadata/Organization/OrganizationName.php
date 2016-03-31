<?php

namespace OpenConext\Value\Saml\Metadata\Organization;

use OpenConext\Value\Assert\Assertion;
use OpenConext\Value\Serializable;

final class OrganizationName implements Serializable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $language;

    /**
     * @param string $name
     * @param string $language
     */
    public function __construct($name, $language)
    {
        Assertion::nonEmptyString($name, 'name');
        Assertion::nonEmptyString($language, 'language');

        $this->name     = $name;
        $this->language = $language;
    }

    /**
     * @param OrganizationName $other
     * @return bool
     */
    public function equals(OrganizationName $other)
    {
        return ($this->name === $other->name && $this->language === $other->language);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
        Assertion::keysExist($data, array('name', 'language'));

        return new self($data['name'], $data['language']);
    }

    public function serialize()
    {
        return array(
            'name'     => $this->name,
            'language' => $this->language
        );
    }

    public function __toString()
    {
        return sprintf('[%s] %s', $this->language, $this->name);
    }
}
