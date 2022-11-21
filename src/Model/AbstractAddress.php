<?php 

namespace Eckinox\AddressBundle\Model;

abstract class AbstractAddress implements AddressInterface {
	protected string $address;
    protected string $city;
    protected string $province;
    protected string $postalCode;
    protected string $country;

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getProvince(): string
    {
        return $this->province;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function getCountry(): string
    {
        return $this->country;
    }
}