<?php

namespace Eckinox\AddressBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class AbstractAddress
{
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

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function setName(string $name): self
	{
		$this->name = $name;

		return $this;
	}

	public function getAddress(): ?string
	{
		return $this->address;
	}

	public function setAddress(string $address): self
	{
		$this->address = $address;

		return $this;
	}

	public function getCity(): ?string
	{
		return $this->city;
	}

	public function setCity(string $city): self
	{
		$this->city = $city;

		return $this;
	}

	public function getProvince(): ?string
	{
		return $this->province;
	}

	public function setProvince(string $province): self
	{
		$this->province = $province;

		return $this;
	}

	public function getPostalCode(): ?string
	{
		return $this->postalCode;
	}

	public function setPostalCode(string $postalCode): self
	{
		$this->postalCode = $postalCode;

		return $this;
	}
}
