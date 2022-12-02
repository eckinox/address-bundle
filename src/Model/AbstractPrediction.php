<?php

namespace Eckinox\AddressBundle\Model;

abstract class AbstractPrediction implements PredictionInterface
{
	protected string $id;
	protected string $displayName;
	protected string $action;

	public function getId(): string
	{
		return $this->id;
	}

	public function getDisplayName(): string
	{
		return $this->displayName;
	}

	public function getAction(): string
	{
		return $this->action;
	}
}
