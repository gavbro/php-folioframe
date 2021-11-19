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
    Controller made to accept the submission of
    an email address to login.

    Does not load a view, only redirects as needed.
*/

Class Login extends \Controller\MC
{
	private $auth; // Set holder for authorization class.

	public function __construct()
	{
        // Make sure something was submitted.
		if($_SERVER["REQUEST_METHOD"] === "POST")
		{
            // Load the Auth model.
			$this->loadModel("Auth");
			$this->auth = new \Security\Auth();

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
			$this->auth->processEmail($_POST["mail"], $_POST['website'], $grc, $_POST['frsc']);
		}
		else
		{
            // No post was made, so it must
            // be a direct access request.
            // Send to 404.
			//header("Location: " . TLD . $_SESSION["LN"] . "/404/");
			//exit;
            echo "Skipped it again!";
		}
	}
}

//Load the Controller
return new \vController\Login();