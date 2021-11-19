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
	Prepare and display the code entry area
	once the email or reference hash code 
	has been verified.
*/

Class Code extends \Controller\MC
{

	private $auth; // To hold the auth class object
	private $hash; // Holds the hash value (GET or POST)
  	private $code; // Holds the code if passed by the URL (GET)

	public function __construct()
	{
		// Load the authorization Model class
		$this->loadModel("Auth");

		// Assign it to the classwide variable.
		$this->auth = new \Security\Auth();

		// Get the current web address minus the TLD
		// and remove all unwanted characters.
	  	$uri = preg_replace("/[^a-zA-Z0-9\&]/", "", trim($this->getUri()));

	  	// Verify that the second GET value is set.
	  	if(strpos($uri, "&"))
		{
			// Separate the first and second GET values
			// and set them to variables.
			$uri_vals = explode("&", $uri);
			$this->hash = $uri_vals[0];

			// The code from the email should be base64 encoded.
			$this->code =  base64_decode(substr($uri_vals[1], 4));
		}
		else
		{
			// There is no second GET value, so assume the
			// URI is the hash.
			$this->hash = $uri;
			$this->code = "";
		}

		// Either way, check the hash against the 
		// Database to see if it matches a valid 
		// code the is awaiting confirmation.
		if($this->auth->validateHash($this->hash))
		{

			// Since the hash is OK, set all of the 
			// header information.
			$newvars = array();
			$newvars["code"] = $this->code;
			$newvars["hash"] = $this->hash;
			$newvars["title"] = $this->showLang("Content","page_0");
			$newvars["desc"] = $this->showLang("Content","desc_0");
			$newvars["fonts"] = array("Abel", "Oxygen");
			$newvars["gcp"] = TRUE; // Show Google reCAPTCHA (Default: FALSE)
			$newvars["csp"] = CSP;
			$newvars["css"] = array("default","codeEntry");
			$newvars["js"] = array("default", "form");
			
			// Load the form helper.
			$this->loadHelper('form');
			$newvars['form'] = new \Input\Form();
			
			// Load the view.
			$this->loadView("Code", $newvars);
		}
		else
		{
			// The hash is not correct or does not
			// match a valid hash (could be timed out)
			// Clear the session and show the error.
			$this->auth->Logout();
			$this->auth->addError("Error","code_0");
			$this->auth->doError();
		}
	}
}

//Load the Controller
return new \vController\Code();