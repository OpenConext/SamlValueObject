<?php

namespace OpenConext\Value\Saml\Metadata;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use OpenConext\Value\Exception\InvalidArgumentException;

final class ShibbolethMetadataScopeList implements IteratorAggregate, Countable
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
        foreach ($scopes as $scope) {
            $this->initializeWith($scope);
        }
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
     * @param string $string
     * @return bool
     */
    public function inScope($string)
    {
        if (!is_string($string)) {
            throw InvalidArgumentException::invalidType('string', 'string', $string);
        }

        foreach ($this->scopes as $scope) {
            if ($scope->allows($string)) {
                return true;
            }
        }

        return false;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->scopes);
    }

    public function count()
    {
        return count($this->scopes);
    }

    public function __toString()
    {
        return sprintf('ShibbolethMetadataScopeList(%s)', join(', ', array_map('strval', $this->scopes)));
    }

    private function initializeWith(ShibbolethMetadataScope $scope)
    {
        $this->scopes[] = $scope;
    }
}
