<?php

namespace OpenConext\Value\Saml\Metadata\Common;

use OpenConext\Value\Exception\InvalidArgumentException;
use PHPUnit_Framework_TestCase as UnitTest;

class EndpointTest extends UnitTest
{
    /**
     * @test
     * @group metadata
     * @group common
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notStringOrEmptyString
     * @expectedException InvalidArgumentException
     *
     * @param mixed $notStringOrEmptyString
     */
    public function location_must_be_a_non_empty_string($notStringOrEmptyString)
    {
        new Endpoint(Binding::httpPost(), $notStringOrEmptyString);
    }

    /**
     * @test
     * @group metadata
     * @group common
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notNullAndNotStringOrEmptyString
     * @expectedException InvalidArgumentException
     *
     * @param mixed $notNullAndNotStringOrEmptyString
     */
    public function response_location_must_be_null_or_a_non_empty_string($notNullAndNotStringOrEmptyString)
    {
        new Endpoint(Binding::httpPost(), 'some:uri', $notNullAndNotStringOrEmptyString);
    }

    /**
     * @test
     * @group metadata
     * @group common
     */
    public function the_binding_can_be_retrieved()
    {
        $binding = Binding::httpPost();

        $endpoint = new Endpoint($binding, 'some:uri');

        $this->assertEquals($binding, $endpoint->getBinding());
    }

    /**
     * @test
     * @group metadata
     * @group common
     */
    public function the_location_can_be_retrieved()
    {
        $location = 'some:uri';

        $endpoint = new Endpoint(Binding::httpArtifact(), $location);

        $this->assertEquals($location, $endpoint->getLocation());
    }

    /**
     * @test
     * @group metadata
     * @group common
     */
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

    /**
     * @test
     * @group metadata
     * @group common
     */
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

    /**
     * @test
     * @group metadata
     * @group common
     */
    public function deserializing_a_serialized_endpoint_results_in_an_equal_value_object()
    {
        $original = new Endpoint(Binding::httpPost(), 'some:uri', 'some:response:location');

        $deserialized = Endpoint::deserialize($original->serialize());

        $this->assertTrue($original->equals($deserialized));
    }

    /**
     * @test
     * @group metadata
     * @group common
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notarray
     * @expectedException InvalidArgumentException
     *
     * @param mixed $notArray
     */
    public function deserialization_requires_data_to_be_an_array($notArray)
    {
        Endpoint::deserialize($notArray);
    }

    /**
     * @test
     * @group metadata
     * @group common
     *
     * @dataProvider invalidDataProvider
     * @expectedException InvalidArgumentException
     *
     * @param array $invalidData
     */
    public function deserialization_requires_presence_of_all_required_keys($invalidData)
    {
        Endpoint::deserialize($invalidData);
    }

    public function invalidDataProvider()
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

    /**
     * @test
     * @group metadata
     * @group common
     */
    public function an_endpoint_can_be_cast_to_string()
    {
        $this->assertInternalType('string', (string) new Endpoint(Binding::httpPost(), 'some:uri'));
    }
}
