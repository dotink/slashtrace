<?php

namespace SlashTrace\Context;

use InvalidArgumentException;
use JsonSerializable;

class User implements JsonSerializable
{
	/**
	 * @var string|null
	 */
	private $id;

	/**
	 * @var string|null
	 */
	private $email;

	/**
	 * @var string|null
	 */
	private $name;


	/**
	 *
	 */
	public function getId(): ?string
	{
		return $this->id;
	}

	/**
	 * @param string $id
	 */
	public function setId(?string $id): self
	{
		$this->id = $id;

		return $this;
	}

	/**
	 *
	 */
	public function getEmail(): ?string
	{
		return $this->email;
	}

	/**
	 *
	 */
	public function setEmail(?string $email): self
	{
		if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
			throw new InvalidArgumentException("Invalid email address");
		}

		$this->email = $email;

		return $this;
	}

	/**
	 *
	 */
	public function getName(): ?string
	{
		return $this->name;
	}

	/**
	 *
	 */
	public function setName(?string $name): self
	{
		$this->name = $name;

		return $this;
	}


	/**
	 * @return array<mixed, string|null>
	 */
	public function jsonSerialize(): array
	{
		return array_filter([
			"id"    => $this->getId(),
			"email" => $this->getEmail(),
			"name"  => $this->getName(),
		]);
	}
}