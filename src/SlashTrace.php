<?php

namespace SlashTrace;

use SlashTrace\Context\User;
use SlashTrace\EventHandler\EventHandler;
use SlashTrace\EventHandler\EventHandlerException;
use SlashTrace\System\HasSystemProvider;

use Exception;
use RuntimeException;

class SlashTrace
{
    use HasSystemProvider;

    const VERSION = "1.0.0";

    /** @var EventHandler[] */
    private $handlers = [];

    /**
     * @param Exception $exception
     * @return int
     */
    public function handleException($exception)
    {
        foreach ($this->getHandlers() as $handler) {
            try {
                if ($handler->handleException($exception) === EventHandler::SIGNAL_EXIT) {
                    return EventHandler::SIGNAL_EXIT;
                }

            } catch (EventHandlerException $exception) {
                $this->logHandlerException($exception);

                return EventHandler::SIGNAL_EXIT;
            }
        }

        return EventHandler::SIGNAL_CONTINUE;
    }

    private function logHandlerException(EventHandlerException $exception)
    {
        $message = $exception->getMessage();
        $code = $exception->getCode();
        if ($code) {
            $error = sprintf("SlashTrace error (%s): %s", $code, $message);
        } else {
            $error = sprintf("SlashTrace error: %s", $message);
        }

        $this->getSystem()->logError($error);
    }

    /**
     * @return EventHandler[]
     */
    public function getHandlers()
    {
        return $this->handlers;
    }

    public function addHandler(EventHandler $handler)
    {
        $this->checkUniqueHandler($handler);
        $this->handlers[] = $handler;
    }

    public function prependHandler(EventHandler $handler)
    {
        $this->checkUniqueHandler($handler);
        array_unshift($this->handlers, $handler);
    }

    /**
     * Checks that a particular handler hasn't already been registered
     *
     * @param EventHandler $input
     * @throws RuntimeException
     */
    private function checkUniqueHandler(EventHandler $input)
    {
        foreach ($this->handlers as $handler) {
            if ($handler === $input) {
                throw new RuntimeException();
            }
        }
    }

    public function register()
    {
        $errorHandler = new ErrorHandler($this, $this->getSystem());
        $errorHandler->install();
    }

    public function setUser(User $user): self
    {
        foreach ($this->getHandlers() as $handler) {
            $handler->setUser($user);
        }

        return $this;
    }

    public function recordBreadcrumb(string $title, array $data = []): self
    {
        foreach ($this->getHandlers() as $handler) {
            $handler->recordBreadcrumb($title, $data);
        }

        return $this;
    }

    public function setRelease(string $release): self
    {
        foreach ($this->getHandlers() as $handler) {
            $handler->setRelease($release);
        }

        return $this;
    }

    public function setApplicationPath(string $path): self
    {
        foreach ($this->getHandlers() as $handler) {
            $handler->setApplicationPath($path);
        }

        return $this;
    }
}
