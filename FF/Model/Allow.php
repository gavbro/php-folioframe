<?php

//Define the namespace
Namespace Security;

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
	Class to handle whether a user is allowed
	into the secure area or not. Also, to deal
	with them.
*/

Class Allow 
{

/**
* @param None.
*
* @throws None.
*
* @return Boolean: Current loggedin status.
*/

	public function allowedCheck(): bool
	{
		// Returns the current loggedin status.
		return $this->allowStatus();
	}

/**
* @param None.
*
* @throws None.
*
* @return Boolean: Current loggedin status.
*/

	private function allowStatus(): bool
	{
		// Make sure the session variable is set and is good.
		if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

/**
* @param None.
*
* @throws None.
*
* @return None: unsets the session variable and then destroys it.
*/

	public function disAllow(): void
	{
		// Check the loggedin status.
		// if it is true, make it false.
		if($this->allowStatus())
		{
			$_SESSION["logged_in"] = false;
		}

		// Destroy the currents session.
		session_destroy();
	}	
}