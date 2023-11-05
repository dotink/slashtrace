<?php

namespace SlashTrace\EventHandler;

use SlashTrace\Context\Breadcrumbs;
use SlashTrace\Context\EventContext;
use SlashTrace\Context\User;
use SlashTrace\DebugRenderer\DebugJsonRenderer;
use SlashTrace\DebugRenderer\DebugRenderer;
use SlashTrace\DebugRenderer\DebugCliRenderer;
use SlashTrace\DebugRenderer\DebugWebRenderer;
use SlashTrace\DebugRenderer\DebugTextRenderer;
use SlashTrace\Event;
use SlashTrace\Exception\ExceptionInspector;
use SlashTrace\Level;
use SlashTrace\System\HasSystemProvider;

use ErrorException;
use TypeError;
use Exception;

/**
 *
 */
class DebugHandler implements EventHandler
{
	use HasSystemProvider;

	/**
	 * @var DebugRenderer
	 */
	private $renderer;

	/**
	 * @var ExceptionInspector
	 */
	private $exceptionInspector;

	/**
	 * @var EventContext
	 */
	private $eventContext;

	/**
	 * {@inheritDoc}
	 */
	public function handleException($exception): int
	{
		$event = $this->createEvent($exception);
		$event->setContext($this->getEventContext());

		return $this->handleEvent($event);
	}

	/**
	 *
	 */
	public function handleEvent(Event $event): int
	{
		$this->getRenderer()->render($event);

		return EventHandler::SIGNAL_EXIT;
	}

	/**
	 *
	 */
	public function getRenderer(): DebugRenderer
	{
		if (is_null($this->renderer)) {
			$this->renderer = $this->createRenderer();
		}
		return $this->renderer;
	}

	/**
	 *
	 */
	public function setRenderer(DebugRenderer $renderer): self
	{
		$this->renderer = $renderer;

		return $this;
	}

	/**
	 *
	 */
	private function createRenderer(): DebugRenderer
	{
		$system = $this->getSystem();
		if ($system->isCli()) {
			return new DebugCliRenderer();
		}

		$request = $system->getHttpRequest();
		if ($request->getHeader("Accept") === "application/json") {
			return new DebugJsonRenderer();
		}

		if ($request->isXhr()) {
			return new DebugTextRenderer();
		}

		return new DebugWebRenderer();
	}

	/**
	 * @param TypeError|Exception $exception
	 */
	private function createEvent($exception): Event
	{
		$event = new Event();
		$event->setLevel($this->getLevel($exception));

		$exceptionInspector = $this->getExceptionInspector();
		do {
			$event->addException($exceptionInspector->inspect($exception));
		} while ($exception = $exception->getPrevious());

		return $event;
	}


	/**
	 *
	 */
	private function getExceptionInspector(): ExceptionInspector
	{
		if (is_null($this->exceptionInspector)) {
			$this->exceptionInspector = new ExceptionInspector();
		}
		return $this->exceptionInspector;
	}


	/**
	 * @param TypeError|Exception $exception
	 */
	private function getLevel($exception): string
	{
		$level = Level::ERROR;
		if ($exception instanceof ErrorException) {
			$level = Level::severityToLevel($exception->getSeverity());
		}
		return $level;
	}


	/**
	 *
	 */
	private function getEventContext(): EventContext
	{
		if (is_null($this->eventContext)) {
			$this->eventContext = $this->createEventContext();
		}
		return $this->eventContext;
	}


	/**
	 *
	 */
	private function createEventContext(): EventContext
	{
		$system = $this->getSystem();

		$context = new EventContext();
		$context->setServer($system->getServerData());
		$context->setBreadcrumbs(new Breadcrumbs($system));

		if ($system->isWeb()) {
			$context->setHttpRequest($system->getHttpRequest());
		}

		return $context;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setUser(User $user): self
	{
		$this->getEventContext()->setUser($user);

		return $this;
	}


	/**
	 * {@inheritDoc}
	 */
	public function recordBreadcrumb(string $title, array $data = []): self
	{
		$this->getEventContext()->getBreadcrumbs()->record($title, $data);

		return $this;
	}


	/**
	 * {@inheritDoc}
	 */
	public function setApplicationPath(string $path): self
	{
		$this->getEventContext()->setApplicationPath($path);

		return $this;
	}


	/**
	 * {@inheritDoc}
	 */
	public function setRelease($release): self
	{
		$this->getEventContext()->setRelease($release);

		return $this;
	}
}
