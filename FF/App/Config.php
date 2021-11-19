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

define("PUB", ROOT . DS . $index_directory . DS); // System path to your script root.

// Set script to use only UTF8
mb_internal_encoding('UTF-8');
$utf_set = ini_set('default_charset', 'utf-8');

if (!$utf_set) {
    throw new Exception('could not set default_charset to utf-8, please ensure it\'s set on your system!');
}

// Set output to UTF8
mb_http_output('UTF-8');

// Pre-Setup the error session variable.

if(!isset($_SESSION['Err']))
{
	$_SESSION['Err'] = "";
}

//Set Default Controller (HOME PAGE)
define("HM", ucfirst($default_homepage));

//Set Default Controller (HOME PAGE)
define("LA", strtolower($default_language));

//Set showErrors as defined in /Config/Settings.php
define("ERR", $show_errors);

//Set Site name
define("NM", $_SERVER['SERVER_NAME']);


// Set the TLD or domain name.
define("TLD", strtolower($project_tld));
define("COPY", $default_copyright);
define("CSS", TLD . "css");
define("JS", TLD . "js");
define("IMG", TLD . "img");
define("SA", "  "); // Set default tag spacing. = 1 tab


// Create nonce value to enable inline Javascript when nonce is SET in script tag.
define("NONCE", strtolower(base64_encode(openssl_random_pseudo_bytes(16))));

// Default CSP setup made easier to see and change.
// Each line is a seperate setting. 

// Setup the CSP tags.
// This needs to go in a CSP class.
$cspvar = " default-src 'self';";
$cspvar .= " base-uri 'self';";
$cspvar .= " frame-ancestors 'self';";
$cspvar .= " upgrade-insecure-requests;";
$cspvar .= " object-src 'none';";
$cspvar .= " style-src 'self' 'report-sample' fonts.googleapis.com;";
$cspvar .= " img-src 'self' data:;";
$cspvar .= " media-src 'self';";
$cspvar .= " worker-src 'none';";
$cspvar .= " font-src fonts.gstatic.com;";
$cspvar .= " connect-src 'self';";

// GOOGLE CSP Setup if Google reCAPTCHA is enabled
// This allows google to function.
if(GRC)
{
	$cspvar .= " script-src 'unsafe-inline' 'nonce-". NONCE ."' 'report-sample' https://www.gstatic.com/recaptcha/ https://www.google.com/recaptcha/ https://recaptcha.google.com/recaptcha/;";
	$cspvar .= " frame-src 'self' https://www.google.com/recaptcha/ https://recaptcha.google.com/recaptcha/;";
	$cspvar .= " child-src 'self' https://www.google.com/recaptcha/ https://recaptcha.google.com/recaptcha/;";
	$cspvar .= " form-action 'self' https://www.google.com/recaptcha/ https://recaptcha.google.com/recaptcha/;";
}
else
{
	$cspvar .= " script-src 'self' 	'nonce-". NONCE ."' 'report-sample';";
	$cspvar .= " frame-src 'self';";
	$cspvar .= " child-src 'self';";
	$cspvar .= " form-action 'self';";
}
$cspvar .= " manifest-src 'self';";
$cspvar .= " prefetch-src 'self';";

//Set default CSP policy
define("CSP", $cspvar);
unset($cspvar);

//define the default extension (Default: PHP)
define("E", ".php");