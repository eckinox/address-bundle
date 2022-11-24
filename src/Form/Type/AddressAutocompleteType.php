<?php

namespace Eckinox\AddressBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressAutocompleteType extends AbstractType
{
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'api' => "addressComplete",
		]);
	}

	public function buildView(FormView $view, FormInterface $form, array $options): void
	{
		$view->vars['attr']['data-widget'] = 'address-complete';
		$view->vars['api'] = $options['api'];

		parent::buildView($view, $form, $options);
	}

	public function getParent(): string
	{
		return TextType::class;
	}

	public function getBlockPrefix(): string
	{
		return 'eckinox_address_autocomplete';
	}
}
