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
	 * @var string
	 */
	private $title;

	/**
	 * @var array<string, mixed>
	 */
	private $data = [];

	/**
	 * @var DateTime
	 */
	private $dateTime;


	/**
	 * @param array<string, mixed> $data
	 */
	public function __construct(string $title, array $data, DateTime $date_time)
	{
		$this->title    = $title;
		$this->data     = $data;
		$this->dateTime = $date_time;
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
