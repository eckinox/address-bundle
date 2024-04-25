<?php

namespace Eckinox\AddressBundle\Api\AddressComplete\Model;

use Eckinox\AddressBundle\Model\AbstractPrediction;

class Prediction extends AbstractPrediction
{
    public static function fromAddressCompleteResult(object $result): Prediction
    {
        $prediction = new Prediction();

        $prediction->id = $result->Id;
        $prediction->displayName = $result->Text.", ".$result->Description;
        $prediction->action = $result->Next;

        return $prediction;
    }
}
