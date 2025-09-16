<?php

namespace OpenConext\Value\Saml\Metadata\Common;

use OpenConext\Value\Assert\Assertion;
use OpenConext\Value\Serializable;

final class LocalizedUri implements Serializable
{
    /**
     * @var string
     */
    private $uri;

    /**
     * @var string
     */
    private $language;

    /**
     * @param string $uri
     * @param string $language
     */
    public function __construct($uri, $language)
    {
        Assertion::nonEmptyString($uri, 'uri');
        Assertion::nonEmptyString($language, 'language');

        $this->uri = $uri;
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param LocalizedUri $other
     * @return bool
     */
    public function equals(LocalizedUri $other)
    {
        return $this->uri === $other->uri && $this->language === $other->language;
    }

    public static function deserialize($data)
    {
        Assertion::isArray($data);
        Assertion::keysExist($data, array('uri', 'language'));

        return new self($data['uri'], $data['language']);
    }

    public function serialize()
    {
        return array(
            'uri'      => $this->uri,
            'language' => $this->language
        );
    }

    public function __toString()
    {
        return sprintf('LocalizedUri(uri=%s, language=%s)', $this->uri, $this->language);
    }
}
