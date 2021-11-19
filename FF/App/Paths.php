<?php

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
    Define globals for each commmonly
    used directory path to use full paths
    whenever possible throughout FolioFrame.
*/

// Set Web directory definitions
define("JSDIR", PUB . "js" . DS); // Javascript files path
define("CSSDIR", PUB . "css" . DS); // Default CSS directory path
define("IMGDIR", PUB . "img" . DS); // Default image file path


// Set main application directory definitions.
define("M", ST . "Model" . DS); // Model file path
define("V", ST . "View" . DS); // View file path
define("C", ST . "Controller" . DS); // Controller path
define("H", ST . "Helper" . DS); // Helper path
define("L", ST . "Libraries" . DS); // Libraries path
define("R", ST . "Resources" . DS); // Resources path
define("LOGDIR", ST . "Log" . DS); // Log path (not used yet)
define("LANGDIR", ST . "Lang" . DS); // Language file path

// Set the default language session if
// it isn't already set
if(!isset($_SESSION["LN"]))
{
    $_SESSION["LN"] = LA;
}

// Bring in the Locale!
require_once(APP . 'Locale.php');

// Load the main view setup.
require_once(APP . 'View.php');

// Bring in the Main Controller
require_once(APP . 'MainController.php');

// Load the Router to rout all requests.
require_once(APP . 'Router.php');




