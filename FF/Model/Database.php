<?php

// Define the namespace
Namespace Database;

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

// Prevent outside inclusion of files. Because.. you know, Paranoia
defined('ROOT') OR exit();

/*
	This class performs all access to the database config files
	And handles setting up the connections with the loaded
	details. All other functionality is already built in to
	\PDO, so no need to duplicate them here.
*/

class Db
{
	
	private $db; // Database name
	private $cipher; // OPENSSL Cipher type for encrypt and decrypt methods.
	private $skey; // Previously generated key using openssl_random_pseudo_bytes.
	
	public function __construct($dbprofile)
	{
		$this->loadConfig($dbprofile);
	}

/**
* @param string: the name of the database profile to load.
*
* @throws Custom \Exception: DB Connect Error.
*
* @return None. Sets up the required information to make a DB connection.
*/

	private function loadConfig($dbp): void
	{
		$settings_file = CONF . "Dbsettings" . E;
		if(file_exists($settings_file))
		{
			include_once($settings_file);
			if(isset($dbsettings[$dbp]))
			{
				$dsn = "mysql:host=" . $dbsettings[$dbp]["host"] . ";dbname=" . $dbsettings[$dbp]["dbname"] . ";charset=" . $dbsettings[$dbp]["charset"];
				$this->db = new \PDO($dsn, $dbsettings[$dbp]['user'], $dbsettings[$dbp]['pass'], $dbsettings[$dbp]['opt']);
				$this->db->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, 1);
				$this->skey = $dbsettings[$dbp]["key"];
			}
			else
			{
				// The requested database profile isn't found
				throw new \Exception("DB Connect Error: Unable to load credentials for the " . $dbp . " profile.");
				exit;
			}
		}
		else
		{
			// The dbsettings.php file isn't detected in the \FF\Config directory.
			throw new \Exception("DB Connect Error: Unable to load the Dbsettings.php file. Please replace the file or rerun the FolioFrame install.");
			exit;
		}
	}

/**
* @param None.
*
* @throws Nothing.
*
* @return object. Returns the fully formed db object.
*/

	public function Set(): object
	{
		return $this->db;
	}

/**
* @param None.
*
* @throws Nothing.
*
* @return string|bool. Returns the encrypt/decrypt key or false.
*/

	public function getKey(): string //: string|bool -- Can't use this until PHP 8.0
	{
		// Make sure the key is set
		if(null !== $this->skey && strlen($this->skey) > 0)
		{
			// Send it!
			return $this->skey;
		}
		else
		{
			return "";
		}
	}
}
