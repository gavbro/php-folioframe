<?php

// Declare the namespace
Namespace Rout;

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
	This namespace/class is all about controlling how
	redirects and links to the outside web are used and 
	handled. 

	This is still very much a work in progress which will
	hopefully include a scrubber for links to the outside
	world, an internal link compressor (shorter references to internal redirects)
	and a url shortening service for the app (reference a specific page with a single word)
*/

// Bring in the language model
// to help with error output.
use \User\Lang as Lang;

class Internal
{
	
	private $urt; // Temporary Array
	private $lang; // Holds the lang class.
	
	public function __construct()
	{
		// Instance the language class.
		$this->lang = new Lang();

		// break up the URI to get the values.
		$this->urt = explode("/", $_SERVER["REQUEST_URI"]);
	
		// Send in the language string if it exists.
		// Load the new language if a matching language
		// file is found. Revert back to last used if not.
		if(isset($this->urt[1]))
		{
			// Attempt to set the new language.
			$this->lang->setLang($this->formatLangString($this->urt[1]));
		}
		else
		{
			// Go back to session Language.
			$this->lang->langReset();
		}

		// Check if a controller has been requested. 
		if(isset($this->urt[2]))
		{
			// Format the text correctly and call it.
			// Example: https://example.com/en/{controller} <-- this part is the controller
			$this->getController($this->formatContString($this->urt[2]));
		}
		else
		{
			// Load the default homepage.
			$this->getController(HM);
		}
	}
/**
* @param string: name of controller to load.
*
* @throws None.
*
* @return Void. Loaded controller or 404 page.
*/
	private function getController(string $cont): void
	{
		clearstatcache(); // Clear cached versions. This needs testing and could be redundant.
		$ControllerPath = C . $cont . E;  // Wrap in proper directory and extension
		
		// If controller file is found, load it. If not, show default 404 page.
		if(file_exists($ControllerPath))
		{
			include_once($ControllerPath);
		}
		else
		{
			//Controller not found send 404!
			$this->show404();
		}
	}
	
/**
* @param string: URL param to convert as String.
*
* @throws Nothing.
*
* @return string: Re-configured string of only numbers, letters and dashes.
*/

	private function formatContString(?string $param): string
	{
		// Reformat the text to include only numbers
		// letters and dashes.
		$newstring =  ucfirst(strtolower(preg_replace("/[^a-zA-Z0-9-]/", "", htmlspecialchars($param))));
		if(null !== $newstring)
		{
			return $newstring;
		}
		else
		{
			// This is a little cheeky, but
			// it works as long as you don't have 
			// a controller named 'Null.php'

			// It is a workaround of the PHP 7 way
			// of not allowing more than one return type.
			return "null";
		}
	}

/**
* @param string: URL language param to convert to lowercase
*
* @throws Nothing.
*
* @return string: Re-configured string of only numbers, letters and dashes.
*/

	private function formatLangString(?string $param): string
	{
		$newstring =  preg_replace("/[^a-z]/", "", strtolower($param));

		// Language string can't be empty and has
		// to be two characters.
		if(null !== $newstring && strlen($newstring) === 2)
		{
			return $newstring;
		}
		else
		{
			// This is a little cheeky, but
			// it works as long as you don't have 
			// a language file named 'null.php'

			// It is a workaround of the PHP 7 way
			// of not allowing more than one return type.
			// This is fixed in PHP 8.0
			return "null"; 
		}
	}
	
/**
* @param NULL
*
* @throws Nothing
*
* @return None: Inclusion of 404 page before loading anything else.
*/

	public function show404(): void
	{
		// redirect to the 404 controller
    	header("Location:" . TLD . $_SESSION["LN"] .  "/404/");

    	// prevent any further running of the script
		exit;
	}
	
/**
* @param NULL
*
* @throws Nothing.
*
* @return None.  Loads the default home controller on call.
*/

	private function home(): void
	{
		// load the default controller set 
		// in the /FF/Config/Settings.php file.
    	$this->getController(HM);
	}

}

//Load it
return new \Rout\Internal;
