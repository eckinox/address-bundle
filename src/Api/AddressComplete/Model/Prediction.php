<?php

namespace Eckinox\AddressBundle\Api\AddressComplete\Model;

use Eckinox\AddressBundle\Model\AbstractPrediction;

class Prediction extends AbstractPrediction {
    public function __construct(object $data) {
        $this->id = $data->Id;
        $this->displayName = $data->Text . ", " . $data->Description;
        $this->action = $data->Next;
    }
}