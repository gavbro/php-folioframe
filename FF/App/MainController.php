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

	This class is functions as a collection of methods
	that partially link other classes

*/

use \Display\View as View;
use \User\Lang as Lang;

Class MC 
{

/**
* @param mixed: Name of the view to load, any options as an array
*
* @throws Custom exception if file not found. 
*
* @return string: Output of view file string or print directly to call.
*/

	public function loadView($viewFileName, $viewOptions = array(), $print = TRUE)//: string|null -- Can't use until PHP 8.0
	{
		// Add the path and .php extension
		$viewFile = V . ucfirst($viewFileName) . E;

		// Set the URL as the variable $uri in the view.
		$viewOptions["uri"] = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

		// Set the view object to the $view variable in the loaded view.
		$viewOptions["view"] = new View();

		// Set he logged in flag if the user is logged in.
		// This adds the $loggedIn variable to every View.
		// for faster checks of if the user is logged in or
		// not.
		if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true)
		{
			$viewOptions["loggedIn"] = true;
		}
		else
		{
			$viewOptions["loggedIn"] = false;
		}

		$output = NULL;
		// Make sure the view exists.
    if(file_exists($viewFile))
    {
    	// Set each array key as a variable variable.
    	// Example: $thisarray["var"] becomes $var.
    	foreach($viewOptions as $key => $val)
    	{
    		$$key = $val;
    	}

      // Start output buffering
      ob_start();

      // Include the file
      include_once($viewFile);

      // End buffering and return its contents
      $output = ob_get_clean();

      // Default behavior is to print the
      // output, but it can also be returned
      // as a string if print is set to false
      // in the method call.
      if ($print)
	    {
	        print $output;
	    }
	    else
	    {
	    	return $output;
	    }
  	}
  	else
  	{
  		// The view file does not exist. 
  		throw new \Exception('Main Controller Error: View file not found.');
  	}
	}

/**
* @param None.
*
* @throws None.
*
* @return array: collection of server user info.
*/

	// Method to gather up all remote user info that can be displayed.
	public function RemoteInfo(): array
	{
		return array(
			"User IP" => $_SERVER["REMOTE_ADDR"], // User IP Address
			"Remote Port" => $_SERVER["REMOTE_PORT"], // User Request PORT
			"User Browser" => $_SERVER["HTTP_USER_AGENT"], // User Browser Info
			"Query String" => $_SERVER["QUERY_STRING"], // The GET variables if existant
			"Request Scheme" => $_SERVER["REQUEST_METHOD"] // GET/POST etc.
		);
	}

/**
* @param None.
*
* @throws None.
*
* @return string: returns the address minus the TLD and language.
*/

	public function getUri(): string
	{
		// Break the site url info into an array 
		// after removing the prefix.
		$uri = explode("/", substr("http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], (strlen(TLD)+3)));

		// Get the part after the controller
		if(isset($uri[1]) && strlen($uri[1]) > 0)
		{
			return $uri[1];
		}
		else
		{
			return "";
		}
		
	}
	
/**
* @param string. The model name to load
*
* @throws Custom Exception: File not found.
*
* @return Nothing: Loads the model file directly.
*/

	public function loadModel($model): void
	{
		// Make sure the parameter has the first
		// letter as uppercase.
		$model = ucfirst($model);

		// Does the model file exist?
		if(file_exists(M . $model . E))
		{
			// If it is, load it!
			include_once(M . $model . E);
		}
		else
		{
			// The model file does not exist. 
			// Log ang throw exception.
  		throw new \Exception('Main Controller Error: View file not found.');
		}
	}
		
/**
* @param string. The helper name to load
*
* @throws Custom Exception: File not found.
*
* @return Nothing: Loads the helper file directly.
*/

	public function loadHelper($helper): void
	{
		// Make sure the parameter has the first
		// letter as uppercase.
		$helper = ucfirst($helper);

		// Does the helper file exists?
		if(file_exists(H . $helper . E))
		{
			// Load it!
			include_once(H . $helper . E);
		}
		else
		{
			// The helper file does not exist. 
			throw new \Exception ('Unable to load required file: Helper. Please contact the webmaster to report.');
		}
	}

/**
* @param string. The helper name to load
*
* @throws Custom Exception: File not found.
*
* @return Nothing: Loads the library file directly.
*/

	public function loadLibrary($lib): void
	{
		// Uppercase not required since a 
		// library could be imported code.

		// Does the library file exist.
		if(file_exists(L . $lib . E))
		{
			// Load it!
			include_once(L . $lib . E);
		}
		else
		{
			// The Library file does not exist.
			throw new \Exception ('Unable to load required file: Library. Please contact the webmaster to report.');
		}
	}
	
/**
* @param string. The language category and key.
*
* @throws None. (yet)
*
* @return string. Language
*/

	public function showLang($section, $errno): string
	{
		// Set an instance of the Lang class
		$lang = new Lang();

		// Load the requested language text.
		return $lang->getLang($section, $errno);
	}
	
/**
* @param None. 
*
* @throws None.
*
* @return Boolean: True if the user is still logged in.
*/

	public function checkLogin(): bool
	{
		// Load the allow model
		$this->loadModel("Allow");

		// Set an instance of the allow class 
		$allow = new \Security\Allow;

		// Return the result of the check
		return $allow->allowedCheck();
	}

/**
* @param None. 
*
* @throws None.
*
* @return None.
*/

	public function Logout(): void
	{
		// Load the allow model
		$this->loadModel("Allow");

		// Set an instance of the allow class 
		$allow = new \Security\Allow;

		// Unset the session and redirect.
		$allow->disAllow();
	}
}
