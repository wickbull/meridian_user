<?php namespace Starlight\FrontKernel;

class Application extends \Illuminate\Foundation\Application
{
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

	/**
	 * Create a new Illuminate application instance.
	 *
	 * @param  string|null  $basePath
	 * @return void
	 */
	public function __construct($basePath = null)
	{
		parent::__construct($basePath);

		$this->url = $this->share(function()
		{
			return new Routing\UrlGenerator($this->router->getRoutes(), $this->request);
		});
	}

}
