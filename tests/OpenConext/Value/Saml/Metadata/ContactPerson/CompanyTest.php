<?php

namespace OpenConext\Value\Saml\Metadata\ContactPerson;

use OpenConext\Value\Exception\InvalidArgumentException;


class CompanyTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     * @group metadata
     * @group contactperson
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notStringOrEmptyString
     *
     * @param mixed $invalidValue
     */
    public function only_non_empty_strings_are_valid_companies($invalidValue)
    {
        $this->expectException(\InvalidArgumentException::class);
        new Company($invalidValue);
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function the_same_companies_are_considered_equal()
    {
        $base      = new Company('OpenConext.org');
        $theSame   = new Company('OpenConext.org');
        $different = new Company('Something Else Corp.');

        $this->assertTrue($base->equals($theSame));
        $this->assertFalse($base->equals($different));
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function the_company_can_be_retrieved()
    {
        $companyName = 'OpenConext.org';

        $company = new Company($companyName);

        $this->assertEquals($companyName, $company->getCompany());
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function deserializing_a_serialized_company_results_in_an_equal_value_object()
    {
        $companyName = 'OpenConext.org';

        $original = new Company($companyName);
        $deserialized = Company::deserialize($original->serialize());

        $this->assertTrue($original->equals($deserialized));
        $this->assertEquals($companyName, $deserialized->getCompany());
    }

    /**
     * @test
     * @group        metadata
     * @group        contactperson
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notStringOrEmptyString
     *
     * @param mixed $invalidData
     */
    public function deserialization_requires_valid_data($invalidData)
    {
        $this->expectException(\InvalidArgumentException::class);
        Company::deserialize($invalidData);
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function a_company_can_be_cast_to_string()
    {
        $companyName = 'OpenConext.org';

        $company = new Company($companyName);

        $this->assertEquals($companyName, (string) $company);
    }
}
