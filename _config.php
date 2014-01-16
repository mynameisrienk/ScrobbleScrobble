<?php

/*
 * MySQL Server Settings
 */

if($_SERVER['SERVER_NAME'] === 'localhost') :
	define('DB_NAME', 'assorted');
	define('DB_USER', 'root');
	define('DB_PASSWORD', 'root');
	define('DB_HOST', 'localhost');
else :
	define('DB_NAME', '[DATABASE]');
	define('DB_USER', '[USER]');
	define('DB_PASSWORD', '[PASSWORD]');
	define('DB_HOST', '[HOST]');
endif;

/*
 *Your Last.fm User Name, API key and Secret Token
 * Get a free API account @http://www.last.fm/api/account/create
 */

$USER_NAME  = '[USER_NAME]';
$API_KEY    = '[API_KEY]';
$API_SECRET = '[API_SECRET]';

?>