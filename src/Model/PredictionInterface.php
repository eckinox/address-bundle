<?php

namespace Eckinox\AddressBundle\Model;

interface PredictionInterface {
    public function getId(): string;
    public function getDisplayName(): string;
    public function getAction(): string;
}
