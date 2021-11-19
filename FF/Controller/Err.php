<?php

// Define the namespace
Namespace vController;

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
	Loads the headers and view for
	any errors that have been reserved
	in the session that need to be 
	displayed to the user.
*/

class Err extends \Controller\MC 
{
	public function __construct()
	{

		// Set all of the header information and
		// variable variables.
		$newvars = array();
		$newvars["title"] = $this->showLang("Error","title_0");
		$newvars["desc"] = $this->showLang("Error","message_3");
		$newvars["fonts"] = array("Abel", "Oxygen");
		$newvars["css"] = array("error");
		$newvars["js"] = array("error");
		
 		// Load the view.
		$this->loadView("Err", $newvars);
	}
}

//Load the Controller
return new \vController\Err();