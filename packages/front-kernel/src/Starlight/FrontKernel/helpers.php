<?php

if ( ! function_exists('static_asset'))
{
	/**
	 * Get the path to static file
	 *
	 * @param  string  $path
	 * @return string
	 */
	function static_asset($path)
	{
		return rtrim(config('app.static_url'), '/') . '/' . ltrim($path, '/');
	}
}

if ( ! function_exists('rev'))
{
	/**
	 * Get the path to static file with rev
	 *
	 * @param  string  $path
	 * @return string
	 */
	function rev($path)
	{
		static $manifest = null;

		$full_path = app_path('../static/build/rev-manifest.json');

		if (is_null($manifest))
		{
			$manifest = json_decode(file_get_contents($full_path), true);
		}

		$path = $manifest[$path];

		return static_asset('build/' . $path);
	}
}

if ( ! function_exists('format_size'))
{
	/**
	 * @param  integer  $bytes
	 * @param  integer $precision
	 * @return string
	 */
	function format_size($bytes, $precision = 2)
	{

		$units = array('B', 'KB', 'MB', 'GB', 'TB');

		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);

		// Uncomment one of the following alternatives
		$bytes /= pow(1024, $pow);
		// $bytes /= (1 << (10 * $pow));

		return round($bytes, $precision) . ' ' . $units[$pow];
	}
}

