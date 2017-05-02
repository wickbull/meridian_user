<?php

require __DIR__.'/../vendor/autoload.php';

Dotenv::load(__DIR__ . '/../');

/*
|--------------------------------------------------------------------------
| Create static files miner
|--------------------------------------------------------------------------
|
| Handles requests to nonexistent static files,
| analyzes and modifies the URL file before he was sent to http response,
| and stores the processed file and next time have
| to give the finished file is not causing this script
|
*/


$miner = new Ideil\LaravelGenericFile\GenericFileMiner(
	Symfony\Component\HttpFoundation\Request::createFromGlobals(), '~/[a-z\d]{2}/[a-z\d]{2}/.+$~');

/*
|--------------------------------------------------------------------------
| Configure
|--------------------------------------------------------------------------
|
| Setup basic settings
|
*/

$miner->setDevModeActivity(env('APP_DEBUG'));

$miner->setUriRoot('/content/thumbs/{checksum}/');

$miner->setHandledFilesRoot(__DIR__ . '/../static');

$miner->setOriginalFilesRoot(__DIR__ . '/../static/content/files');

/*
|--------------------------------------------------------------------------
| True url detection
|--------------------------------------------------------------------------
|
| Check is url really for static content
| or redirect required
|
*/

$miner->uriNotMatch('~^/(content|pub|src|layouts)~', function ($uri) use ($miner)
{
	$miner->getRedirectTo(env('APP_URL') . $uri)
		->send();
});

/*
|--------------------------------------------------------------------------
| Register thumb handlers
|--------------------------------------------------------------------------
|
| Register handlers for processing files
|
*/

// Crop processing
// Example: URL must contain a fragment /0x0-100x100/

$miner->addThumbHandler('crop', '~/(\d+)x(\d+)-(\d+)x(\d+)/~', function ($image, $matches)
{
	return $image->crop($matches[3], $matches[4], $matches[1], $matches[2]);
});

// Resize processing
// Example: URL must contain a fragment /100x100/

$miner->addThumbHandler('resize', '~/(u)?(\d*)([x\-])(\d*)/~', function ($image, $matches)
{
	$is_upsizable = !empty($matches[1]);
	$width        = $matches[2];
	$height       = $matches[4];
	$sign         = $matches[3];

	if ($sign === '-')
	{
		return $image->resize($width ?: null, $height ?: null, function ($constraint) use ($is_upsizable)
		{
			$constraint->aspectRatio();
			$is_upsizable || $constraint->upsize();
		});
	}

	if ($sign === 'x')
	{
		if (empty($width))
		{
			return $image->heighten($height, function ($constraint) use ($is_upsizable)
			{
				$is_upsizable || $constraint->upsize();
			});
		}

		if (empty($height))
		{
			return $image->widen($width, function ($constraint) use ($is_upsizable)
			{
				$is_upsizable || $constraint->upsize();
			});
		}

		return $image->fit($width, $height, function ($constraint) use ($is_upsizable)
		{
			$is_upsizable || $constraint->upsize();
		});
	}
});

// Watermark processing
// Example: URL must contain a fragment /watermark/

$miner->addThumbHandler('watermark', '~/watermark/~', function ($image, $matches)
{
	return $image->insert(__DIR__ . '/../static/src/img/watermark-1.png', 'bottom-right', 10, 10);
});

/*
|--------------------------------------------------------------------------
| Run
|--------------------------------------------------------------------------
|
| Execute all handlers and send response
|
*/

$miner->handle()->send();
