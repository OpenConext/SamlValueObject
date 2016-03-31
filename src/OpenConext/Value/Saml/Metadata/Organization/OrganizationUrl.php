<?php

namespace OpenConext\Value\Saml\Metadata\Organization;

use OpenConext\Value\Assert\Assertion;
use OpenConext\Value\Serializable;

final class OrganizationUrl implements Serializable
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $language;

    /**
     * @param string $url
     * @param string $language
     */
    public function __construct($url, $language)
    {
        Assertion::nonEmptyString($url, 'url');
        Assertion::nonEmptyString($language, 'language');

        $this->url      = $url;
        $this->language = $language;
    }

    /**
     * @param OrganizationUrl $other
     * @return bool
     */
    public function equals(OrganizationUrl $other)
    {
        return ($this->url === $other->url && $this->language === $other->language);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
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
        Assertion::keysExist($data, array('url', 'language'));

        return new self($data['url'], $data['language']);
    }

    public function serialize()
    {
        return array(
            'url'      => $this->url,
            'language' => $this->language
        );
    }

    public function __toString()
    {
        return sprintf('[%s] %s', $this->language, $this->url);
    }
}
