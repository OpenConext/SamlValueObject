<?php

namespace OpenConext\Value\Saml\Metadata\Common;

use OpenConext\Value\Assert\Assertion;
use OpenConext\Value\Serializable;

final class IndexedEndpoint implements Serializable
{
    /**
     * @var Endpoint
     */
    private $endpoint;

    /**
     * @var int
     */
    private $index;

    /**
     * @var bool
     */
    private $isDefault;

    public function __construct(Endpoint $endpoint, $index, $isDefault = false)
    {
        Assertion::integer($index);
        Assertion::boolean($isDefault);

        $this->endpoint = $endpoint;
        $this->index = $index;
        $this->isDefault = $isDefault;
    }

    /**
     * @return Binding
     */
    public function getBinding()
    {
        return $this->endpoint->getBinding();
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->endpoint->getLocation();
    }

    /**
     * @return null|string
     */
    public function getResponseLocation()
    {
        return $this->endpoint->getResponseLocation();
    }

    /**
     * @return int
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @return bool
     */
    public function isDefault()
    {
        return $this->isDefault;
    }

    /**
     * @param IndexedEndpoint $other
     * @return bool 
     */
    public function equals(IndexedEndpoint $other)
    {
        return $this->endpoint->equals($other->endpoint)
                && $this->index === $other->index
                && $this->isDefault === $other->isDefault;
    }

    public static function deserialize($data)
    {
        Assertion::isArray($data);
        Assertion::keysExist($data, array('endpoint', 'index', 'is_default'));

        return new self(
            Endpoint::deserialize($data['endpoint']),
            $data['index'],
            $data['is_default']
        );
    }

    public function serialize()
    {
        return array(
            'endpoint' => $this->endpoint->serialize(),
            'index' => $this->index,
            'is_default' => $this->isDefault
        );
    }

    public function __toString()
    {
        return sprintf(
            'IndexedEndpoint(%s, index=%d, isDefault=%s',
            (string) $this->endpoint,
            $this->index,
            ($this->isDefault ? 'true' : 'false')
        );
    }
}
