<?php

namespace Eckinox\AddressBundle\Api\GooglePlaces\Model;

use Eckinox\AddressBundle\Model\AbstractPrediction;

class Prediction extends AbstractPrediction
{
	public function __construct(object $data)
	{
		$this->id = $data->place_id;
		$this->displayName = $data->description;
		$this->action = "Retrieve";
	}
}
