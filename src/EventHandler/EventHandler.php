<?php

namespace SlashTrace\EventHandler;

use TypeError;
use Exception;
use SlashTrace\Context\User;

/**
 *
 */
interface EventHandler
{
	const SIGNAL_CONTINUE = 0;
	const SIGNAL_EXIT = 1;


	/**
	 * @param TypeError|Exception $exception
	 * @throws EventHandlerException
	 */
	public function handleException($exception): int;


	/**
	 * @param array<string, mixed> $data
	 */
	public function recordBreadcrumb(string $title, array $data = []): self;


	/**
	 *
	 */
	public function setUser(User $user): self;


	/**
	 *
	 */
	public function setRelease(string $release): self;


	/**
	 *
	 */
	public function setApplicationPath(string $path): self;
}
