<?php

namespace SlashTrace\Context;

use SlashTrace\Http\Request;
use JsonSerializable;
use Exception;

/**
 *
 */
class EventContext implements JsonSerializable
{
	/**
	 * @var string|null
	 * */
	private $release;

	/**
	 * @var Request
	 */
	private $httpRequest;

	/**
	 * @var array<string, mixed>
	 */
	private $server = [];

	/**
	 * @var User|null
	 */
	private $user;

	/**
	 * @var Breadcrumbs|null
	 */
	private $breadcrumbs;

	/**
	 * @var string|null
	 */
	private $applicationPath;


	/**
	 *
	 */
	public function getRelease(): ?string
	{
		return $this->release;
	}


	/**
	 *
	 */
	public function setRelease(?string $release): self
	{
		$this->release = $release;

		return $this;
	}

	/**
	 * @return Request
	 */
	public function getHTTPRequest(): Request
	{
		return $this->httpRequest;
	}

	/**
	 *
	 */
	public function setHttpRequest(Request $request): self
	{
		$this->httpRequest = $request;

		return $this;
	}

	/**
	 * @return array<string, mixed>
	 */
	public function getServer(): array
	{
		return $this->server;
	}

	/**
	 * @param array<string, mixed> $server
	 */
	public function setServer(array $server): self
	{
		$this->server = $server;

		return $this;
	}

	/**
	 *
	 */
	public function getUser(): ?User
	{
		return $this->user;
	}

	/**
	 * @throws Exception
	 */
	public function setUser(?User $user): self
	{
		if (empty($user->getId()) && empty($user->getEmail())) {
			throw new Exception("User must have ID or email address");
		}

		$this->user = $user;

		return $this;
	}

	/**
	 *
	 */
	public function getBreadcrumbs(): ?Breadcrumbs
	{
		return $this->breadcrumbs;
	}


	/**
	 *
	 */
	public function setBreadcrumbs(?Breadcrumbs $breadcrumbs): self
	{
		$this->breadcrumbs = $breadcrumbs;

		return $this;
	}


	/**
	 *
	 */
	public function getApplicationPath(): ?string
	{
		return $this->applicationPath;
	}

	/**
	 *
	 */
	public function setApplicationPath(?string $applicationPath): self
	{
		// Force the use of the cross-platform directory separator
		$this->applicationPath = str_replace("\\", "/", $applicationPath);

		return $this;
	}


	/**
	 *
	 */
	public function hasCustomData(): bool
	{
		$breadcrumbs = $this->getBreadcrumbs();

		if (!empty($breadcrumbs) && !$breadcrumbs->isEmpty()) {
			return TRUE;
		}

		return !empty($this->getRelease()) || !empty($this->getUser());
	}


	/**
	 * @return array<string, mixed>
	 */
	public function jsonSerialize(): array
	{
		return array_filter([
			"request"          => $this->getHTTPRequest(),
			"server"           => $this->getServer(),
			"user"             => $this->getUser(),
			"breadcrumbs"      => $this->getBreadcrumbs(),
			"release"          => $this->getRelease(),
			"application_path" => $this->getApplicationPath(),
		]);
	}
}
