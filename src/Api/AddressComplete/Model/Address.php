<?php

namespace Eckinox\AddressBundle\Api\AddressComplete\Model;

use Eckinox\AddressBundle\Model\AbstractAddress;

class Address extends AbstractAddress {
    public function __construct(object $data) {
        $this->address = $data->Line1;
        $this->city = $data->City;
        $this->province = $data->ProvinceName;
        $this->postalCode = $data->PostalCode;
        $this->country = $data->CountryName;
    }
}