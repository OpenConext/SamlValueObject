<?php

namespace OpenConext\Value\Saml\Metadata\Common;

use OpenConext\Value\Assert\Assertion;
use OpenConext\Value\Serializable;

final class LocalizedName implements Serializable
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

        $this->name = $name;
        $this->language = $language;
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

    /**
     * @param LocalizedName $other
     * @return bool
     */
    public function equals(LocalizedName $other)
    {
        return $this->name === $other->name && $this->language === $other->language;
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
            'name' => $this->name,
            'language' => $this->language
        );
    }

    public function __toString()
    {
        return sprintf('LocalizedName(name=%s, language=%s)', $this->name, $this->language);
    }
}
