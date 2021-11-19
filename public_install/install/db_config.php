<?php 

/**
 * PHP - FolioFrame
 *
 * A PHP MVC mini-framework using modern PHP design security, 
 * with the intent of usage for online portfolios, personal webpages,
 * small business sites, etc.. 
 *
 * See README.md for more information 
 *     --> https://github.com/gavbro/php-folioframe/blob/main/README.md
 *
 *
 * @package    php-folioframe
 * @copyright  2014-2021 Gavin Brown
 * @license    MIT License through GITHUB
 * @git        https://github.com/gavbro/php-folioframe
 * @link       https://gavinbrown.ca/
 * @since      See the README for current overall version info. 
 *
 * @file       version 0.0.1
 *
 * @author Gavin Brown <gavin@gavinbrown.ca>
 *
 */

return "/*
Use Syntax: 
\$dbsettings = array(
'Default'=>array						// Name of settings array. Used to call the settings as needed.
	(
		'host' => 'localhost',			// Host Name. Localhost if on the same server, IP or Domain otherwise.
		'dbname' => 'database_name',	// Full Database name.
		'user' => 'database_user',		// Database User Name.
		'pass' => 'database_password',	// Database Password.
		'charset' => 'utf8mb4',			// Charset. Default is utf8mb4.
		'key' => '',					// Generated KEY for database encrypt / decrypt;
		'opt' => [
    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,   // PDO Options.
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    \PDO::ATTR_EMULATE_PREPARES   => false,
		]
),
'Next'=>array
	(
		'host' => 'localhost',
		'dbname' => 'database_name',
		'user' => 'database_user',
		'pass' => 'database_password',
		'charset' => 'utf8mb4',	
		'key' => '',
		'opt' => [
    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    \PDO::ATTR_EMULATE_PREPARES   => false,
		]
	)
);
*/


\$dbsettings = array(

	// Here is the main login user. This is automatically generated in the install.
	// Changing anything here will most likely break the login script.
	'Entry'=>array
	(
		'host' => '{dbase_host}',
		'dbname' => '{dbase_name}',
		'user' => '{dbase_user}',
		'pass' => '{dbase_pass}',
		'charset' => '{dbase_char}',
		'key' => '{dbase_key}',
		'opt' => [
    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    \PDO::ATTR_EMULATE_PREPARES   => false,
		]
	),

	// This is here to display a warning to the admin if the install user is still active.
    // Feel free to remove it if the install user is gone. or you just want the warning gone.
	'Install'=>array 
	( 
		'host' => '{dbase_host}',
		'dbname' => '{dbase_name}',
		'user' => '{install_user}',
		'pass' => '{install_pass}',
		'charset' => '{dbase_char}',	
		'key' => '',
		'opt' => [
    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    \PDO::ATTR_EMULATE_PREPARES   => false,
		]
	)
);";