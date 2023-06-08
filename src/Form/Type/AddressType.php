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
			->add('name', TextType::class, ['label' => 'address.fields.name'])
			->add('address', AddressAutocompleteType::class, [
				'label' => 'address.fields.address',
				'attr' => ['autocomplete' => uniqid('noautocomplete')],
				'api' => $options['api'],
			])
			->add('suite', TextType::class, [
				'label' => 'address.fields.suite',
				'required' => false
			])
			->add('city', TextType::class, ['label' => 'address.fields.city'])
			->add('province', TextType::class, ['label' => 'address.fields.province'])
			->add('country', TextType::class, ['label' => 'address.fields.country'])
			->add('postalCode', TextType::class, ['label' => 'address.fields.postal_code'])
			->add('phoneNumber', TextType::class, [
				'label' => 'address.fields.phone_number',
				'required' => false
			])
			->add('faxNumber', TextType::class, [
				'label' => 'address.fields.fax_number',
				'required' => false
			])
		;
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => null,
			'api' => AddressCompleteApi::API_NAME,
			'attr' => ['data-widget' => 'form-validate'],
		]);
	}
}
