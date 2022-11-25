# AddressBundle

Le AddressBundle offre la possibilité d'avoir des champs d'auto-complétion utilisant différentes API pour les formulaires contenant des adresses.

![Exemple d'utilisation](demo/example.gif)

## Installation du bundle

1. Configurez le repository avec Github dans le `composer.json` de votre projet:
```json
	"repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/eckinox/address-bundle"
        }
	]
```
2. Installez le bundle via Composer:
```bash
composer require eckinox/address-bundle
```

3. Ajoutez le secret:
```bash
bin/console secrets:set INSERT_CHOSEN_API_NAME_HERE_API_KEY
```
Remplacez `INSERT_CHOSEN_API_NAME` pour l'api choisi. Les api disponibles sont les suivantes: `GOOGLE_PLACES`, `ADDRESS_COMPLETE`.

Pour générer votre clé d'api, veuillez vous référer aux articles ci-dessous:
- [How to install | AddressComplete | Canada Post](https://www.canadapost-postescanada.ca/ac/support/setup-guides/#create-an-api-key)
- [Use API Keys with Places API  |  Google Developers](https://developers.google.com/maps/documentation/places/web-service/get-api-key)

## Comment utiliser _Address Bundle_

### Créer l'entité

Tout d'abord, créer l'entité qui sera utilisée pour enregistrer les adresses. Pour ce faire, vous n'avez qu'à extend la classe abstraite fournie par le bundle comme suis:

```php
use Eckinox\AddressBundle\Entity\AbstractAddress;

class MyAddressClass extends AbstractAddress
{
    // Just add other needed fields like usual
}
```

Voici la liste des propriétés définies dans `AbstractAddress`:

```php
/**
 * @ORM\Id
 * @ORM\GeneratedValue
 * @ORM\Column(type="integer")
 *
 * @var int
 */
protected $id;

/**
 * @ORM\Column(type="string", length=255)
 *
 * @var string
 */
protected $name;

/**
 * @ORM\Column(type="string", length=255)
 *
 * @var string
 */
protected $address;

/**
 * @ORM\Column(type="string", length=255)
 *
 * @var string
 */
protected $city;

/**
 * @ORM\Column(type="string", length=255)
 *
 * @var string
 */
protected $province;

/**
 * @ORM\Column(type="string", length=255)
 *
 * @var string
 */
protected $postalCode;
```

### Utiliser l'auto-complete dans les formulaires

Pour utiliser le formulaire de base créé par le bundle (*parfait si vous utilisez seulement les champs définis dans `AbstractAddress`*):
1. Vous n'avez qu'à utiliser `Eckinox\AddressBundle\Form\Type\AddressType;` dans le formulaire où vous désirez le form.
2. Puis, utiliser `AddressType` et passer l'entité d'`address` désiré à l'aide du paramètre `entry_options` puis lui passer `'data_class' => MyAddressClass::class,` (voir l'exemple ci-dessous).
3. Ajouter, au besoin, l'api désiré avec l'option `api`, l'api de Poste Canada "Address Complete" sera utilisé par défaut.

```php
use Eckinox\AddressBundle\Form\Type\AddressType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Address;

class MyFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
			// Add fields like you usually would. 
			->add('addresses', CollectionListType::class, [
				'entry_type' => AddressType::class,
				'entry_options' => [
					'data_class' => Address::class,
					'api' => 'addressComplete', 
				],
				'by_reference' => false,
			])
        ;
    }
}
```

Pour utiliser le champ `AddressAutocompleteType` dans vos `FormType`, vous devez:
1. Ajouter `Eckinox\AddressBundle\Form\Type\AddressAutocompleteType` dans vos use.
2. Ajouter votre champ `AddressAutocompleteType` comme vous le feriez avec n'importe quel autre champ.
3. Ajouter les paramètres désirés:
	1. Il est recommandé de désactiver l'autocomplete du champ pour éviter que les propositions du navigateur passent par-dessus les propositions d'autocomplete. Vous n'avez qu'à ajouter l'attribut suivant au champ: `'autocomplete' => uniqid('noautocomplete')`.
	2. Il est possible de passer un paramètre `api` pour choisir l'api à utiliser. Les choix disponibles sont: `addressComplete` ou `googlePlaces`. Si aucun api est spécifié, l'api de Poste Canada "Address Complete" sera utilisé par défaut.

Voici un exemple:
```php
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Eckinox\AddressBundle\Form\Type\AddressAutocompleteType;

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
				'api' => 'addressComplete', // addressComplete || googlePlaces
			])
			->add('city', TextType::class)
			->add('province', TextType::class)
			->add('postalCode', TextType::class)
		;
	}
```

4. Une fois l'auto-complete ajouté au formulaire, il est possible d'utiliser l'event javascript `populate-address` pour remplir les champs avec les détails de l'adresse (qui se retrouvent dans `event.detail`). L'event est déclanché sur l'élément de row du formulaire, donc dans ce cas-ci, sur `<tr>`. Voici l'event listener utilisé par la liste par défaut qui peut être redéfini et ajusté pour vos besoins spécifiques:

```javascript
this.row.addEventListener('populate-address', (event) => {
	const address = this.input;
	let city = this.row.querySelector('*[data-field-name="city"] input');
	let province = this.row.querySelector('*[data-field-name="province"] input');
	let postalCode = this.row.querySelector('*[data-field-name="postalCode"] input');

	// in the case where the form would be displayed in a modal
	if (city == null || province == null || postalCode == null) {
		city = this.row.querySelector('*.city input');
		province = this.row.querySelector('*.province input');
		postalCode = this.row.querySelector('*.postal-code input');
	}

	address.value = event.detail.address;
	city.value = event.detail.city;
	province.value = event.detail.province;
	postalCode.value = event.detail.postalCode;
});
```

## Fonctionnement

Lorsqu'un champ de type `AddressAutocompleteType` est utilisé dans un formulaire, l'input classique est remplacé par un webcomponent d'autocomplete qui contient le champ utilisé pour la rechercher et la liste qui receveras les résultats de l'api.

Une fois qu'un choix est sélectionné, une autre requête à l'api est lancée et les détails de l'adresse sont retournés. Un event javascript est déclenché et les autres champs d'adresse sont alors remplis.
