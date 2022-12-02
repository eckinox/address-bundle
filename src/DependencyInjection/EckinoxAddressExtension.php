<?php

namespace Eckinox\AddressBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class EckinoxAddressExtension extends Extension implements PrependExtensionInterface
{
	public function load(array $configs, ContainerBuilder $container): void
	{
		$configs = $this->processConfiguration($this->getConfiguration([], $container), $configs);
		$loader = new YamlFileLoader(
			$container,
			new FileLocator(__DIR__.'/../../config')
		);

		$loader->load('services.yaml');
	}

	public function prepend(ContainerBuilder $container): void
	{
		$container->prependExtensionConfig('twig', [
			'form_themes' => ['@EckinoxAddress/form/address_autocomplete.html.twig'],
		]);

		$container->prependExtensionConfig('framework', [
			'http_client' => [
				'scoped_clients' => [
					'googlePlaces' => [
						'base_uri' => 'https://maps.googleapis.com/maps/api/place/',
					],
					'addressComplete' => [
						'base_uri' => 'https://ws1.postescanada-canadapost.ca/addresscomplete/interactive/',
					],
				],
			],
		]);
	}
}
