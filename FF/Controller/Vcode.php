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
	This class is solely to verify the 
	code entered by a user that has already
	input their email and reseived that code.

	No View is loaded directly. Only redirects
	are used.
*/

class Vcode extends \Controller\MC
{
	private $auth; // Set hodler for authorization class.

	public function __construct()
	{
		// Load the Auth model.
		$this->loadModel("Auth");
		$this->auth = new \Security\Auth();

		// Make sure the form was submitted.
		if(isset($_POST["hash"]) && !empty($_POST["hash"]))
		{
			// If Google reCAPTHCA is enabled
            // send the response for verification.
			if(GRC === true)
            {
                $grc = $_POST['g-recaptcha-response'];
            }
            else
            {
                $grc = "";
            }

            // Process the code. The redirects are handled
            // by the auth model from here.
			$this->auth->processCode($this->getCode($_POST), $_POST["hash"], $_POST['website'], $grc, $_POST['frsc']);
		}
		else
		{
			// No POST made, so assume it was an attempt
			// to access this page directly. Send to 404.
			$this->auth->do404();
		}
	}

/**
* @param array: Full _POST array.
*
* @throws Nothing
*
* @return string: Combines the code values into one string.
*/

	private function getCode($post): string
	{
		$code = ""; // Make a string to hold the code.

		// Cycle through each POST element looking 
		// for the code key submission.
		foreach($post as $key => $value)
		{
			// Allow only POST keys that have
			// an underscore. Ignore others.
			if(strpos($key, "_"))
			{
				// Break the key by underscore
				// into an array
				$exp_key = explode("_", $key);

				// Make sure the prefix value matches 
				// the code input name.
				if($exp_key[0] === "secCode")
				{
					// It matches, so append the
					// value to the code string.
					$code .= $value;
				}
			}
		}
		// Return the code string.
		return $code;
	}
}

//Load the Controller
return new \vController\Vcode();