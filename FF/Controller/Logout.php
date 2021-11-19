<?php

// Define the namespace
Namespace Controller;

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
	This controller acts as a direct link
	access point to logout the user.

	Going to https://website.tld/en/Logout will logout
	the current user.
*/

class Logout extends \Controller\MC
{
	public function __construct()
	{
		// Call the Logout 
		$this->Logout();

		// Send them back to the homepage.
		header("Location: " . TLD . $_SESSION["LN"] . "/");
		exit;
	}
}

//Load the Controller
return new \Controller\Logout();