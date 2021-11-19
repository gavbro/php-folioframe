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



Class About extends \Controller\MC
{

	public function __construct()
	{
		$newvars = array();

        // These are the default and required variables for
        // any controller that loads a view with the View->Setup method called.
		$newvars["title"] = $this->showLang("Page", "about_title");
		$newvars["desc"] = $this->showLang("Page", "about_desc");
        $newvars["gcp"] = GRC; // Load GRC if it is enabled. Set this to false if you do not want to load it either way.
        $newvars["csp"] = CSP; // Enables the Content Security Policy, False to disable. (higly recommeneded for public pages)
		$newvars["fonts"] = array("Abel", "Oxygen");
		$newvars["css"] = array("default", "emailInput"); // load default.js & form.js
		$newvars["js"] = array("default"); // load default.css

        // Load form helper.
        $this->loadHelper('form');
        $newvars['form'] = new \Input\Form();

		// Load the view.
		$this->loadView("About", $newvars);
	}
}

//Load the Controller
return new \vController\About();