<?php

namespace OpenConext\Value\Saml;

use OpenConext\Value\Assert\Assertion;
use OpenConext\Value\Serializable;

final class EntityType implements Serializable
{
    const TYPE_SP  = 'saml20-sp';
    const TYPE_IDP = 'saml20-idp';

    /**
     * @var string
     */
    private $type;

    /**
     * @param string $type
     */
    public function __construct($type)
    {
        Assertion::inArray(
            $type,
            array(self::TYPE_SP, self::TYPE_IDP),
            'EntityType must be one of EntityType::TYPE_SP or EntityType::TYPE_IDP'
        );

        $this->type = $type;
    }

    /**
     * Creates a new ServiceProvider Type
     * @return EntityType
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public static function SP()
    {
        return new EntityType(self::TYPE_SP);
    }

    // @codingStandardsIgnoreStart
    /**
     * Creates a new IdentityProvider Type
     * @return EntityType
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    public static function IdP()
    {
        return new EntityType(self::TYPE_IDP);
    }
    // @codingStandardsIgnoreEnd

    /**
     * @return bool
     */
    public function isServiceProvider()
    {
        return $this->type === self::TYPE_SP;
    }

    /**
     * @return bool
     */
    public function isIdentityProvider()
    {
        return $this->type === self::TYPE_IDP;
    }

    /**
     * @param EntityType $other
     * @return bool
     */
    public function equals(EntityType $other)
    {
        return $this->type === $other->type;
    }

    public static function deserialize($data)
    {
        return new self($data);
    }

    public function serialize()
    {
        return $this->type;
    }

    public function __toString()
    {
        return $this->type;
    }
}
