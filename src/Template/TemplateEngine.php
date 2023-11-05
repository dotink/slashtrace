<?php

namespace SlashTrace\Template;

/**
 *
 */
interface TemplateEngine
{
	/**
	 * @param array<string, mixed> $data
	 */
	public function render(string $template, array $data): string;
}