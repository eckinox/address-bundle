<?php

namespace Eckinox\AddressBundle\Api\AddressComplete;

use Eckinox\AddressBundle\Api\AddressApiInterface;
use Eckinox\AddressBundle\Api\AddressComplete\Model\Address;
use Eckinox\AddressBundle\Api\AddressComplete\Model\Prediction;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AddressCompleteApi implements AddressApiInterface
{
	public const API_NAME = 'addressComplete';

	private HttpClientInterface $client;

	public function __construct(
		private LoggerInterface $logger,
		private RequestStack $requestStack,
		private string $apiKey,
		HttpClientInterface $addressComplete,
	) {
		$this->client = $addressComplete;
	}

	/**
	 * @return array<Prediction>
	 */
	public function getPredictions(string $searchQuery, string $previousId): array
	{
		$urlEncodedSearchQuery = urlencode($searchQuery);
		$urlEncodedPreviousId = urlencode($previousId);

		$locale = $this->requestStack->getCurrentRequest()->getLocale();

		// LanguagePreference doesn't seem to work, parameter is here in case it gets fixed in the future
		$response = $this->client->request(
			'GET',
			"Find/2.1/json.ws?key={$this->apiKey}&SearchTerm={$urlEncodedSearchQuery}&LastId={$urlEncodedPreviousId}&LanguagePreference={$locale}"
		);

		$predictions = $this->handleResponse($response);
		$formattedPredictions = [];

		foreach ($predictions as $predictionData) {
			$prediction = Prediction::fromAddressCompleteResult($predictionData);
			$formattedPredictions[] = $prediction;
		}

		return $formattedPredictions;
	}

	public function getAddressDetails(?string $placeId): Address
	{
		$urlEncodedPlaceId = urlencode($placeId);

		$response = $this->client->request(
			'GET',
			"Retrieve/2.1/json.ws?key={$this->apiKey}&id={$urlEncodedPlaceId}"
		);

		$responseContent = $this->handleResponse($response);
		$translatedAddressComponents = $this->getAddressComponentsBasedOnLang($responseContent);

		return Address::fromAddressCompleteResult($translatedAddressComponents);
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
		$locale = $this->requestStack->getCurrentRequest()->getLocale();
		$lang = strpos($locale, 'fr') !== false ? 'FRE' : 'ENG'; // addressComplete only supports FR and EN

		foreach ($addressComponentsByLang as $addressComponents) {
			if ($addressComponents->Language === $lang) {
				return $addressComponents;
			}
		}

		return null;
	}
}
