<?php

namespace SlashTrace\Template;

use SlashTrace\Formatter\StackFrameCallFormatter;
use SlashTrace\Formatter\StackFrameCallHtmlFormatter;
use SlashTrace\Formatter\VarDumper;
use SlashTrace\StackTrace\StackFrame;

/**
 *
 */
class TemplateHelper
{
	/**
	 * @var VarDumper|null
	 */
	private $varDumper;

	/**
	 * @var StackFrameCallFormatter|null
	 */
	private $stackFrameCallFormatter;


	/**
	 *
	 */
	public function formatStackFrameCall(StackFrame $frame): string
	{
		return $this->getStackFrameCallFormatter()->format($frame);
	}


	/**
	 *
	 */
	public function formatStackFrameContext(StackFrame $frame): string
	{
		$lines = $frame->getContext();

		if (!count($lines)) {
			return '';
		}

		$attributes = [];
		$firstLine  = array_keys($lines)[0];

		if ($firstLine > 1) {
			$attributes["data-start"] = $firstLine;
		}

		$line = $frame->getLine();
		if (!is_null($line)) {
			$attributes["data-line"] = $line;
		}

		$attributes = $this->formatHMLAttributes($attributes);

		$return = $attributes ? "<pre $attributes>" : "<pre>";
		$return .= implode("\n", $this->escapeCodeLines($lines));
		$return .= "</pre>";

		return $return;
	}

	/**
	 * @param array<string, mixed> $attributes
	 */
	private function formatHMLAttributes(array $attributes): string
	{
		$return = [];
		foreach ($attributes as $key => $value) {
			$return[] = "$key=\"$value\"";
		}
		return implode(" ", $return);
	}

	/**
	 * @param array<string> $lines
	 * @return array<string>
	 */
	private function escapeCodeLines(array $lines): array
	{
		$return = [];
		foreach ($lines as $line) {
			if (!strlen($line)) {
				$line = " ";
			}
			$return[] = htmlentities($line, ENT_QUOTES, "UTF-8");
		}
		return $return;
	}


	/**
	 * @param mixed $argument
	 */
	public function dump($argument): string
	{
		return $this->getVarDumper()->dump($argument);
	}


	/**
	 *
	 */
	public function getVarDumper(): VarDumper
	{
		if (is_null($this->varDumper)) {
			$this->varDumper = new VarDumper();
		}

		return $this->varDumper;
	}


	/**
	 *
	 */
	public function setVarDumper(VarDumper $dumper): self
	{
		$this->varDumper = $dumper;

		return $this;
	}


	/**
	 *
	 */
	private function getStackFrameCallFormatter(): StackFrameCallFormatter
	{
		if (is_null($this->stackFrameCallFormatter)) {
			$this->stackFrameCallFormatter = new StackFrameCallHtmlFormatter();
		}

		return $this->stackFrameCallFormatter;
	}
}