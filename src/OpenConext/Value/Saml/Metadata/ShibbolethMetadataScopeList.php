<?php

namespace OpenConext\Value\Saml\Metadata;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use OpenConext\Value\Assert\Assertion;

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
        Assertion::string($string, 'Scope to check must be a string, "%s" given', 'string');

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
