<?php

namespace SlashTrace\Context\Breadcrumbs;

use JsonSerializable;
use DateTime;

/**
 *
 */
class Breadcrumb implements JsonSerializable
{
	/**
	 * @param array<string, mixed> $data
	 */
	public function __construct(private readonly string $title, private readonly array $data, private readonly DateTime $dateTime)
    {
    }


	/**
	 *
	 */
	public function getTitle(): string
	{
		return $this->title;
	}


	/**
	 * @return array<string, mixed>
	 */
	public function getData(): array
	{
		return $this->data;
	}


	/**
	 *
	 */
	public function getDateTime(): DateTime
	{
		return $this->dateTime;
	}


	/**
	 * @return array<string, mixed>
	 */
	public function jsonSerialize(): array
	{
		return [
			"title" => $this->getTitle(),
			"data"  => $this->getData(),
			"time"  => $this->getDateTime()->format(DATE_ATOM),
		];
	}
}
