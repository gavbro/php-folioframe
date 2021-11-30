<?php

// Add nosniff
header('X-Content-Type-Options: nosniff');
//Add XSS Protection for older browsers
header("X-XSS-Protection: 1; mode=block");

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

return "// Start Session. 
// Also set cookies to require secure connections.
session_start([
    'cookie_httponly' => true,
    'cookie_secure' => true
]);

// Assign the global directory separation character
// Usually '/';

define('DS', DIRECTORY_SEPARATOR);
 
/*
 ONLY EDIT BELOW IF YOU CHANGE THE INSTALL DIRECTORY
 FROM /FF/ or define the main directory of the server.
*/
// Set the root directory of the server for file inclusions.
// Change  to define('ROOT', '/home/yourserver'); or define('ROOT', 'D:/Example/http_docs') for windows
// if you want, this setting will go to one above webroot (httpdocs, http_docs, public_html etc).
define('ROOT', realpath(dirname(__FILE__). DS . '..' . DS));

// This is the name of your install directory.
// DEFAULT is FF for FolioFrame.
\$install_directory = '{installdir}';

// Sets the main install directory as a global for use in inclusions.
define('ST', ROOT . DS . \$install_directory . DS); 

// Setup the GLOBAL App and Config directores to include
// core files.
define('APP', ST . 'App' . DS);
define('CONF', ST . 'Config' . DS);

// Bring in the configuration file.
require_once(CONF . 'Settings.php'); // Load the user defined settings.
require_once(APP . 'Config.php'); // Load the Application Settings

////// ERROR REPORTING TOGGLE. SET IN THE SETTINGS FILE
if(ERR === TRUE)
{
    ini_set ('display_errors', 1);  
    ini_set ('display_startup_errors', 1);  
    error_reporting (E_ALL);
}

// Bring in the Exception Handler!
require_once(APP . 'Error.php');

// bring in the rest of the required classes.
require_once(APP . 'Paths' . E); // Path to the paths file, which loads everything else needed to start.";