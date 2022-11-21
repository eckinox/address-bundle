<?php

namespace Eckinox\AddressBundle\Api\GooglePlaces;

use App\Api\ResponseHandlingTrait;
use Psr\Log\LoggerInterface;
use App\Entity\Purchase\Item;
use App\Entity\Purchase\Order;
use App\Model\Purchase\ProductInterface;
use App\Api\Synnex\Provider\AuthenticationProvider;
use App\Publisher\ApiStatusPublisher;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Eckinox\AddressBundle\Api\AddressApiInterface;
use Eckinox\AddressBundle\Api\GooglePlaces\Model\Address;
use Eckinox\AddressBundle\Api\GooglePlaces\Model\Prediction;

class GooglePlacesApi implements AddressApiInterface {
	use ResponseHandlingTrait;

	private HttpClientInterface $client;

	public function __construct(
		HttpClientInterface $googlePlaces,
		private LoggerInterface $logger,
		private string $apiKey,
	) {
		$this->client = $googlePlaces;
	}

	/**
     * @return array<Prediction>
     */
	public function getPredictions(string $searchQuery, string $previousId): array
	{
		$response = $this->client->request(
            'GET',
            "autocomplete/json?key={$this->apiKey}&input={$searchQuery}"
        );
		
		$responseContent = $this->handleResponse($response);
		$predictions = $responseContent->predictions;

        $formattedPredictions = [];

        foreach ($predictions as $predictionData) {
			$prediction = new Prediction($predictionData);
			$formattedPredictions[] = $prediction;
        }

		return $formattedPredictions;
	}

	/**
     * @return Address
     */
    public function getAdressDetails(?string $placeId): Address
	{
		$placeId = $placeId;

		$response = $this->client->request(
            'GET',
            "details/json?key={$this->apiKey}&place_id={$placeId}"
        );
		
		$responseContent = $this->handleResponse($response);
		$placeDetails = $responseContent->result;
		
        $formattedAddressComponents = $this->formatAddressComponents($placeDetails);

		return new Address($formattedAddressComponents);
	}

	private function handleResponse(object $response): ?object
	{
        if ($response->getStatusCode() === 200) {
            $content = json_decode($response->getContent());
            return $content;
        } else {
            $this->logger->critical('Google Places API: Error - Code ' . $response->getStatusCode());
            return null;
        }
    }

    public function formatAddressComponents(object $addressDetails): array
    {
		$addressComponentsByType = [];
		foreach ($addressDetails->address_components as $addressComponent) {
			$addressComponentsByType[$addressComponent->types[0]] = $addressComponent;
		}
        
		return $addressComponentsByType;
    }
}