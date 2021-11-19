<?php
session_start();

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

// Used to play ball with the ROOT check on the include
// file from the FF directory. Specifically \FF\Model\Secure.php
define('ROOT', realpath(dirname(__FILE__). DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR));

/*

This file is essentially broken into two (2) sections:

	1) Form Submit Validation Area.
	2) Form Submit Processing (Actual Install).

The $cp variable refers to a called instance of class.php in the install directory as well.
See that file for all of the validation and processing methods.

*/


// Initialize the required Session variable(s).
$_SESSION["install_error"] = array(); 

// Full path to the class.php file
include_once("class.php");
$cp = new \FF\Install(" ");

//////////////////////////
//       SECTION 1
// Form Submit Validation.
//////////////////////////


// This area deals entirely with checking if the form was submitted,
// and processing the form if it was.
// If anything is wrong, stop the install.
if($_SERVER["REQUEST_METHOD"] === "POST")
{

	// The install is a go until it is not.
	$install = false;

	// Check if all minimum requirements are met. PHP vesion, SSL, Https, PDO
	if($cp->validateInitReq())
	{
		$install = true;
	}

	// Checks that the entered install directory exists.
	if($cp->validateDir($_POST["installdirpath"], $_POST["installdirname"], "filedir"))
	{
		$install = true;
	}

	// Checks that the entered web root directory exists.
	if($cp->validateDir($_POST["installdirpath"], $_POST["webdir"], "webdir"))
	{
		$install = true;
	}

	// Checks that the Config directory in the main install exists and is writable
	if($cp->validateDir($_POST["installdirpath"], $_POST["installdirname"], "config"))
	{
		$install = true;
	}

	// Runs a cURL check on the web address to make sure the SSL cert is good.
	if($cp->validateSite($_POST["website"]))
	{
		$install = true;
	}

	// Makes sure that the entered limits integers are within bounds.
	// This is for code timeout, code length and code tries.
	if($cp->validateNums($_POST["codetimefield"], $_POST["codelenfield"], $_POST["codetriesfield"]))
	{
		$install = true;
	}

	// First checks if the Google reCAPTCHA feature is set as enabled.
	// If so, check that the keys are correct with Google.
	if(isset($_POST["recapfield"]) && empty($_POST["recapfield"]) && $_POST["recapfield"] === "TRUE")
	{
		if($cp->validateRC($_POST["privkeyfield"], $_POST["pubkeyfield"]))
		{
			$install = true;
		}
	}

	// Check the entered install user credentials and verify
	// that they have all required privilges.
	if($cp->testDb("install", $_POST["dbase_address"], $_POST["dbase_name"], $_POST["inst_dbase_user"], $_POST["inst_dbase_pass"], "ALL"))
	{
		$install = true;
	}

	// Check the entered FolioFrame execute only database user 
	// credentials and that only execute privilege is enabled.
	if($cp->testDb("user", $_POST["dbase_address"], $_POST["dbase_name"], $_POST["dbase_user"], $_POST["dbase_pass"], "Execute"))
	{
		$install = true;
	}

	// Make sure something is entered for the copyright.
	if($cp->validateCopyright($_POST["copyfield"]))
	{
		$install = true;
	}

	// Validate the email entered for MX records and a 
	// valid domain.
	if($cp->validateEmail($_POST["admin_email"]))
	{
		$install = true;
	}


//////////////////////////
//       SECTION 2
// Form Submit Processing (Install).
//////////////////////////

	// If nothing failed in Section 1, proceed.
	// On any error below, the script will abort and reload 
	// the page with the error displayed.
	// ( This area definitly needs some streamlining work )
	if($install)
	{	
		// Setup the timestamp for the logfile name.
		// Prints something like: Monday 8th of August 2005 03:12:46 PM
		$logName = date('m-j-Y_G-i-s');
		// Setup a prefix log string for the install log file.
		$logPre = "Log " . date('F j, Y - G:i:s') . " - ";
		try
		{
			// Reset the error session variable in case something remained buffered.
			// This shouldn't happen since all section 1 tests passed, but just incase.

			// Attempt to install the database, including structure and stored procedures.
			if($cp->installDB($_POST["dbase_name"], $_POST["dbase_address"], $_POST["inst_dbase_user"], $_POST["inst_dbase_pass"], $_POST["dbase_prefix"], $_POST["dbase_engine"], $_POST["dbase_char"]))
			{
				// Create and append to logfile.
				// createFile(PathtoFile, Message, Add version?, Append?)
				$cp->createFile("installLog_". $logName . ".log", $logPre . "Database Installed OK.", false, true);

				// Attempt to configure and copy database settings files.
				// to the application root (FF/Config).
				if($cp->installDBConfig($_POST["dbase_name"], $_POST["dbase_address"], $_POST["inst_dbase_user"], $_POST["inst_dbase_pass"], $_POST["dbase_user"], $_POST["dbase_pass"], $_POST["dbase_char"], $_POST["installdirpath"], $_POST["installdirname"]))
				{
					$cp->createFile("installLog_". $logName . ".log", $logPre . "DB Config File generated!", false, true);

					//Add the admin user's email properly with the admin flag set to 1 (Admin).
					if($cp->installAdminUser($_POST["dbase_name"], $_POST["dbase_address"], $_POST["inst_dbase_user"], $_POST["inst_dbase_pass"], $_POST["dbase_prefix"], $_POST["admin_email"], $_POST["admin_name"], $_POST["admin_fbook"], $_POST["admin_linked"], $_POST["admin_reddit"], $_POST["admin_twit"], $_POST["admin_git"], $_POST["admin_insta"], $_POST["installdirpath"], $_POST["installdirname"]))
					{
						$cp->createFile("installLog_". $logName . ".log", $logPre . "Admin User Created Sucessfully!", false, true);

						// Attempt to configure and copy application settings files.
						// to the application root (FF/Config).
						if($cp->installSettings($_POST["website"], $_POST["copyfield"], $_POST["devmode"], $_POST["recapfield"], $_POST["pubkeyfield"], $_POST["privkeyfield"], $_POST["codetimefield"], $_POST["codelenfield"], $_POST["codetriesfield"], $_POST["webdir"], $_POST["installdirpath"], $_POST["installdirname"]))
						{
							$cp->createFile("installLog_". $logName . ".log", $logPre . "Application Settings file created successfully!", false, true);

							// Attempt to replace the install index with the new one for the site.
							if($cp->installIndex($_POST["installdirname"], $_POST["installdirpath"], $_POST["webdir"]))
							{
								$cp->createFile("installLog_". $logName . ".log", $logPre . "New Index File Created!", false, true);

								// Install the new .htacces file, while backing up the old one in
								// a backup directory (webroot/Backup)
								if($cp->installHtaccess($_POST["installdirpath"], $_POST["webdir"]))
								{
									$cp->createFile("installLog_". $logName . ".log", $logPre . ".htaccess file created!", false, true);

									// Install the php-FolioFrame icon, but
									// only if one doesn't already exist.
									$cp->installFavicon($_POST["installdirpath"], $_POST["webdir"]);

									// The install is complete move the script 
									// to section 5 (Success Output)
									unset($_SESSION);
									session_destroy();
									$cp->createFile("installLog_". $logName . ".log", $logPre . "Initial Install Complete!!!", false, true);

									// Send back to new index.
									if(isset($_POST["install_url"]))
									{
										$url = $_POST["install_url"];
									}
									else
									{
										$url = "/";
									}
									header("Location:" . $url, true, 302);
									exit();

								}
								else
								{
									$cp->setError("Unable to create .htaccess file in webroot.");
								}
							}
							else
							{
								$cp->setError("Unable to create new index file in webroot.");
							}

						}
						else
						{
							$cp->setError("Unable to create new settings file in " . $_POST["installdirpath"] . DIRECTORY_SEPARATOR . $_POST["installdirname"] . ".");
						}
					}
					else
					{
						$cp->setError("Unable to add administrator user.");
					}
				}
				else
				{
					$cp->setError("Unable to create new config file in " . $_POST["installdirpath"] . DIRECTORY_SEPARATOR . $_POST["installdirname"] . ".");
				}
			}
			else
			{
				$cp->setError("Unable to install database structure.");
			}
		}
		catch(\Exception $e)
		{
			$cp->setError("An unexpceted error occurred during install: " . $e);
		}
	}


	// Set the POST values as $_SESSION to
	// have them back on the install inputs
	// instead of the user having to re-input.
	$_SESSION["POST_VARS"] = array(
	"dbase_name" => $_POST["dbase_name"],
	"dbase_address" => $_POST["dbase_address"],
	"inst_dbase_user" => $_POST["inst_dbase_user"],
	"inst_dbase_pass" => $_POST["inst_dbase_pass"],
	"dbase_prefix" => $_POST["dbase_prefix"],
	"dbase_engine" => $_POST["dbase_engine"],
	"dbase_char" => $_POST["dbase_char"],
	"dbase_user" => $_POST["dbase_user"],
	"dbase_pass" => $_POST["dbase_pass"],
	"installdirpath" => $_POST["installdirpath"],
	"installdirname" => $_POST["installdirname"],
	"admin_email" => $_POST["admin_email"],
	"admin_name" => $_POST["admin_name"],
	"admin_fbook" => $_POST["admin_fbook"],
	"admin_linked" => $_POST["admin_linked"],
	"admin_reddit" => $_POST["admin_reddit"],
	"admin_twit" => $_POST["admin_twit"],
	"admin_git" => $_POST["admin_git"],
	"admin_insta" => $_POST["admin_insta"],
	"website" => $_POST["website"],
	"copyfield" => $_POST["copyfield"],
	"devmode" => $_POST["devmode"],
	"recapfield" => $_POST["recapfield"],
	"pubkeyfield" => $_POST["pubkeyfield"],
	"privkeyfield" => $_POST["privkeyfield"],
	"codetimefield" => $_POST["codetimefield"],
	"codelenfield" => $_POST["codelenfield"],
	"codetriesfield" => $_POST["codetriesfield"]
	);

	// Send back to install on fail
	if(isset($_POST["install_url"]))
	{
		$url = $_POST["install_url"] . "/install.php";
	}
	else
	{
		$url = "/install.php";
	}
	header("Location:" . $url, true, 302);
	exit();
}
else
{
	$cp->setError("Direct loading of the form processing file is not allowed.");
}

