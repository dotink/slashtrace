<?php

namespace SlashTrace\Context;

use SlashTrace\Context\Breadcrumbs\Breadcrumb;
use SlashTrace\System\SystemProvider;
use JsonSerializable;
use DateTime;

/**
 *
 */
class Breadcrumbs implements JsonSerializable
{
	const MAX_SIZE = 25;

	/**
	 * @var SystemProvider
	 */
	private $system;

	/**
	 *  @var Breadcrumb[]
	 */
	private $crumbs = [];


	/**
	 *
	 */
	public function __construct(SystemProvider $system)
	{
		$this->system = $system;
	}

	/**
	 * @param array<string, mixed> $data
	 */
	public function record(string $title, array $data = []): void
	{
		$breadcrumb = new Breadcrumb($title, $data, $this->getDateTime());

		if ($this->getSize() == self::MAX_SIZE) {
			array_shift($this->crumbs);
		}

		$this->crumbs[] = $breadcrumb;
	}

	/**
	 * @return Breadcrumb[]
	 */
	public function getCrumbs()
	{
		return $this->crumbs;
	}

	/**
	 * @return DateTime
	 */
	protected function getDateTime()
	{
		return $this->system->getDateTime();
	}

	/**
	 * @return int
	 */
	public function getMaxSize()
	{
		return self::MAX_SIZE;
	}

	/**
	 * @return int
	 */
	private function getSize()
	{
		return count($this->crumbs);
	}

	public function isEmpty(): bool
	{
		return $this->getSize() == 0;
	}

	public function clear(): self
	{
		$this->crumbs = [];

		return $this;
	}


	/**
	 * @return array<string, mixed>
	 */
	public function jsonSerialize(): array
	{
		return $this->crumbs;
	}
}
