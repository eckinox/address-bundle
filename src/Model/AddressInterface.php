<?php

namespace Eckinox\AddressBundle\Model;

interface AddressInterface
{
	public function getAddress(): string;

	public function getCity(): string;

	public function getProvince(): string;

	public function getPostalCode(): string;

	public function getCountry(): string;
}
