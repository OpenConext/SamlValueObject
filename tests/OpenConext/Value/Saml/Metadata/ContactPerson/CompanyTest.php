<?php

namespace OpenConext\Value\Saml\Metadata\ContactPerson;

use OpenConext\Value\Exception\InvalidArgumentException;
use PHPUnit_Framework_TestCase as UnitTest;

class CompanyTest extends UnitTest
{
    /**
     * @test
     * @group metadata
     * @group contactperson
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notStringOrEmptyString
     * @expectedException InvalidArgumentException
     *
     * @param mixed $invalidValue
     */
    public function only_non_empty_strings_are_valid_companies($invalidValue)
    {
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
    public function a_company_can_be_cast_to_string()
    {
        $companyName = 'OpenConext.org';

        $company = new Company($companyName);

        $this->assertEquals($companyName, (string) $company);
    }
}
