<?php

namespace OpenConext\Value\Saml\Metadata\Common;

class IndexedEndpointTest extends \PHPUnit\Framework\TestCase
{
    /**
     *
     *
     * @param mixed $notInteger
     */
    #[\PHPUnit\Framework\Attributes\DataProviderExternal(\OpenConext\Value\TestDataProvider::class, 'notInteger')]
    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function index_must_be_an_integer($notInteger)
    {
        $this->expectException(\InvalidArgumentException::class);
        new IndexedEndpoint($this->getPostEndpoint(), $notInteger);
    }

    /**
     *
     *
     * @param mixed $notBoolean
     */
    #[\PHPUnit\Framework\Attributes\DataProviderExternal(\OpenConext\Value\TestDataProvider::class, 'notBoolean')]
    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function is_default_must_be_a_boolean($notBoolean)
    {
        $this->expectException(\InvalidArgumentException::class);
        new IndexedEndpoint($this->getPostEndpoint(), 1, $notBoolean);
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function binding_can_be_retrieved()
    {
        $binding = Binding::soap();

        $indexedEndpoint = new IndexedEndpoint(new Endpoint($binding, 'some:uri'), 1, true);

        $this->assertEquals($binding, $indexedEndpoint->getBinding());
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function location_can_be_retrieved()
    {
        $location = 'some:uri:somewhere';

        $indexedEndpoint = new IndexedEndpoint(new Endpoint(Binding::httpRedirect(), $location), 1, true);

        $this->assertEquals($location, $indexedEndpoint->getLocation());
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
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

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function index_can_be_retrieved()
    {
        $index = 9;

        $indexedEndpoint = new IndexedEndpoint($this->getPostEndpoint(), $index);

        $this->assertEquals($index, $indexedEndpoint->getIndex());
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function is_default_can_be_queried()
    {
        $default = new IndexedEndpoint($this->getPostEndpoint(), 1, true);
        $notDefault = new IndexedEndpoint($this->getPostEndpoint(), 1, false);
        $noDefault = new IndexedEndpoint($this->getPostEndpoint(), 1);

        $this->assertTrue($default->isDefault());
        $this->assertFalse($notDefault->isDefault());
        $this->assertFalse($noDefault->isDefault());
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
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

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function deserializing_a_serialized_indexed_endpoint_yields_an_equal_value_object()
    {
        $original = new IndexedEndpoint($this->getPostEndpoint(), 2, false);

        $deserialized = IndexedEndpoint::deserialize($original->serialize());

        $this->assertTrue($original->equals($deserialized));
    }

    /**
     *
     *
     * @param mixed $notArray
     */
    #[\PHPUnit\Framework\Attributes\DataProviderExternal(\OpenConext\Value\TestDataProvider::class, 'notArray')]
    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function deserialization_requires_data_to_be_an_array($notArray)
    {
        $this->expectException(\InvalidArgumentException::class);
        IndexedEndpoint::deserialize($notArray);
    }

    /**
     *
     *
     * @param mixed $invalidData
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('invalidDataProvider')]
    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function deserialization_requires_the_presence_of_all_required_keys($invalidData)
    {
        $this->expectException(\InvalidArgumentException::class);
        IndexedEndpoint::deserialize($invalidData);
    }

    /**
     * @return array
     */
    public static function invalidDataProvider()
    {
        $endpoint = new Endpoint(Binding::httpPost(), 'some:uri', 'some:response:location');

        return array(
            'no matching keys' => array(array('ep' => $endpoint->serialize(), 'foo' => 1, 'bar' => false)),
            'no endpoint'      => array(array('index' => 1, 'is_default' => true)),
            'no index'         => array(array('endpoint' => $endpoint->serialize(), 'is_default' => false)),
            'no is_default'    => array(array('endpoint' => $endpoint->serialize(), 'index' => 2))
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function an_indexed_endpoint_can_be_cast_to_string()
    {
        $indexedEndpoint = new IndexedEndpoint($this->getPostEndpoint(), 1, true);

        $this->assertIsString((string) $indexedEndpoint);
    }

    /**
     * @return Endpoint
     */
    private function getPostEndpoint()
    {
        return new Endpoint(Binding::httpPost(), 'some:uri');
    }
}
