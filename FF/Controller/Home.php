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
	Default homepage controller
*/

class Home extends \Controller\MC
{
	public function __construct()
	{

		// Create array to hold page settings
		$newvars = array();

		// Assign key->val pairs that will be converted to
		// variable variables.
		$newvars["title"] = $this->showLang("Content","page_0");
		$newvars["desc"] = $this->showLang("Content","desc_0");
		$newvars["fonts"] = array("Abel", "Oxygen");
		$newvars["gcp"] = GRC; // Show Google reCAPTCHA if enabled.
		$newvars["csp"] = CSP;
		$newvars["css"] = array("default", "emailInput"); // load default.js & form.js
		$newvars["js"] = array("default"); // load default.css
		
		// Load form helper.
		$this->loadHelper('form');
		$newvars['form'] = new \Input\Form();
		
		// Load the view.
		$this->loadView("Home", $newvars);
	}
}


//Load the Controller
return new \vController\Home();

