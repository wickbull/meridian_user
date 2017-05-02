<?php

return [

	// domain to access files

	'domain' => env('APP_STATIC_URL', 'http://mfaua'),

	// inperpolate path for just uploaded file

	'path_pattern' => '/content/files/{contentHash|subpath}/{contentHash}.{ext}',

];
