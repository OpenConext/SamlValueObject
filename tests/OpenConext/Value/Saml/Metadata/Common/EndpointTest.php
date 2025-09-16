<?php

namespace OpenConext\Value\Saml\Metadata\Common;

use OpenConext\Value\Exception\InvalidArgumentException;


class EndpointTest extends \PHPUnit\Framework\TestCase
{
    /**
     *
     *
     * @param mixed $notStringOrEmptyString
     */
    #[\PHPUnit\Framework\Attributes\DataProviderExternal(\OpenConext\Value\TestDataProvider::class, 'notStringOrEmptyString')]
    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function location_must_be_a_non_empty_string($notStringOrEmptyString)
    {
        $this->expectException(\InvalidArgumentException::class);
        new Endpoint(Binding::httpPost(), $notStringOrEmptyString);
    }

    /**
     *
     *
     * @param mixed $notNullAndNotStringOrEmptyString
     */
    #[\PHPUnit\Framework\Attributes\DataProviderExternal(\OpenConext\Value\TestDataProvider::class, 'notNullAndNotStringOrEmptyString')]
    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function response_location_must_be_null_or_a_non_empty_string($notNullAndNotStringOrEmptyString)
    {
        $this->expectException(\InvalidArgumentException::class);
        new Endpoint(Binding::httpPost(), 'some:uri', $notNullAndNotStringOrEmptyString);
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function the_binding_can_be_retrieved()
    {
        $binding = Binding::httpPost();

        $endpoint = new Endpoint($binding, 'some:uri');

        $this->assertEquals($binding, $endpoint->getBinding());
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function the_location_can_be_retrieved()
    {
        $location = 'some:uri';

        $endpoint = new Endpoint(Binding::httpArtifact(), $location);

        $this->assertEquals($location, $endpoint->getLocation());
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function the_response_location_can_be_retrieved()
    {
        $responseLocation = 'some:response:location';

        $withResponseLocation = new Endpoint(Binding::httpPost(), 'some:uri', $responseLocation);
        $noResponseLocation = new Endpoint(Binding::httpPost(), 'some:uri');
        $nullReponseLocation = new Endpoint(Binding::httpPost(), 'some:uri', null);

        $this->assertEquals($responseLocation, $withResponseLocation->getResponseLocation());
        $this->assertNull($noResponseLocation->getResponseLocation());
        $this->assertNull($nullReponseLocation->getResponseLocation());
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function equality_is_verified_on_all_properties()
    {
        $base                      = new Endpoint(Binding::httpPost(), 'some:uri', 'some:response:location');
        $same                      = new Endpoint(Binding::httpPost(), 'some:uri', 'some:response:location');
        $noResponseLocation        = new Endpoint(Binding::httpPost(), 'some:uri');
        $differentBinding          = new Endpoint(Binding::httpRedirect(), 'some:uri', 'some:response:location');
        $differentLocation         = new Endpoint(
            Binding::httpRedirect(),
            'some:different:uri',
            'some:response:location'
        );
        $differentResponseLocation = new Endpoint(
            Binding::httpRedirect(),
            'some:different:uri',
            'different:esponse:location'
        );

        $this->assertTrue($base->equals($same));
        $this->assertFalse($base->equals($noResponseLocation));
        $this->assertFalse($base->equals($differentBinding));
        $this->assertFalse($base->equals($differentLocation));
        $this->assertFalse($base->equals($differentResponseLocation));
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function deserializing_a_serialized_endpoint_results_in_an_equal_value_object()
    {
        $original = new Endpoint(Binding::httpPost(), 'some:uri', 'some:response:location');

        $deserialized = Endpoint::deserialize($original->serialize());

        $this->assertTrue($original->equals($deserialized));
    }

    /**
     *
     *
     * @param mixed $notArray
     */
    #[\PHPUnit\Framework\Attributes\DataProviderExternal(\OpenConext\Value\TestDataProvider::class, 'notarray')]
    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function deserialization_requires_data_to_be_an_array($notArray)
    {
        $this->expectException(\InvalidArgumentException::class);
        Endpoint::deserialize($notArray);
    }

    /**
     *
     *
     * @param array $invalidData
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('invalidDataProvider')]
    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function deserialization_requires_presence_of_all_required_keys($invalidData)
    {
        $this->expectException(\InvalidArgumentException::class);
        Endpoint::deserialize($invalidData);
    }

    public static function invalidDataProvider()
    {
        return array(
            'no matching keys'     => array(
                array(
                    'foo' => Binding::HOK_SSO,
                    'bar' => 'some:uri',
                    'baz' => 'some:response:uri'
                )
            ),
            'no binding'           => array(
                array(
                    'location'          => 'some:uri',
                    'response_location' => 'some:response:uri'
                )
            ),
            'no location'          => array(
                array(
                    'binding'           => Binding::HOK_SSO,
                    'response_location' => 'some:response:uri'
                )
            ),
            'no response_location' => array(array('foo' => Binding::HOK_SSO, 'bar' => 'some:uri')),
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function an_endpoint_can_be_cast_to_string()
    {
        $this->assertIsString((string) new Endpoint(Binding::httpPost(), 'some:uri'));
    }
}
