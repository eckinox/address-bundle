<?php

namespace Eckinox\AddressBundle\Api;

interface AddressApiInterface {
    /**
     * @return array<Prediction>
     */
    public function getPredictions(string $searchQuery, string $previousId): array;

    /**
     * @return object
     * object is a Address model but the type hint is greifing my ass
     */
    public function getAdressDetails(string $placeId): object;
}