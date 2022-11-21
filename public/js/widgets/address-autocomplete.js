let stylesInitialized = false;

class AddressAutocomplete extends HTMLElement {
	constructor()
	{
		super();
		this.input = null;
		this.wrapper = null;
		this.row = null;
		this.autocompleteChoices = null;
		this.api = '';
		this.getPredictionsRoute = Routing.generate('bgm_ajax_get_address_predictions', {});
		this.getDetailsRoute = Routing.generate('bgm_ajax_get_address_details', {});

		this.init();
	}

	init()
	{
		this.input = this.querySelector('input');
		this.wrapper = this;
		this.row = this.parentNode.parentNode;
		this.autocompleteChoices = this.querySelector('.autocomplete-choices');
		this.api = this.dataset.api;

		this.querySelector('.loading-overlay').setAttribute('style', '');

		this.initEvents();
		this._injectCSS();
	}

	initEvents()
	{
		let userInputDelay = null;
		// get address predictions based on the user's input
		this.input.addEventListener('input', () => {
			clearTimeout(userInputDelay);
			// prevent the request from being sent if value is empty
			if (this.input.value == "") {
				return;
			}
			// Add a timeout to prevent multiple requests being sent while the user is typing
			userInputDelay = setTimeout(() => {
				this.classList.add('loading');

				fetch(`${this.getPredictionsRoute}?search=${this.input.value}&api=${this.api}`, {method: 'GET'})
					.then(response => response.json())
					.then(choices => { 
						this.classList.remove('loading');
						this.autocompleteChoices.innerHTML = '';

						this.generateChoices(choices);
					});
			}, 800);
		});

		// once a prediction is clicked, get address infos or more precise predictions based on the action
		this.wrapper.addEventListener('click', event => {

			if (!event.target.classList.contains('list-group-item')) {
				return;
			}

			event.stopPropagation();

			const clickedAddressChoice = event.target;
			const action = clickedAddressChoice.dataset.action;

			this.classList.add('loading');
			
			fetch(`${this.getDetailsRoute}?search=${this.input.value}&id=${clickedAddressChoice.id}&action=${action}&api=${this.api}`, {method: 'GET'})
				.then(response => response.json())
				.then(data => {
					// data here is either more precise choices or address details
					this.classList.remove('loading');

					if (action === 'Find') {
						this.generateChoices(data);
					} else {
						// if it's address details, trigger a custom event with all the needed infos
						this.autocompleteChoices.innerHTML = '';
						this.row.dispatchEvent(new CustomEvent('populate-address', {"detail": data}));
					}
				});
		});

		this.row.addEventListener('populate-address', (event) => {
			const address = this.input;
			const city = this.row.querySelector('td[data-field-name="city"] input');
			const province = this.row.querySelector('td[data-field-name="province"] input');
			const postalCode = this.row.querySelector('td[data-field-name="postalCode"] input');

			address.value = event.detail.address;
			city.value = event.detail.city;
			province.value = event.detail.province;
			postalCode.value = event.detail.postalCode;
		});

		window.addEventListener('click', () => {
			this.autocompleteChoices.innerHTML = '';
		});
	}

	generateChoices(choices)
	{
		for (const choice of choices) {
			this.autocompleteChoices.insertAdjacentHTML(
				'beforeend',
				`<small id="${choice.id}" class="list-group-item list-group-item-action" data-action="${choice.action}">
					${choice.displayName}
				</small>`
			);
		}
	}

	_injectCSS()
	{
		if (stylesInitialized) {
			return;
		}

		document.head.insertAdjacentHTML("afterbegin", `
			<style>
				address-autocomplete { position: relative; }
				address-autocomplete .autocomplete-choices-wrapper { position: absolute; top: 100%; left: 0; width: 100%; max-height: 215px; overflow-y: auto; cursor: pointer; }
				address-autocomplete .loading-overlay { pointer-events: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: #33333377; display: flex; align-items: center; justify-content: center; transition: opacity .2s ease-in-out; opacity: 0; }
				address-autocomplete .loading-overlay .spinner-border { opacity: 0.3; }

				address-autocomplete.loading .loading-overlay { opacity: 1; }
			</style>
		`);

		stylesInitialized = true;
	}
}

customElements.define('address-autocomplete', AddressAutocomplete);