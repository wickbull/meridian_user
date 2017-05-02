<?php

return [

	// public path

	'public_path' => env('GENERIC_FILE_PATH', public_path()),

	// model class

	'model' => 'App\\GenericFile',

	// prevent physically delete files

	'prevent_deletions' => false,

	// path pattern to store file

	'path_pattern' => '/content/files/{contentHash|subpath}/{contentHash}.{ext}',

];
