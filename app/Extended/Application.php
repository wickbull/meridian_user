<?php namespace App\Extended;

class Application extends \Illuminate\Foundation\Application
{
	/**
	 * Application version
	 */
	const APP_VERSION = '1.0.0';

	/**
	 * @return string
	 */
	public function getAppVersion()
	{
		return self::APP_VERSION;
	}

	/**
	 * Get the path to the public / web directory.
	 *
	 * @return string
	 */
	public function publicPath()
	{
		return $this->basePath.DIRECTORY_SEPARATOR.'static';
	}

	/**
	 * Get the path to the storage directory.
	 *
	 * @return string
	 */
	public function storagePath()
	{
		return $this->storagePath ?: $this->basePath.DIRECTORY_SEPARATOR.'var';
	}

}
