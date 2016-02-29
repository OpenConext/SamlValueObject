<?php

namespace OpenConext\Value\Saml\Metadata;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use OpenConext\Value\Assert\Assertion;
use OpenConext\Value\Exception\IndexOutOfBoundsException;
use OpenConext\Value\Serializable;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods) consistent list interface dictates quite a few
 */
final class ShibbolethMetadataScopeList implements Countable, IteratorAggregate, Serializable
{
    /**
     * @var ShibbolethMetadataScope[]
     */
    private $scopes = array();

    /**
     * @param ShibbolethMetadataScope[] $scopes
     */
    public function __construct(array $scopes = array())
    {
        Assertion::allIsInstanceOf($scopes, '\OpenConext\Value\Saml\Metadata\ShibbolethMetadataScope');

        $this->scopes = array_values($scopes);
    }

    /**
     * @param string $string
     * @return bool
     */
    public function inScope($string)
    {
        Assertion::string($string, 'Scope to check must be a string, "%s" given', 'string');

        foreach ($this->scopes as $scope) {
            if ($scope->allows($string)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param ShibbolethMetadataScope $scope
     * @return ShibbolethMetadataScopeList
     */
    public function add(ShibbolethMetadataScope $scope)
    {
        return new ShibbolethMetadataScopeList(array_merge($this->scopes, array($scope)));
    }

    /**
     * @param ShibbolethMetadataScope $shibbolethMetadataScope
     * @return bool
     */
    public function contains(ShibbolethMetadataScope $shibbolethMetadataScope)
    {
        foreach ($this->scopes as $scope) {
            if ($scope->equals($shibbolethMetadataScope)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param ShibbolethMetadataScope $shibbolethMetadataScope
     * @return int
     */
    public function indexOf(ShibbolethMetadataScope $shibbolethMetadataScope)
    {
        foreach ($this->scopes as $index => $scope) {
            if ($scope->equals($shibbolethMetadataScope)) {
                return $index;
            }
        }

        return -1;
    }

    /**
     * @param int $index
     * @return ShibbolethMetadataScope
     */
    public function get($index)
    {
        Assertion::integer($index);

        if ($index < 0) {
            throw IndexOutOfBoundsException::tooLow($index, 0);
        }

        if ($index > count($this->scopes) - 1) {
            throw IndexOutOfBoundsException::tooHigh($index, count($this->scopes) - 1);
        }

        return $this->scopes[$index];
    }

    /**
     * @param Callable $predicate
     * @return null|ShibbolethMetadataScope
     */
    public function find($predicate)
    {
        Assertion::isCallable($predicate, 'predicate');

        foreach ($this->scopes as $shibbolethMetadataScope) {
            if (call_user_func($predicate, $shibbolethMetadataScope) === true) {
                return $shibbolethMetadataScope;
            }
        }

        return null;
    }

    /**
     * @param ShibbolethMetadataScopeList $other
     * @return bool
     */
    public function equals(ShibbolethMetadataScopeList $other)
    {
        if (count($this->scopes) !== count($other->scopes)) {
            return false;
        }

        foreach ($this->scopes as $index => $scope) {
            if (!$scope->equals($other->scopes[$index])) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return ShibbolethMetadataScope[]
     */
    public function toArray()
    {
        return $this->scopes;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->scopes);
    }

    public function count()
    {
        return count($this->scopes);
    }

    public static function deserialize($data)
    {
        Assertion::isArray($data);

        $scopes = array_map(function ($scope) {
            return ShibbolethMetadataScope::deserialize($scope);
        }, $data);

        return new self($scopes);
    }

    public function serialize()
    {
        return array_map(function (ShibbolethMetadataScope $shibbolethMetadataScope) {
            return $shibbolethMetadataScope->serialize();
        }, $this->scopes);
    }

    public function __toString()
    {
        return sprintf('ShibbolethMetadataScopeList(%s)', join(', ', array_map('strval', $this->scopes)));
    }
}
