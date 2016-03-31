<?php

namespace OpenConext\Value\Saml\Metadata\Common;

use OpenConext\Value\Assert\Assertion;
use OpenConext\Value\Serializable;

final class Binding implements Serializable
{
    /**
     * URNs for the various bindings
     */
    const HTTP_POST     = 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST';
    const HTTP_REDIRECT = 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect';
    const HTTP_ARTIFACT = 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Artifact';
    const SOAP          = 'urn:oasis:names:tc:SAML:2.0:bindings:SOAP';
    const HOK_SSO       = 'urn:oasis:names:tc:SAML:2.0:profiles:holder-of-key:SSO:browser';

    private static $validBindings = array(
        self::HTTP_POST,
        self::HTTP_REDIRECT,
        self::HTTP_ARTIFACT,
        self::SOAP,
        self::HOK_SSO
    );

    /**
     * @var string
     */
    private $binding;

    public function __construct($binding)
    {
        $message = 'Binding must be one of Binding::HTTP_POST, Binding::HTTP_REDIRECT, Binding::HTTP_ARTIFACT '
                    . 'Binding::SOAP or Binding::HOK_SSO';

        Assertion::inArray($binding, self::$validBindings, $message);

        $this->binding = $binding;
    }

    /**
     * @return Binding
     */
    public static function httpPost()
    {
        return new self(self::HTTP_POST);
    }

    /**
     * @return Binding
     */
    public static function httpRedirect()
    {
        return new self(self::HTTP_REDIRECT);
    }

    /**
     * @return Binding
     */
    public static function httpArtifact()
    {
        return new self(self::HTTP_ARTIFACT);
    }

    /**
     * @return Binding
     */
    public static function soap()
    {
        return new self(self::SOAP);
    }

    /**
     * @return Binding
     */
    public static function holderOfKey()
    {
        return new self(self::HOK_SSO);
    }

    /**
     * @param $binding
     * @return bool
     */
    public static function isValidBinding($binding)
    {
        return in_array($binding, self::$validBindings);
    }

    /**
     * @param string $binding
     * @return bool
     */
    public function isOfType($binding)
    {
        $message = 'BindingType must be one of Binding::HTTP_POST, Binding::HTTP_REDIRECT, Binding::HTTP_ARTIFACT '
            . 'Binding::SOAP or Binding::HOK_SSO';

        Assertion::inArray($binding, self::$validBindings, $message);

        return $this->binding === $binding;
    }

    /**
     * @return string
     */
    public function getBinding()
    {
        return $this->binding;
    }

    /**
     * @param Binding $other
     * @return bool
     */
    public function equals(Binding $other)
    {
        return $this->binding === $other->binding;
    }

    public static function deserialize($data)
    {
        Assertion::nonEmptyString($data, 'data');

        return new self($data);
    }

    public function serialize()
    {
        return $this->binding;
    }

    public function __toString()
    {
        return $this->binding;
    }
}
