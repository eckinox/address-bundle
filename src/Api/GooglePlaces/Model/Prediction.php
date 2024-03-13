<?php

namespace Eckinox\AddressBundle\Api\GooglePlaces\Model;

use Eckinox\AddressBundle\Model\AbstractPrediction;

class Prediction extends AbstractPrediction
{
    public static function fromPlacesResult(object $result): Prediction
    {
        $prediction = new Prediction();

        $prediction->id = $result->place_id;
        $prediction->displayName = $result->description;
        $prediction->action = 'Retrieve';

        return $prediction;
    }
}
