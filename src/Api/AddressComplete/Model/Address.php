<?php

namespace Eckinox\AddressBundle\Api\AddressComplete\Model;

use Eckinox\AddressBundle\Model\AbstractAddress;

class Address extends AbstractAddress
{
	public static function fromAddressCompleteResult(object $result): Address
	{
		$address = new Address();

		$address->address = $result->Line1;
		$address->city = $result->City;
		$address->province = $result->ProvinceName;
		$address->postalCode = $result->PostalCode;
		$address->country = $result->CountryName;

		return $address;
	}
}
