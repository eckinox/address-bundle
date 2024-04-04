<?php

namespace Eckinox\AddressBundle\Api\GooglePlaces\Model;

use Eckinox\AddressBundle\Model\AbstractAddress;

class Address extends AbstractAddress
{
    /**
     * @param array<string, object> $result
     */
    public static function fromPlacesResult(array $result): Address
    {
        $address = new Address();

        $streetNumberAndRoute = [];
        if (isset($result["street_number"])) {
            $streetNumberAndRoute[] = $result["street_number"]->long_name;
        }
        if (isset($result["route"])) {
            $streetNumberAndRoute[] = $result["route"]->short_name;
        }

        $address->address = implode(' ', $streetNumberAndRoute);
        $address->city = $result["locality"]->long_name ?? '';
        $address->province = $result["administrative_area_level_1"]->long_name ?? '';
        $address->postalCode = $result["postal_code"]->long_name ?? '';
        $address->country = $result["country"]->long_name ?? '';

        return $address;
    }
}
