<?php

namespace OpenConext\Value\Saml;

use OpenConext\Value\Assert\Assertion;
use OpenConext\Value\Serializable;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods) this is due to the various named constructors
 */
final class NameIdFormat implements Serializable
{
    /**
     * The various types of Name Identifier Format Identifiers as defined in section 8.3 of
     * Assertions and Protocols for the OASIS Security Assertion Markup Language (SAML) V2.0
     *
     * @see https://docs.oasis-open.org/security/saml/v2.0/saml-core-2.0-os.pdf
     */
    const UNSPECIFIED                   = 'urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified';
    const EMAIL_ADDRESS                 = 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress';
    const X509_SUBJECT_NAME             = 'urn:oasis:names:tc:SAML:1.1:nameid-format:X509SubjectName';
    const WINDOWS_DOMAIN_QUALIFIED_NAME = 'urn:oasis:names:tc:SAML:1.1:nameid-format:WindowsDomainQualifiedName';
    const KERBEROS_PRINCIPLE_NAME       = 'urn:oasis:names:tc:SAML:2.0:nameid-format:kerberos';
    const ENTITY_IDENTIFIER             = 'urn:oasis:names:tc:SAML:2.0:nameid-format:entity';
    const PERSISTENT_IDENTIFIER         = 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent';
    const TRANSIENT_IDENTIFIER          = 'urn:oasis:names:tc:SAML:2.0:nameid-format:transient';

    /**
     * @var array
     */
    private static $validFormats = array(
        self::UNSPECIFIED,
        self::EMAIL_ADDRESS,
        self::X509_SUBJECT_NAME,
        self::WINDOWS_DOMAIN_QUALIFIED_NAME,
        self::KERBEROS_PRINCIPLE_NAME,
        self::ENTITY_IDENTIFIER,
        self::PERSISTENT_IDENTIFIER,
        self::TRANSIENT_IDENTIFIER
    );

    /**
     * @var string
     */
    private $format;

    /**
     ** @param string $format one of the valid NameID formats
     */
    public function __construct($format)
    {
        Assertion::inArray($format, self::$validFormats);

        $this->format = $format;
    }

    /**
     * @return NameIdFormat
     */
    public static function unspecified()
    {
        return new self(self::UNSPECIFIED);
    }

    /**
     * @return NameIdFormat
     */
    public static function emailAddress()
    {
        return new self(self::EMAIL_ADDRESS);
    }

    /**
     * @return NameIdFormat
     */
    public static function x509SubjectName()
    {
        return new self(self::X509_SUBJECT_NAME);
    }

    /**
     * @return NameIdFormat
     */
    public static function windowsDomainQualifiedName()
    {
        return new self(self::WINDOWS_DOMAIN_QUALIFIED_NAME);
    }

    /**
     * @return NameIdFormat
     */
    public static function kerberosPrincipalName()
    {
        return new self(self::KERBEROS_PRINCIPLE_NAME);
    }

    /**
     * @return NameIdFormat
     */
    public static function entity()
    {
        return new self(self::ENTITY_IDENTIFIER);
    }

    /**
     * @return NameIdFormat
     */
    public static function persistent()
    {
        return new self(self::PERSISTENT_IDENTIFIER);
    }

    /**
     * @return NameIdFormat
     */
    public static function transient()
    {
        return new self(self::TRANSIENT_IDENTIFIER);
    }

    /**
     * @param NameIdFormat $other
     * @return bool
     */
    public function equals(NameIdFormat $other)
    {
        return $this->format === $other->format;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    public static function deserialize($data)
    {
        Assertion::nonEmptyString($data, 'data');

        return new self($data);
    }

    public function serialize()
    {
        return $this->format;
    }

    public function __toString()
    {
        return $this->format;
    }
}
