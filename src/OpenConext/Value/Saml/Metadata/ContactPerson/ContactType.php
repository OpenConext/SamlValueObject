<?php

namespace OpenConext\Value\Saml\Metadata\ContactPerson;

use OpenConext\Value\Assert\Assertion;

final class ContactType
{
    const TYPE_ADMINISTRATIVE = 'administrative';
    const TYPE_BILLING        = 'billing';
    const TYPE_OTHER          = 'other';
    const TYPE_SUPPORT        = 'support';
    const TYPE_TECHNICAL      = 'technical';

    private static $validTypes = array(
        self::TYPE_ADMINISTRATIVE,
        self::TYPE_BILLING,
        self::TYPE_OTHER,
        self::TYPE_SUPPORT,
        self::TYPE_TECHNICAL
    );

    /**
     * @var string
     */
    private $type;

    /**
     ** @param string $type one of the ContactType::TYPE_* constants
     */
    public function __construct($type)
    {
        $validMessage = 'one of "ContactType::' . implode(', ContactType::', self::$validTypes) . '"';
        Assertion::inArray($type, self::$validTypes, $validMessage);

        $this->type = $type;
    }

    /**
     * @return ContactType
     */
    public static function administrative()
    {
        return new self(self::TYPE_ADMINISTRATIVE);
    }

    /**
     * @return ContactType
     */
    public static function billing()
    {
        return new self(self::TYPE_BILLING);
    }

    /**
     * @return ContactType
     */
    public static function other()
    {
        return new self(self::TYPE_OTHER);
    }

    /**
     * @return ContactType
     */
    public static function support()
    {
        return new self(self::TYPE_SUPPORT);
    }

    /**
     * @return ContactType
     */
    public static function technical()
    {
        return new self(self::TYPE_TECHNICAL);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param ContactType $other
     * @return bool
     */
    public function equals(ContactType $other)
    {
        return $this->type === $other->type;
    }

    public function __toString()
    {
        return $this->type;
    }
}
