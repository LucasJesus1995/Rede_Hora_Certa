<?php

return [

	'path' =>  'public/uploads/db/dumps/',

	'mysql' => [
		'dump_command_path' => env('DB_LOCAL_MYSQLDUMP'),
		'restore_command_path' => env('DB_LOCAL_RESTORE'),
	],

	's3' => [
		'path' => env('DB_S3_PATH')
	],

    'compress' => false,
];

