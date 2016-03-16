<?php

namespace OpenConext\Value\Saml\Metadata\Common;

use OpenConext\Value\Assert\Assertion;
use OpenConext\Value\Serializable;

final class Endpoint implements Serializable
{
    /**
     * @var Binding
     */
    private $binding;

    /**
     * @var string
     */
    private $location;

    /**
     * @var null|string
     */
    private $responseLocation;

    /**
     * @param Binding     $binding
     * @param string      $location
     * @param null|string $responseLocation
     */
    public function __construct(Binding $binding, $location, $responseLocation = null)
    {
        Assertion::nonEmptyString($location, 'location');
        Assertion::nullOrNonEmptyString($responseLocation, 'responseLocation');

        $this->binding = $binding;
        $this->location = $location;
        $this->responseLocation = $responseLocation;
    }

    /**
     * @return Binding
     */
    public function getBinding()
    {
        return $this->binding;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @return null|string
     */
    public function getResponseLocation()
    {
        return $this->responseLocation;
    }

    /**
     * @param Endpoint $other
     * @return bool
     */
    public function equals(Endpoint $other)
    {
        return $this->binding->equals($other->binding)
                && $this->location === $other->location
                && $this->responseLocation === $other->responseLocation;
    }

    public static function deserialize($data)
    {
        Assertion::isArray($data);
        Assertion::keysExist($data, array('binding', 'location', 'response_location'));

        return new self(Binding::deserialize($data['binding']), $data['location'], $data['response_location']);
    }

    public function serialize()
    {
        return array(
            'binding' => $this->binding->serialize(),
            'location' => $this->location,
            'response_location' => $this->responseLocation
        );
    }

    public function __toString()
    {
        return sprintf(
            'Endpoint(Binding=%s, Location=%s, ResponseLocation=%s',
            (string) $this->binding,
            $this->location,
            ($this->responseLocation ?: 'null')
        );
    }
}
