<?php

namespace OpenConext\Value\Saml\Metadata\Common;

use Doctrine\Instantiator\Exception\InvalidArgumentException;
use PHPUnit_Framework_TestCase as UnitTest;

class IndexedEndpointTest extends UnitTest
{
    /**
     * @test
     * @group metadata
     * @group common
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notInteger
     * @expectedException InvalidArgumentException
     *
     * @param mixed $notInteger
     */
    public function index_must_be_an_integer($notInteger)
    {
        new IndexedEndpoint($this->getPostEndpoint(), $notInteger);
    }

    /**
     * @test
     * @group metadata
     * @group common
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notBoolean
     * @expectedException InvalidArgumentException
     *
     * @param mixed $notBoolean
     */
    public function is_default_must_be_a_boolean($notBoolean)
    {
        new IndexedEndpoint($this->getPostEndpoint(), 1, $notBoolean);
    }

    /**
     * @test
     * @group metadata
     * @group common
     */
    public function binding_can_be_retrieved()
    {
        $binding = Binding::soap();

        $indexedEndpoint = new IndexedEndpoint(new Endpoint($binding, 'some:uri'), 1, true);

        $this->assertEquals($binding, $indexedEndpoint->getBinding());
    }

    /**
     * @test
     * @group metadata
     * @group common
     */
    public function location_can_be_retrieved()
    {
        $location = 'some:uri:somewhere';

        $indexedEndpoint = new IndexedEndpoint(new Endpoint(Binding::httpRedirect(), $location), 1, true);

        $this->assertEquals($location, $indexedEndpoint->getLocation());
    }

    /**
     * @test
     * @group metadata
     * @group common
     */
    public function response_location_can_be_retrieved()
    {
        $responseLocation = 'some:response:location';

        $indexedEndpoint = new IndexedEndpoint(new Endpoint(Binding::soap(), 'some:uri', $responseLocation), 1, false);
        $noResponseLocation = new IndexedEndpoint(new Endpoint(Binding::soap(), 'some:uri'), 1, false);
        $nullResponseLocation = new IndexedEndpoint(new Endpoint(Binding::soap(), 'some:uri', null), 1, false);

        $this->assertEquals($responseLocation, $indexedEndpoint->getResponseLocation());
        $this->assertNull($noResponseLocation->getResponseLocation());
        $this->assertNull($nullResponseLocation->getResponseLocation());
    }

    /**
     * @test
     * @group metadata
     * @group common
     */
    public function index_can_be_retrieved()
    {
        $index = 9;

        $indexedEndpoint = new IndexedEndpoint($this->getPostEndpoint(), $index);

        $this->assertEquals($index, $indexedEndpoint->getIndex());
    }

    /**
     * @test
     * @group metadata
     * @group common
     */
    public function is_default_can_be_queried()
    {
        $default = new IndexedEndpoint($this->getPostEndpoint(), 1, true);
        $notDefault = new IndexedEndpoint($this->getPostEndpoint(), 1, false);
        $noDefault = new IndexedEndpoint($this->getPostEndpoint(), 1);

        $this->assertTrue($default->isDefault());
        $this->assertFalse($notDefault->isDefault());
        $this->assertFalse($noDefault->isDefault());
    }

    /**
     * @test
     * @group metadata
     * @group common
     */
    public function equality_is_verified_on_endpoint_index_and_is_default()
    {
        $base = new IndexedEndpoint($this->getPostEndpoint(), 1);
        $same = new IndexedEndpoint($this->getPostEndpoint(), 1);
        $differentEndpoint = new IndexedEndpoint(new Endpoint(Binding::httpRedirect(), 'some:uri'), 1);
        $differentIndex = new IndexedEndpoint($this->getPostEndpoint(), 9);
        $differentDefault = new IndexedEndpoint($this->getPostEndpoint(), 1, true);

        $this->assertTrue($base->equals($same));
        $this->assertFalse($base->equals($differentEndpoint));
        $this->assertFalse($base->equals($differentIndex));
        $this->assertFalse($base->equals($differentDefault));
    }

    /**
     * @test
     * @group metadata
     * @group common
     */
    public function deserializing_a_serialized_indexed_endpoint_yields_an_equal_value_object()
    {
        $original = new IndexedEndpoint($this->getPostEndpoint(), 2, false);

        $deserialized = IndexedEndpoint::deserialize($original->serialize());

        $this->assertTrue($original->equals($deserialized));
    }

    /**
     * @test
     * @group metadata
     * @group common
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notArray
     * @expectedException InvalidArgumentException
     *
     * @param mixed $notArray
     */
    public function deserialization_requires_data_to_be_an_array($notArray)
    {
        IndexedEndpoint::deserialize($notArray);
    }

    /**
     * @test
     * @group metadata
     * @group common
     *
     * @dataProvider invalidDataProvider
     * @expectedException InvalidArgumentException
     *
     * @param mixed $invalidData
     */
    public function deserialization_requires_the_presence_of_all_required_keys($invalidData)
    {
        IndexedEndpoint::deserialize($invalidData);
    }

    /**
     * @return array
     */
    public function invalidDataProvider()
    {
        $endpoint = new Endpoint(Binding::httpPost(), 'some:uri', 'some:response:location');

        return array(
            'no matching keys' => array(array('ep' => $endpoint->serialize(), 'foo' => 1, 'bar' => false)),
            'no endpoint'      => array(array('index' => 1, 'is_default' => true)),
            'no index'         => array(array('endpoint' => $endpoint->serialize(), 'is_default' => false)),
            'no is_default'    => array(array('endpoint' => $endpoint->serialize(), 'index' => 2))
        );
    }

    /**
     * @test
     * @group metadata
     * @group common
     */
    public function an_indexed_endpoint_can_be_cast_to_string()
    {
        $indexedEndpoint = new IndexedEndpoint($this->getPostEndpoint(), 1, true);

        $this->assertInternalType('string', (string) $indexedEndpoint);
    }

    /**
     * @return Endpoint
     */
    private function getPostEndpoint()
    {
        return new Endpoint(Binding::httpPost(), 'some:uri');
    }
}
