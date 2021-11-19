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
	The class accepts the users email,
	makes sure it is valid, creates the
	new code in the DB, send the email to
	the user and then loads the Code 
	view for the user to enter the code
	to be verified.
*/

class Vemail extends \Controller\MC
{
	private $auth; // Set holder for authorization class.

	public function __construct()
	{
		// Make sure something was submitted.
		if(!empty($_POST["mail"]))
		{
			// Load the Auth model.
			$this->loadModel("Auth");
			$this->auth = new \Security\Auth();

			if(GRC === true)
            {
                $grc = $_POST['g-recaptcha-response'];
            }
            else
            {
                $grc = "";
            }

            // Process the email. The redirects are handled
            // by the auth model from here if errors occur.
			$this->auth->processEmail($_POST["mail"], $_POST['website'], $_POST['g-recaptcha-response'], $_POST['frsc']);

			// If the above Auth method is ok then 
			// we proceed to the Code entry area.

			// Setup the View header information.
			$newvars = array();
			$newvars["title"] = $this->showLang("Content","page_0");
			$newvars["desc"] = $this->showLang("Content","desc_0");
			$newvars["fonts"] = array("Abel", "Oxygen");
			$newvars["gcp"] = GRC; // Show Google reCAPTCHA if it is setup.
			$newvars["csp"] = CSP;
			$newvars["css"] = array("default","codeEntry");
			$newvars["js"] = array("default", "form");
			
			// Load helpers.
			$this->loadHelper('form');
			$newvars['form'] = new \Input\Form();
			
			
			// Load the view.
			$this->loadView("Code", $newvars);
		}
		else
		{
            // No POST was made, so it must
            // be a direct access request.
            // Send to 404.
			header("Location: " . TLD . $_SESSION["LN"] . "/404/");
			exit;
		}
	}
}

//Load the Controller
return new \vController\Vemail();