<?php

//Define the namespace
Namespace User;

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

	Class meant to handle all language setting 
	and getting methods.

*/

Class Lang
{
	// Holder variable for language array
	// once loaded.
	private $langInfo;


	public function __construct()
	{
		if(!isset($this->langInfo))
		{
			$this->langInfo = include(LANGDIR . $_SESSION["LN"] . E);
		}
	}

/**
 * @param string: language  and filename. 
 *
 * @throws Nothing.
 *
 * @return Void: Redirects as necessary.
*/

	public function setLang($lang): void
	{
		// If the current language matches
		// the session, then nothing has 
		// changed.
		if($lang === $_SESSION["LN"])
		{
			$this->langInfo = include(LANGDIR . $_SESSION["LN"] . E);
		}
		else
		{
			// Language code is different

			// Setup the path to where
			// the language file should
			// be found.
			$file = LANGDIR . $lang . E;

			// Make sure the language file
			// exists.
			if(file_exists($file))
			{
				// Set the new session language.
				$_SESSION["LN"] = $lang;

				// Assign the file output to the
				// language array property.
				$this->langInfo = include $file;
			}
			else
			{
				// Reset to default language
				$this->langReset();
			}
		}
	}

/**
 * @param string: Error type and ID. 
 *
 * @throws Nothing.
 *
 * @return string: sentance string.
*/

	public function getLang($type, $id): string
	{
		if(array_key_exists($type, $this->langInfo))
		{
			if(array_key_exists($id, $this->langInfo[$type]))
			{
				return $this->langInfo[$type][$id];
			}
			else
			{
				return "ERR: ID incorrect";
			}
		}
		else
		{
			return "ERR: Lang type incorrect";
		}
	}

/**
 * @param None. 
 *
 * @throws Nothing.
 *
 * @return Void: Reset the lang and redirect.
*/

	public function langReset(): void
	{
		$this->langInfo = include(LANGDIR . $_SESSION["LN"] . E);
		header("Location: " . TLD . $_SESSION["LN"] ."/");
	}

}

// Run the code onload

return new \User\Lang();