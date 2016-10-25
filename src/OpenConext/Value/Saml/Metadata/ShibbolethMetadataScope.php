<?php

namespace OpenConext\Value\Saml\Metadata;

use OpenConext\Value\Assert\Assertion;
use OpenConext\Value\RegularExpression;
use OpenConext\Value\Serializable;

final class ShibbolethMetadataScope implements Serializable
{
    /**
     * @var string
     */
    private $scope;

    /**
     * @var bool
     */
    private $isRegexp;

    /**
     * @param string $literal
     * @return ShibbolethMetadataScope
     */
    public static function literal($literal)
    {
        Assertion::nonEmptyString($literal, 'literal');

        return new self($literal);
    }

    /**
     * @param string $regexp
     * @return ShibbolethMetadataScope
     */
    public static function regexp($regexp)
    {
        Assertion::nonEmptyString($regexp, 'regexp');

        return new self($regexp, true);
    }

    /**
     * @param string $scope    the scope as defined
     * @param bool   $isRegexp whether or not the scope is a regular expression as identified by the regexp attribute
     */
    public function __construct($scope, $isRegexp = false)
    {
        Assertion::nonEmptyString($scope, 'scope');
        Assertion::boolean($isRegexp);

        if ($isRegexp) {
            Assertion::validRegularExpression("#$scope#i", 'scope');
        }

        $this->scope    = $scope;
        $this->isRegexp = $isRegexp;
    }

    /**
     * @param string $string
     * @return bool
     */
    public function allows($string)
    {
        Assertion::string($string, 'Scope to check should be a string, "%s" given');

        if (!$this->isRegexp) {
            return strcasecmp($this->scope, $string) === 0;
        }

        $regexp = new RegularExpression("#$this->scope#i");
        return $regexp->matches($string);
    }

    /**
     * @param ShibbolethMetadataScope $other
     * @return bool
     */
    public function equals(ShibbolethMetadataScope $other)
    {
        return (strcasecmp($this->scope, $other->scope) === 0 && $this->isRegexp === $other->isRegexp);
    }

    public static function deserialize($data)
    {
        Assertion::isArray($data);
        Assertion::keysExist($data, array('is_regexp', 'scope'));

        return new self($data['scope'], $data['is_regexp']);
    }

    public function serialize()
    {
        return array(
            'scope'     => $this->scope,
            'is_regexp' => $this->isRegexp
        );
    }

    public function __toString()
    {
        return sprintf(
            'ShibbolethMetadataScope(scope=%s, regexp=%s)',
            $this->scope,
            $this->isRegexp ? 'true' : 'false'
        );
    }
}
