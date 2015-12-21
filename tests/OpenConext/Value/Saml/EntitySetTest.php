<?php

namespace OpenConext\Value\Saml;

use PHPUnit_Framework_TestCase as TestCase;

class EntitySetTest extends TestCase
{
    /**
     * @param array $firstSet
     * @param array $secondSet
     *
     * @test
     * @group        saml
     * @dataProvider unequalSets
     */
    public function set_with_different_elements_are_not_considered_equal(array $firstSet, array $secondSet)
    {
        $base  = new EntitySet($firstSet);
        $other = new EntitySet($secondSet);

        $this->assertFalse($base->equals($other));
    }

    public function unequalSets()
    {
        return array(
            'Different elements' => array(
                array(new Entity(new EntityId('a'), EntityType::SP()), new Entity(new EntityId('a'), EntityType::IdP())),
                array(new Entity(new EntityId('b'), EntityType::IdP())),
            ),
            'First set contains second set' => array(
                array(new Entity(new EntityId('a'), EntityType::SP()), new Entity(new EntityId('a'), EntityType::IdP())),
                array(new Entity(new EntityId('a'), EntityType::SP())),
            ),
            'Different EntityType' => array(
                array(new Entity(new EntityId('a'), EntityType::IdP())),
                array(new Entity(new EntityId('a'), EntityType::SP())),
            ),
            'Second set is empty' => array(
                array(new Entity(new EntityId('a'), EntityType::IdP())),
                array(),
            ),
            'First set is empty' => array(
                array(),
                array(new Entity(new EntityId('a'), EntityType::IdP())),
            ),
        );
    }

    /**
     * @param array $firstSet
     * @param array $secondSet
     *
     * @test
     * @group        saml
     * @dataProvider equalSets
     */
    public function set_with_equal_elements_are_considered_equal(array $firstSet, array $secondSet)
    {
        $base  = new EntitySet($firstSet);
        $other = new EntitySet($secondSet);

        $this->assertTrue($base->equals($other));
    }

    public function equalSets()
    {
        return array(
            'Same Entities' => array(
                array(new Entity(new EntityId('a'), EntityType::IdP())),
                array(new Entity(new EntityId('a'), EntityType::IdP())),
            ),
            'Both emtpy' => array(
                array(),
                array(),
            ),
            'Same Entities due to deduplication in the first set' => array(
                array(new Entity(new EntityId('a'), EntityType::SP()), new Entity(new EntityId('a'), EntityType::SP())),
                array(new Entity(new EntityId('a'), EntityType::SP())),
            ),
            'Same Entities, different Sequence' => array(
                array(new Entity(new EntityId('a'), EntityType::IdP()), new Entity(new EntityId('b'), EntityType::SP())),
                array(new Entity(new EntityId('b'), EntityType::SP()), new Entity(new EntityId('a'), EntityType::IdP())),
            ),
        );
    }

    /**
     * @test
     * @group saml
     */
    public function elements_in_a_set_can_be_tested_for_presence_based_on_equality()
    {
        $entityInSetOne = new Entity(new EntityId('RUG'), EntityType::SP());
        $entityInSetTwo = new Entity(new EntityId('HU'), EntityType::IdP());
        $entityNotInSet = new Entity(new EntityId('UM'), EntityType::IdP());

        $entitySet = new EntitySet(array($entityInSetOne, $entityInSetTwo));

        $this->assertTrue($entitySet->contains($entityInSetOne));
        $this->assertTrue($entitySet->contains(new Entity(new EntityId('HU'), EntityType::IdP())));
        $this->assertFalse($entitySet->contains($entityNotInSet));
    }

    /**
     * @test
     * @group saml
     */
    public function entity_set_deduplicates_equal_elements()
    {
        $entity            = new Entity(new EntityId('RUG'), EntityType::SP());
        $differentInstance = new Entity(new EntityId('RUG'), EntityType::SP());

        $entitySet = new EntitySet(array($entity, $entity, $differentInstance));

        $this->assertCount(1, $entitySet);
    }
}
