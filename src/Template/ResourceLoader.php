<?php

namespace SlashTrace\Template;

/**
 *
 */
class ResourceLoader
{
	/**
	 *
	 */
	public function stylesheet(string $file): string
	{
		$path = $this->getAssetsDirectory() . "/stylesheets/$file";

		$return = '<style type="text/css">';
		$return .= $this->loadResource($path);
		$return .= '</style>';

		return $return;
	}


	/**
	 *
	 */
	public function script(string $file): string
	{
		$path = $this->getAssetsDirectory() . "/scripts/$file";

		$return = '<script type="text/javascript">';
		$return .= $this->loadResource($path);
		$return .= '</script>';

		return $return;
	}


	/**
	 *
	 */
	public function getViewsDirectory(): string
	{
		return $this->getRootDirectory() . "/views";
	}


	/**
	 *
	 */
	private function getAssetsDirectory(): string
	{
		return $this->getRootDirectory() . "/assets";
	}


	/**
	 *
	 */
	private function getRootDirectory(): string
	{
		return dirname(__DIR__) . "/Resources";
	}


	/**
	 *
	 */
	private function loadResource(string $file): string
	{
		return file_get_contents($file) ?: '';
	}
}