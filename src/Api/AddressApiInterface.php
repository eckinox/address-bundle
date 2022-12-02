<?php

namespace Eckinox\AddressBundle\Api;

use Eckinox\AddressBundle\Model\AbstractAddress;
use Eckinox\AddressBundle\Model\AbstractPrediction;

interface AddressApiInterface
{
	/**
	 * @return array<AbstractPrediction>
	 */
	public function getPredictions(string $searchQuery, string $previousId): array;

	public function getAddressDetails(string $placeId): AbstractAddress;
}
