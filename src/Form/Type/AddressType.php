<?php

namespace Eckinox\AddressBundle\Form\Type;

use Eckinox\AddressBundle\Api\AddressComplete\AddressCompleteApi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('name', TextType::class)
			->add('address', AddressAutocompleteType::class, [
				'attr' => [
					'autocomplete' => uniqid('noautocomplete'),
				],
				'api' => $options['api'],
			])
			->add('city', TextType::class)
			->add('province', TextType::class)
			->add('postalCode', TextType::class)
		;
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => null,
			'api' => AddressCompleteApi::API_NAME,
			'attr' => [
				'data-widget' => 'form-validate',
			],
		]);
	}
}
