<?php

namespace SlashTrace\Template;

use League\Plates\Engine;

/**
 *
 */
class PlatesTemplateEngine implements TemplateEngine
{
	/**
	 * @var Engine|null
	 */
	private $engine;


	/**
	 *
	 */
	public function __construct(private readonly ResourceLoader $resourceLoader)
    {
    }


	/**
	 * Renders the main template, making the data available globally to all sub-templates
	 *
	 * @see http://platesphp.com/templates/data/
	 * @param array<string, mixed> $data
	 */
	public function render(string $template, array $data): string
	{
		$engine = $this->getEngine();
		$engine->addData($data);
		return $engine->render($template);
	}


	/**
	 *
	 */
	private function getEngine(): Engine
	{
		if (is_null($this->engine)) {
			$this->engine = new Engine($this->resourceLoader->getViewsDirectory());
		}

		return $this->engine;
	}
}