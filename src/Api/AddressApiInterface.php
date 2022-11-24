<?php

namespace Eckinox\AddressBundle\Api;

interface AddressApiInterface
{
	/**
	 * @return array<object>
	 *                       returns an array of Prediction
	 */
	public function getPredictions(string $searchQuery, string $previousId): array;

	/**
	 * @return object
	 *                returns an Address
	 */
	public function getAdressDetails(string $placeId): object;
}
