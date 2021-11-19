<?php

// Define the namespace.
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
    This controller is loaded as the default 404
    page. The 404 view fille(/FF/View/404.php)
    Is where the look can be changed.
*/



Class Err404 extends \Controller\MC
{

	public function __construct()
	{
		$newvars = array();

        // Set the header details for the 404 page
		$newvars["title"] = $this->showLang("Error", "title_0");
		$newvars["desc"] = $this->showLang("Error", "message_0");
		$newvars["fonts"] = array("Abel", "Oxygen");
		$newvars["css"] = array("404");
		$newvars["js"] = array("404");
		
		// Load the view.
		$this->loadView("404", $newvars);
	}
}

//Load the Controller
return new \vController\Err404();