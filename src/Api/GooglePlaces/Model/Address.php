<?php

namespace Eckinox\AddressBundle\Api\GooglePlaces\Model;

use Eckinox\AddressBundle\Model\AbstractAddress;

class Address extends AbstractAddress {
    public function __construct(array $data) {
        $this->address = $data["street_number"]->long_name . " " . $data["route"]->short_name;
        $this->city = $data["locality"]->long_name;
        $this->province = $data["administrative_area_level_1"]->long_name;
        $this->postalCode = $data["postal_code"]->long_name;
        $this->country = $data["country"]->long_name;
    }
}