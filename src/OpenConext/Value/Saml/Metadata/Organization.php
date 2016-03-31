<?php

namespace OpenConext\Value\Saml\Metadata;

use OpenConext\Value\Assert\Assertion;
use OpenConext\Value\Saml\Metadata\Organization\OrganizationDisplayNameList;
use OpenConext\Value\Saml\Metadata\Organization\OrganizationNameList;
use OpenConext\Value\Saml\Metadata\Organization\OrganizationUrlList;
use OpenConext\Value\Serializable;

final class Organization implements Serializable
{
    /**
     * @var OrganizationNameList
     */
    private $organizationNames;

    /**
     * @var OrganizationDisplayNameList
     */
    private $organizationDisplayNames;

    /**
     * @var OrganizationUrlList
     */
    private $organizationUrls;

    public function __construct(
        OrganizationNameList $organizationNames,
        OrganizationDisplayNameList $organizationDisplayNames,
        OrganizationUrlList $organizationUrls
    ) {
        Assertion::min(count($organizationNames), 1, 'At least one OrganizationName is required');
        Assertion::min(count($organizationDisplayNames), 1, 'At least one OrganizationDisplayName is required');
        Assertion::min(count($organizationUrls), 1, 'At least one OrganizationUrl is required');

        $this->organizationNames        = $organizationNames;
        $this->organizationDisplayNames = $organizationDisplayNames;
        $this->organizationUrls         = $organizationUrls;
    }

    /**
     * @param Organization $other
     * @return bool
     */
    public function equals(Organization $other)
    {
        return $this->organizationNames->equals($other->organizationNames)
                && $this->organizationDisplayNames->equals($other->organizationDisplayNames)
                && $this->organizationUrls->equals($other->organizationUrls);
    }

    /**
     * @return OrganizationNameList
     */
    public function getOrganizationNames()
    {
        return $this->organizationNames;
    }

    /**
     * @return OrganizationDisplayNameList
     */
    public function getOrganizationDisplayNames()
    {
        return $this->organizationDisplayNames;
    }

    /**
     * @return OrganizationUrlList
     */
    public function getOrganizationUrls()
    {
        return $this->organizationUrls;
    }

    public static function deserialize($data)
    {
        Assertion::isArray($data);
        Assertion::keysExist($data, array('organization_names', 'organization_display_names', 'organization_urls'));

        return new self(
            OrganizationNameList::deserialize($data['organization_names']),
            OrganizationDisplayNameList::deserialize($data['organization_display_names']),
            OrganizationUrlList::deserialize($data['organization_urls'])
        );
    }

    public function serialize()
    {
        return array(
            'organization_names' => $this->organizationNames->serialize(),
            'organization_display_names' => $this->organizationDisplayNames->serialize(),
            'organization_urls' => $this->organizationUrls->serialize()
        );
    }

    public function __toString()
    {
        return sprintf(
            'Organization(OrganizationNames=%s, OrganizationDisplayNames=%, OrganizationULRs=%s',
            $this->organizationNames,
            $this->organizationDisplayNames,
            $this->organizationUrls
        );
    }
}
