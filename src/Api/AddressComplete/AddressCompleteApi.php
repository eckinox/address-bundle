<?php

namespace Eckinox\AddressBundle\Api\AddressComplete;

use App\Api\ResponseHandlingTrait;
use Eckinox\AddressBundle\Api\AddressApiInterface;
use Eckinox\AddressBundle\Api\AddressComplete\Model\Address;
use Eckinox\AddressBundle\Api\AddressComplete\Model\Prediction;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AddressCompleteApi implements AddressApiInterface
{
	use ResponseHandlingTrait;

	private HttpClientInterface $client;

	private string $language;

	public function __construct(
		HttpClientInterface $addressComplete,
		private LoggerInterface $logger,
		private string $apiKey,
	) {
		$this->client = $addressComplete;
		$this->language = "FRE"; // this should also be based on config
	}

	/**
	 * @return array<Prediction>
	 */
	public function getPredictions(string $searchQuery, string $previousId): array
	{
		$urlEncodedSearchQuery = urlencode($searchQuery);
		$urlEncodedPreviousId = urlencode($previousId);

		// LanguagePreference doesn't seem to work, parameter is here in case it gets fixed in the future
		$response = $this->client->request(
			'GET',
			"Find/2.1/json.ws?key={$this->apiKey}&SearchTerm={$urlEncodedSearchQuery}&LastId={$urlEncodedPreviousId}&LanguagePreference=fr-ca"
		);

		$predictions = $this->handleResponse($response);
		$formattedPredictions = [];

		foreach ($predictions as $predictionData) {
			$prediction = new Prediction($predictionData);
			$formattedPredictions[] = $prediction;
		}

		return $formattedPredictions;
	}

	public function getAdressDetails(?string $placeId): Address
	{
		$urlEncodedPlaceId = urlencode($placeId);

		$response = $this->client->request(
			'GET',
			"Retrieve/2.1/json.ws?key={$this->apiKey}&id={$urlEncodedPlaceId}"
		);

		$responseContent = $this->handleResponse($response);
		$translatedAddressComponents = $this->getAddressComponentsBasedOnLang($responseContent);

		return new Address($translatedAddressComponents);
	}

	/**
	 * @return array<int, object> $content
	 */
	private function handleResponse(object $response): ?array
	{
		if ($response->getStatusCode() === 200) {
			$content = json_decode($response->getContent());

			return $content;
		}
		$this->logger->critical('Address Complete API: Error - Code '.$response->getStatusCode());

		return null;
	}

	/**
	 * @param array<int, object> $addressComponentsByLang
	 */
	private function getAddressComponentsBasedOnLang(array $addressComponentsByLang): ?object
	{
		foreach ($addressComponentsByLang as $addressComponents) {
			if ($addressComponents->Language == $this->language) {
				return $addressComponents;
			}
		}

		return null;
	}
}
