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

return "// Path to your index.php
// If your script is in the public_html directory, leave this as is.
// however if the directory is named something different, or the script
// is in a subdirectory, change it here (example: public_html to 
// public_html/subdirectory.
\$index_directory = '{webdir}';
 
\$project_tld = 'https://{website}'; // Don't forget the trailing slash https://example.com <-- must be and ending /.

\$default_language = 'en'; // selected language .php file must be setup /lang dir.
\$default_homepage = 'Home'; // Points to the name of a controller in /Controller dir.
\$default_copyright = '{copy}'; // this will show up on the bottom as Copyright YEAR (c) [whatever you put here].

// For testing purposes. This will enable all errors to be displayed. 
// It is NOT a good idea to leave this on in production.
\$show_errors = {errors}; 

//GOOGLE reCAPTCHA Global Settings. See https://developers.google.com/recaptcha/ for codes.
define('GRC', {grc}); // FALSE TO DISABLE GOOGLE RECAPTCHA v3.
define('GPUBKEY', '{grcpub}'); // Public key provided by Google.
define('GPSECKEY', '{grcpriv}');// SECRET key provided by Google.

// How long the login code emailed to users will be valid for (minutes). 
// Caution: This affects site-wide timeout for security check codes. 
//					After the specified timespan any generated verification code
//					will no longer be valid. (Default: 15)
define('EC', {timing});

// How many numbers are sent to the user as a verification code.
// WARNING: changing this while someone is trying their code may deny them access.
// If that happens, they will have to wait until timeout to get a new one.
// DEFAULT IS 6; Will only accept 4-8
define('CN', {length});


// How many tries the user has to get the code correct before it disables the code.
define('TR', {tries});

// DON'T FORGET TO CHECK the index.php in the project_tld to change the APP dir.
// this only matters if you called the directory something other than FF.
// , if your index.php file is in a subdirectory of public_html or your web root 
// directory is named something other than `public_html` (adjust the index_directory
// variable above to fix this).";