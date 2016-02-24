<?php

namespace OpenConext\Value\Saml\Metadata\Organization;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use OpenConext\Value\Assert\Assertion;
use OpenConext\Value\Exception\IndexOutOfBoundsException;
use OpenConext\Value\Serializable;

final class OrganizationUrlList implements Countable, IteratorAggregate, Serializable
{
    /**
     * @var OrganizationUrl[]
     */
    private $organizationUrls;

    /**
     * @param OrganizationUrl[] $organizationUrls
     */
    public function __construct(array $organizationUrls)
    {
        Assertion::allIsInstanceOf($organizationUrls, '\OpenConext\Value\Saml\Metadata\Organization\OrganizationUrl');

        $this->organizationUrls = array_values($organizationUrls);
    }

    /**
     * @param OrganizationUrl $organizationUrl
     * @return OrganizationUrlList
     */
    public function add(OrganizationUrl $organizationUrl)
    {
        return new self(array_merge($this->organizationUrls, array($organizationUrl)));
    }

    /**
     * @param OrganizationUrl $organizationUrl
     * @return bool
     */
    public function contains(OrganizationUrl $organizationUrl)
    {
        foreach ($this->organizationUrls as $url) {
            if ($url->equals($organizationUrl)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param OrganizationUrl $organizationUrl
     * @return int
     */
    public function indexOf(OrganizationUrl $organizationUrl)
    {
        foreach ($this->organizationUrls as $index => $url) {
            if ($url->equals($organizationUrl)) {
                return $index;
            }
        }

        return -1;
    }

    /**
     * @param int $index
     * @return OrganizationUrl
     */
    public function get($index)
    {
        Assertion::integer($index);

        if ($index < 0) {
            throw IndexOutOfBoundsException::tooLow($index, 0);
        }

        if ($index > count($this->organizationUrls) - 1) {
            throw IndexOutOfBoundsException::tooHigh($index, count($this->organizationUrls) - 1);
        }

        return $this->organizationUrls[$index];
    }

    /**
     * @param OrganizationUrlList $other
     * @return bool
     */
    public function equals(OrganizationUrlList $other)
    {
        if (count($this->organizationUrls) !== count($other->organizationUrls)) {
            return false;
        }

        foreach ($this->organizationUrls as $index => $organizationUrl) {
            if (!$organizationUrl->equals($other->organizationUrls[$index])) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return OrganizationUrl[]
     */
    public function toArray()
    {
        return $this->organizationUrls;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->organizationUrls);
    }

    public function count()
    {
        return count($this->organizationUrls);
    }

    public static function deserialize($data)
    {
        Assertion::isArray($data);

        $organizationUrls = array_map(function ($organizationUrl) {
            return OrganizationUrl::deserialize($organizationUrl);
        }, $data);

        return new self($organizationUrls);
    }

    public function serialize()
    {
        return array_map(function (OrganizationUrl $organizationUrl) {
            return $organizationUrl->serialize();
        }, $this->organizationUrls);
    }

    public function __toString()
    {
        return sprintf('OrganizationUrlList[%s]', implode(', ', $this->organizationUrls));
    }
}
