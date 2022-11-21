<?php

namespace Eckinox\AddressBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\Config\FileLocator;
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
    }

    public function applyPriorities(array $children): array
	{
        foreach($children as $name => $child) {
            if(isset($child['children']) && $child['children']) {
                $children[$name]['children'] = $this->applyPriorities($child['children']);
            }
            if(isset($child['sections']) && $child['sections']) {
                $children[$name]['sections'] = $this->applyPriorities($child['sections']);
            }
        }

        uasort($children, function($a, $b) {
            return $a['priority'] < $b['priority'];
        });

        return $children;
    }
}
