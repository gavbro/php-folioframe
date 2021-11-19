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

Namespace FF;

/*

	This install class is broken into six (6) main sections:

	They are: test, validate, check, install, prepare, support

	1) Test Methods - Like check methods below, these are methods to verify something, but generally indepentant of 
	   Validate or Check methods.
	2) Validate Methods - These are heftier functions that do final preparations for install. 
	   They often use Check methods as helpers.
	3) Check methods - Smaller helper methods used to do quick checks on variables/arrays.
	4) Install Methods - These are methods that perform install tasks, like running queries
	   or creating files. They depend heavily on validate,  check and support methods.
	5) Prepare Methods - Methods that take an input and return a reworked or reformatted
	   version of the input.
	6) Support Methods - Any other method that doesn't fit in the above sections. Mainly conversions,
	   error output and non-confirming helper functions.

*/

Class Install
{
	private $error; // An array to hold any errors to display back to the user.
	private $info; // Loads and holds the version text to be added to generated files.

	public function __construct(?string $path)
	{
		$this->getInfo($path); // Set $this->info variable if file exists.
 	}

//\\//\\//\\//\\//\\//\\//
//       SECTION 1
// Test Methods.
//
// These are methods to verify something, but generally
// work indepentant of Validate or Check methods.
//\\//\\//\\//\\//\\//\\//

/**
 * @param mixed: $anything Database credentials, error prefix, permission type. 
 *
 * @throws Nothing explicitly. Returns errors to the user.
 *
 * @return Boolean: True when the db connects and the permissions are ok, false if not.
*/

	public function testDb($type, $host, $db, $username, $pass, $permission = 'Execute'): bool
	{
		// Make sure the username and password have values. Warn the user if not.
		if(strlen($username) > 3 && strlen($pass) > 3)
		{
			// Also, Check the database and host are set.
			if(strlen($db) > 3 && strlen($host) > 3)
			{
				// Try the connection first. There isn't much point looking for
				// permissions if it cannot connect.
				try
				{
					$dsn = 'mysql:host='. $host . ';dbname='. $db;
					if($dbc = new \PDO($dsn, $username, $pass))
					{
						// Since the connection worked, lets check to see if the permissions are ok.
						return $this->testPerms($type, $username, $host, $db, $dbc, $permission);
					}
					else
					{
						$_SESSION["install_error"]["dbase_" . $type] = "Unable to connect to the database.";
						return false;
					}
				}
			    catch (\Exception $e)
			    {
			      $_SESSION["install_error"]["dbase_" . $type] = "Connection Failed: Please double check your credentials.";
			      return false;
			    }
			}
			else
			{
				$_SESSION["install_error"]["dbase_main"] = "Please use a valid database name. This one is too short!";
				return false;
			}
		}
		else
		{
			$_SESSION["install_error"]["dbase_" . $type] = "Database " . ucfirst($type) . " Credentials: Username and/or password cannot be blank!";
			return false;
		}
	}

/**
 * @param mixed: $anything Database credentials, error prefix, permission type. 
 *
 * @throws Nothing explicitly. Returns errors to the user.
 *
 * @return Boolean: True that permissions were ok for the user type (install or authentication), false if not.
*/

	private function testPerms($type, $username, $host, $db, $conn, $permission): bool
	{
	    try
	    {
	    	// Get all permissions from the database for the user in question.
	        $privQuery = $conn->query("select * from information_schema.schema_privileges");
	        $result = $privQuery->fetchAll();

	        // If we are looking into the install user.
	        if($permission === "ALL")
	        {

	        	// Variable to start the cpint of any permissions that are found in the database
	        	// matching any of the blow permissions array values.
	        	$set = 0; 

	        	// The permissions we are looking for. As of this note, these are permissions
	        	// granted with mysql GRANT ALL on database etc.
	        	// These could be trimmed to match only what is needed in the future.
	        	$perms = array( 
	        		"SELECT",
	        		"INSERT",
	        		"UPDATE",
	        		"DELETE",
	        		"CREATE",
	        		"DROP",
	        		"REFERENCES",
	        		"INDEX",
	        		"ALTER",
	        		"CREATE TEMPORARY TABLES",
	        		"LOCK TABLES",
	        		"EXECUTE",
	        		"CREATE VIEW",
	        		"SHOW VIEW",
	        		"CREATE ROUTINE",
	        		"ALTER ROUTINE",
	        		"TRIGGER",
	        		"EVENT");

	        	$pcount = count($perms);

	        	// run through the results to see if they match any of the permissions.
	        	// we want to see in there for this user.
	        	foreach($result as $cntKey => $perm_array)
	        	{
	        		if(in_array($perm_array["PRIVILEGE_TYPE"], $perms))
	        		{
	        			// Add to the counter if there is a match
	        			$set++;
	        		}
	        		
	        	}

	        	// Verify that the counted permissions that exists matches.
	        	// the complete list of permissions we require.
	        	if(($pcount - $set) === 0)
	        	{
	        		return true;
	        	}
	        	else
	        	{
	        		$_SESSION["install_error"]["dbase_" . $type] = "Not all privileges are granted to install user.";
	        		return false;
	        	}
	        }

	        // Now for the authorize user. 
	        elseif($permission === "Execute")
	        {
	        	// Set the default return result to No.
	        	$perm = false;

	        	// Count how many permissions are found.
	        	$total = 0;


	        	foreach($result as $perm_name => $value)
	        	{
	        		// Check if the execute priviledge is present.
	        		if($value["PRIVILEGE_TYPE"] === strtoupper($permission))
	        		{
	        			$perm = true;
	        		}
	        		$total++;
	        	}

	        	// Make sure the execute permssion exists, but is also
	        	// the only permission this user has.
	        	if($perm && $total === 1)
	        	{
	        		return true;
	        	}
	        	else
	        	{
	        		$_SESSION["install_error"]["dbase_" . $type] = "Execute privelege not found for user or other priveleges were also present. Only execute should be set for this user.";
	        		return false;
	        	}
	        }
	        else
	        {
	        	$_SESSION["install_error"]["dbase_" . $type] = "Permission value set incorrectly!, this is a code issue with the install file! Oops!!";
	        	return false;
	        }
	        return false;
	    }
	    catch (\Exception $e)
	    {
	    	$_SESSION["install_error"]["dbase_" . $type] = "Connection Failed: " . $e;
	      return false;
	    }   
	}

/**
 * @param float: Desired minimum PHP version to test against.
 *
 * @throws Nothing.
 *
 * @return Boolean: True when current version is higher.
*/

	public function testVersion($version): bool
	{
		if((float)substr(phpversion(),0,3) >= (float)$version)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

/**
 * @param None
 *
 * @throws Nothing.
 *
 * @return Boolean: True if https is present on port 443
*/

	public function testSecure(): bool
	{
	  	return
	    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
	    || $_SERVER['SERVER_PORT'] == 443;
	}

/**
 * @param None
 *
 * @throws Nothing.
 *
 * @return Boolean: True if openssl version is higher than value.
*/

	public function testOpenSSL(): bool
	{
		if(OPENSSL_VERSION_NUMBER > 268443727) // 268443727 = ~Version 5.0
		{ 
			return true;
		}
		else
		{
			return false;
		}
	}	

/**
 * @param None
 *
 * @throws Nothing.
 *
 * @return Boolean: True if PDO extension is loaded.
*/

	public function testPDO(): bool
	{
		if(extension_loaded ('PDO') || extension_loaded('pdo_mysql'))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

//\\//\\//\\//\\//\\//\\//
//       SECTION 2
// Validate Methods.
//
// Functions that do final preparations for install. 
// They often use Check methods as helpers.
//\\//\\//\\//\\//\\//\\//
	

/**
 * @param string: Directory and error id information.. 
 *
 * @throws Nothing explicitly. Returns errors to the user.
 *
 * @return Boolean: True if the directory exists and is writable.
*/

	public function validateDir($path, $dir, $name): bool
	{
		// Make sure the directory exists.
		if($this->checkDir($path, $dir))
		{
			if($name === "config")
			{	
				// Make sure the directory is writable.
				if($this->validateConfig($path, $dir))
				{
					return true;
				}
				else
				{
					$_SESSION["install_error"][$name] = "The " . $path . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . "Config directory must be writable.";
					return false;
				}
			}
			else
			{
				return true;
			}
		}
		else
		{
			$_SESSION["install_error"][$name] = "The path to this \"" . $path . DIRECTORY_SEPARATOR . $dir . "\" directory is incorrect or does not exist.";
			return false;
		}
	}

/**
 * @param string: Website Address. 
 *
 * @throws Nothing explicitly. Returns errors to the user.
 *
 * @return Boolean: True if the directory exists and is writable.
*/

	public function validateSite($site): bool
	{
		// Add the web address prefix and remove the trailing slash
		// if it is there.
		$site = "https://" . $this->removeTrailingSlash($site);

	    // Try to load the site and capture any errors.
		$ch = curl_init($site);
		curl_exec($ch);

		// Proceed if not errors found.
		if(!curl_errno($ch))
    	{
    		// Capture and assign the http Code (404, 200, 301 etc..)
	    	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	    	// If the code is 200 (OK) or 302 (Found) return true.
	    	// else the site didn't load properly.
			if($http_code === 200 || $http_code === 302)
			{
				return true;
			}
			else
			{
				$_SESSION["install_error"]["website"] = "Website Error: Unexpected HTTP CODE (" . $http_code . "). Please double check your site address.";
				return false;
			}
	    }
	    else
	    {
	    	$_SESSION["install_error"]["website"] = "Website Error: " . $this->curlError(curl_errno($ch));
	    	return false;
	    }
    	return true;
	}

/**
 * @param int: Numeric representations of required inputs.
 *
 * @throws Nothing explicitly. Returns errors to the user.
 *
 * @return Boolean: True if the numbers are withing range.
*/

	public function validateNums($timing, $length, $tries): bool
	{
		// Invoke correspdonging method for each input.
		if($this->checkTimeout($timing) && $this->checkLength($length) && $this->checkAttempts($tries))
		{
			// All PASS!
			return true;
		}
		else
		{
			// Return the appropriate error for whichever number failed the checks
			// back to the user.
			if(!$this->checkTimeout($timing))
			{
				$_SESSION["install_error"]["timing"] = "You must input a time interval betwen 15 and 60 (minutes).";
			}
			if(!$this->checkLength($length))
			{
				$_SESSION["install_error"]["length"] = "You must specify a user verification code length between 5 and 8.";
			}
			if(!$this->checkAttempts($tries))
			{
				$_SESSION["install_error"]["tries"] = "You must inidicate a maximum amount of user code attempts between 3 and 9.";
			}
			return false;
		}
	}

/**
 * @param string: Copyright string input.
 *
 * @throws Nothing explicitly. Returns errors to the user.
 *
 * @return Boolean: True if the string is set.
*/

	public function validateCopyright($copy): bool
	{
		if(strlen($copy) > 0)
		{
			return true;
		}
		else
		{
			$_SESSION["install_error"]["copy"] = "Add something for your Copyright. You can choose to not show it in your project, but at least it will be set in the config.";
			return false;
		}
	}

/**
 * @param string: Google reCAPTCHA keys
 *
 * @throws Nothing explicitly. Returns errors to the user.
 *
 * @return Boolean: True if both keys are valid.
*/

	public function validateRC($private, $public): bool
	{
		// Both are valid until proven otherwise.
		$valid = true;

		// validate the secret key against the reCAPTCHA API.
		if(!$this->validateRCSecret($private))
		{
			$valid = false;
		}

		// validate the public key string matches the expected structure.
		if(!$this->validateRCPublic($public))
		{
			$valid = false;
		}

		return $valid;
	}

/**
 * @param string: Google reCAPTCHA public key
 *
 * @throws Nothing explicitly. Returns errors to the user.
 *
 * @return Boolean: True if the key matches the expected string structure.
*/

	private function validateRCPublic($public): bool
	{
		// Remove all unexpected characters from the string.
		// Check if the string is still of an acceptable length.
		if(strlen(preg_replace("/[^0-9a-zA-Z_-]/","", $public)) > 35)
		{
			return true;
		}
		else
		{
			$_SESSION["install_error"]["rcpubkey"] = "Your public key doesn't seem right, please double check it!";
			return false;
		}
	}

/**
 * @param string: Google reCAPTCHA private key
 *
 * @throws Nothing explicitly. Returns errors to the user.
 *
 * @return Boolean: True if Google API recognizes the user.
*/

  private function validateRCSecret($secret): bool
  {
  		// We are only checking the secret key
		$data = array(
	      'secret' => $secret
		);

		// Setup curl to post to the google api correctly.
		$verify = curl_init();


		curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify"); // Google reCAPTCHA url
		curl_setopt($verify, CURLOPT_CUSTOMREQUEST, "POST"); // Request method. POST is preferred.
		curl_setopt($verify, CURLOPT_ENCODING, ""); 
		curl_setopt($verify, CURLOPT_MAXREDIRS, 10); // Only allow 10 x 301 redirects by default.
		curl_setopt($verify, CURLOPT_TIMEOUT, 30); // Timeout after 30 seconds.

		// Don't cache the results.
		curl_setopt($verify, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($verify, CURLOPT_HTTPHEADER, array("cache-control: no-cache", "content-type: application/x-www-form-urlencoded"));

		curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));

		curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, true); // Make sure the connection is secure.
		curl_setopt($verify, CURLOPT_RETURNTRANSFER, true); // Grab the response from Google.

		// Assign the response to a variable.
		$response = curl_exec($verify);

		// Grab any curl errors if they occur.
		$err = curl_error($verify);

		// We have what we need, so close the connection.
		curl_close($verify);	

		// Did we get a response at all?
		if($response)
		{
			// Google returns the results as JSON, so lets convert that to an array.
			$final = json_decode($response, true);

			// Did the array set correctly?
			if(is_array($final))
			{
				// check if the error code recognizes the user.
				// if it doesn't it returns a differrent response.
				if($final["error-codes"][0] === "invalid-input-response")
				{
					return true;
				}
				else
				{
					$_SESSION["install_error"]["rckey"] = "Google does not recognize your secret key. Please keep in mind the following: <p class=\"note\">You must have a valid key from google.</p><p class=\"note\">The key must be generated for this domain!, Google validates that the request is coming from the registered domain.</p>";
					return false;
				}
			}
			else
			{
				$_SESSION["install_error"]["rckey"] = "Your key response from google was garbled. Please try again.";
				return false;
			}
		}
		else
		{
			$_SESSION["install_error"]["rckey"] = "Google didn't respond to the key validation request. Please try again.";
			return false;
		}
  	}

/**
 * @param string: Path string and directory.
 *
 * @throws Nothing. Error to user from another method.
 *
 * @return Boolean: path is writable or not.
*/

  	private function validateConfig($path, $dir): bool
  	{
  		// prepare the proper path string.
  		$fullPath = $this->preparePath($path, $dir);

  		// clear the state cache which may report
  		// false neg/pos if cached and changed.
		clearstatcache($fullPath);

		// writable check
		if(false !== is_writable($fullPath))
		{
			return true;
		}
		else
		{
			return false;
		}
  	}

/**
 * @param None
 *
 * @throws Nothing. Error to user from child method.
 *
 * @return Boolean: security check passed.
*/

	public function validateInitReq(): bool
	{
		// Set security as OK to start.
		$ok = true;

		// Check each required security measure.
		if(!$this->testSecure())
		{
			$ok = false;
			$_SESSION["install_error"]["reqSecure"] = "You must use a valid https secure certificate.";
		}

		if(!$this->testVersion(7.2))
		{
			$ok = false;
			$_SESSION["install_error"]["reqVersion"] = "Your PHP version isn't high enough, please update to at least 7.2 ( Yours is showing: " . substr(phpversion(),0,3) . ").";
		}
		
		if(!$this->testPDO())
		{
			$ok = false;
			$_SESSION["install_error"]["reqPDO"] = "The PDO driver must be installed.";
		}
		if(!$this->testOpenSSL())
		{
			$ok = false;
			$_SESSION["install_error"]["reqSSL"] = "You must have a minimal OPENSSL version of 1.0.0 to support openssl_random_pseudo_bytes().";
		}

		// return false overall if any are false.
		// true if none are false.
		return $ok;
	}

/**
 * @param string: Email string to be validated.
 *
 * @throws Nothing. Error to user from child method.
 *
 * @return Boolean: True if email is ok.
*/

	public function validateEmail($email): bool
	{
		// Check the email is formed correctly 
		// and has valid MX records.
		if(filter_var($email, FILTER_VALIDATE_EMAIL) && $this->checkMX($email))
		{
			return true;
		}
		else
		{
			$_SESSION["install_error"]["admin_install"] = "You must use a valid email address as it will identify you as the admin going forward. Please try again.";
			return false;
		}
	}

//\\//\\//\\//\\//\\//\\//
//       SECTION 3
// Check Methods.
//
// Smaller helper methods used to do quick 
// checks on variables.
//\\//\\//\\//\\//\\//\\//

/**
 * @param string: Path string and directory.
 *
 * @throws Nothing. Error to user from another method.
 *
 * @return Boolean: path is writable or not.
*/

	private function checkDir($path, $dir): bool
	{
			// prepare the proper path string.
  		$fullPath = $this->preparePath($path, $dir);

  		// clear the state cache which may report
  		// false neg/pos if cached and changed.
		clearstatcache($fullPath);

		// is the path a directory?
		if(false !== is_dir($fullPath))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

/**
 * @param int: User input of time in minutes
 *
 * @throws Nothing. Error to user from another method.
 *
 * @return Boolean: Timeout is within numerical bounds.
*/

	public function checkTimeout($input): bool
	{
		if(!$this->numbersOnly($input))
		{
				return false;
		}
		else
		{
			 if($this->betweenNums($input, 5, 60))
			 {
			 		return true;
			 }
			 else
			 {
			 	return false;
			 }
		}
	}

/**
 * @param int: User input of code length in number of characters
 *
 * @throws Nothing. Error to user from another method.
 *
 * @return Boolean: Length is within numerical bounds.
*/

  private function checkLength($input): bool
	{
		if(!$this->numbersOnly($input))
		{
				return false;
		}
		else
		{
			 if($this->betweenNums($input, 4, 8))
			 {
			 		return true;
			 }
			 else
			 {
			 	return false;
			 }
		}
	}	

/**
 * @param string: Email address to check
 *
 * @throws Nothing. 
 *
 * @return Boolean: True if MX records match.
*/

	private function checkMX($email): bool
	{
		// Separate the email to before and after the @ symbol
		list($user, $domain) = explode("@", $email);

		// Check the domain for MX records
		$arr = dns_get_record($domain, DNS_MX);

		// MX domain matches the entered domain = pass
		if(isset($arr[0]['host']) && $arr[0]['host'] == $domain && !empty($arr[0]['target']))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

/**
 * @param int: User input of login attempts allowed
 *
 * @throws Nothing. 
 *
 * @return Boolean: True if within numerical bounds.
*/

	private function checkAttempts($input): bool
	{
		// make sure it is a number
		if(!$this->numbersOnly($input))
		{
				return false;
		}
		else
		{
			// make sure the number is ok.
			if($this->betweenNums($input, 3, 9))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}

/**
 * @param string: Error reference string, location
 *
 * @throws Nothing explicitly. Error displayed to user if needed. 
 *
 * @return Boolean: True if within numerical bounds.
*/

	public function checkError($err, $end = ""): string
	{
		if($end === "end")
		{
			// is the error is set in the class variable for the end div
			if(isset($_SESSION["install_error"][$err]) && !empty(trim($_SESSION["install_error"][$err])) > 0)
			{
				return "</div>";
			}
			else
			{
				return "";
			}
		}
		elseif($end === "all")
		{
			// if the error is set in the class variable at all
			if(isset($_SESSION["install_error"]) && count($_SESSION["install_error"]) > 0)
			{
				// output all errors in the this->error array.
				$returnstring = "<div class=\"errordiv\"><h2>There are some things to look into before we can install:</h2><ol>"; 
				foreach($_SESSION["install_error"] as $errName => $errVal)
				{ 
					$returnstring .= "<li>" . $errVal . "</li>"; 
				} 
				$returnstring .= "</ol><p>These errors are also highlighted below if applicable.</p></div>";
				return $returnstring;
			}
			else
			{
				// No errors found.
				return "";
			}
		}
		else
		{
			// is the error is set in the class variable for the beginning div
			if(isset($_SESSION["install_error"][$err]) && !empty(trim($_SESSION["install_error"][$err])) > 0)
			{
				return "<div class=\"errordiv\"><p class=\"errortitle\">" . $_SESSION["install_error"][$err] . "</p>";
			}
			else
			{
				return "";
			}
		}
	}

//\\//\\//\\//\\//\\//\\//
//       SECTION 4
// Install Methods.
//
// These are methods that perform install tasks, like running queries
// or creating files. They depend heavily on validate and check methods.
//\\//\\//\\//\\//\\//\\//

/**
 * @param string: Required directory paths
 *
 * @throws Nothing explicitly. Error displayed to user if needed. 
 *
 * @return Boolean: True if install is successfull
*/

	public function installIndex($installdir, $path, $dir): bool
	{
		// properly form the fill path.
		$fullPath = $this->preparePath($path, $dir);

		// Make sure the required install file is present
		// and assign contents to a variable as a string.
		if($tmp_string = include('newindex.php'))
		{
			// Load the user indicated directory into the index file.
			$string = $this->prepareConfigFile($tmp_string, array(array("~\{installdir\}~", $installdir)));

			// Attempt to create the index file with the compiled
			// string of information from above.
			if($this->createFile($fullPath . DIRECTORY_SEPARATOR . "index.php", $string))
			{
				return true;
			}
			else
			{
				$_SESSION["install_error"]["settings_file"] = "Unable to open or create the index.php file in the webroot directory.";
				return false;
			}
		}
		else
		{
			$_SESSION["install_error"]["file_error"] = "Unable to locate and load the index.php file in the install direcetory. Please ensure it is there.";
			return false;
		}
	}

/**
 * @param string: Db credentials, directory paths
 *
 * @throws Nothing explicitly. Error displayed to user if needed. 
 *
 * @return Boolean: True if install is successfull
*/

	public function installDBConfig($dbase_name, $dbase_host, $dbase_install_user, $dbase_install_pass, $dbase_user, $dbase_pass, $dbase_charset, $path, $dir): bool
	{
		// prepare the proper path string.
		$fullPath = $this->preparePath($path, $dir);

		// Make sure the required config file is present
		// and assign contents to a variable as a string.
		if($tmp_string = include('db_config.php'))
		{

			// Preload a multidimensional array with the 
			// needle to look for in the config file and
			// what value to replace it with. 
			$strArray = array(
				array("~\{dbase_name\}~", $dbase_name), 
				array("~\{dbase_host\}~", $dbase_host), 
				array("~\{dbase_user\}~", $dbase_user),
				array("~\{dbase_pass\}~", $dbase_pass),
				array("~\{dbase_char\}~", $dbase_charset),
				array("~\{dbase_key\}~", base64_encode(openssl_random_pseudo_bytes(32))),
				array("~\{install_user\}~", $dbase_install_user),
				array("~\{install_pass\}~", $dbase_install_pass)
			);

			// Do all the neccessary needle/haytstack replacements
			// from the above array and assign to a new string variable.
			$db_string = $this->prepareConfigFile($tmp_string, $strArray);

			// Setup the string to create another file in the ./install 
			// directory called inst_db.php with the install db user 
			// credentials so the installer can warn the user to remove
			// the user. 
			$inst_string = "return array(\"host\" =>\"" . $dbase_host . "\", \"db_name\" =>\"" . $dbase_name . "\", \"install_user\" =>\"" . $dbase_install_user . "\", \"install_pass\" => \"" . base64_encode($dbase_install_pass) . "\");";

			// Attempt to create both the /FF/Dbsettings.php and inst_db.ph
			// files at onces. Any problems, abort and send error out to user.
			if($this->createFile($fullPath . DIRECTORY_SEPARATOR . "Config" . DIRECTORY_SEPARATOR . "Dbsettings.php", $db_string) && $this->createfile("inst_db.php", $inst_string))
			{
				return true;
			}
			else
			{
				$_SESSION["install_error"]["settings_file"] = "Unable to open or create the Dbsettings.php or temporary install db file in the Config/Install directories.";
				return false;
			}
		}
		else
		{
			$_SESSION["install_error"]["file_error"] = "Unable to locate and load the db_config.php file in the install direcetory. Please ensure it is there.";
			return false;
		}
	}

/**
 * @param string: Db credentials
 *
 * @throws Nothing explicitly. Error displayed to user if needed. 
 *
 * @return Boolean: True if install is successfull
*/

	public function installDB($dbase_name, $dbase_host, $dbase_install_user, $dbase_install_pass, $dbase_install_prefix, $dbase_engine, $dbase_charset): bool
	{
		// First make sure the install file is there.
		// and assign it to a string variable or error.
		if($base_string = include('db_install.php'))
		{
			// Attempt to prepare the entire structure from
			// the db_install.php tempate file, inject the new
			// parameters from the user and execute the queries.
			try
			{
				// Preload a multidimensional array with the 
				// needle to look for in the db file and
				// what value to replace it with.
				$prepArray = array(
					array("~\{dbase_install_prefix\}~", $dbase_install_prefix), 
					array("~\{dbase_name\}~", $dbase_name), 
					array("~\{dbase_engine\}~", $dbase_engine), 
					array("~\{dbase_char\}~", $dbase_charset)
				);

				// Do all the neccessary needle/haytstack replacements
				// from the above array and assign to a new string variable.
				$dbase_string = $this->prepareConfigFile($base_string, $prepArray);	

				//Set the host and database DSN for connection.
				$dsn = 'mysql:host='. $dbase_host . ';dbname='. $dbase_name;

				// Attempt to connect
				if($db = new \PDO($dsn, $dbase_install_user, $dbase_install_pass))
				{
					// Set to return exceptions if they occur.
					$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

					// Run the query.
					if(!$db->query($dbase_string))
					{
						// return the error to the user as normal,
						// but include the PDO exception info.
						$_SESSION["install_error"]["dbase_query"] = "Unable to run the query: " . json_encode($db->errorInfo());
						return false;
					}
					else
					{
						return true;
					}
				}
				else
				{
					$_SESSION["install_error"]["dbase_query"] = "Unable to connect to the database.";
					return false;
				}
			}
	    catch (\Exception $e)
	    {
	    	$_SESSION["install_error"]["dbase_query"] = "Unable to install database: " . $e;
	     	return false;
	    }
		}
		else
		{
			$_SESSION["install_error"]["file_error"] = "Unable to locate and load the db_install.php file in the install direcetory. Please ensure it is there.";
			return false;
		}
	}

/**
 * @param string: Db credentials, User settings, Paths
 *
 * @throws Nothing explicitly. Error displayed to user if needed. 
 *
 * @return Boolean: True if install is successfull
*/

	public function installAdminUser($dbname, $dbhost, $dbuser, $dbpass, $dbprefix, $email, $name, $fbook, $linkd, $redd, $twit, $git, $insta, $path, $dir): bool
	{
		// prepare the proper install path string.
		$fullPath = $this->preparePath($path, $dir);

		// Set the model path.
		$modelPath = $fullPath . DIRECTORY_SEPARATOR . "Model" . DIRECTORY_SEPARATOR;
		
		// Set the path for the social model file.
		$socialPath = $modelPath . "Social.php";
		
		// Set the path for the security model file.
		$securePath = $modelPath . "Secure.php";

		// Bring in the security class and 
		// create an instance variable.
		include_once($securePath);
		$sec = new \Security\Secure();

		// Set the path for the previously installed
		// dbsettings file and bring it in.
		$dbsettingsPath = $fullPath . DIRECTORY_SEPARATOR . "Config" . DIRECTORY_SEPARATOR . "Dbsettings.php";
		include_once($dbsettingsPath);
		$encKey = $dbsettings["Entry"]["key"];

		// Encrypt the admin email to match any other user.
		// and follow the same secure user setup in the database
		// for future logins.
		$secemail = $sec->encryptData($email, $encKey); 

		// Preset the email as a hash to be
		// used as a one-way verification tool
		// when quickly trying to determine
		// if a user already exists in the database
		// or not. SHA256 is used in the installed
		// stored procedures as well. (See ./install/db_config.php)

		$emailHash = hash('sha256', $email);


		// Setup a query to check if the user already exists.
		// Which should not really be possible on new install
		// , but just in case!
		$duplication_check_query = "SELECT * FROM `" . $dbname . "`.`" . $dbprefix . "entry` WHERE `" . $dbprefix . "entry`.`en_hash` = '" . $emailHash . "'";

		/*

			Begin query preparation area.

			Below is a series of strings designed to only
			include the parts of the Admin user settings
			that are entered, once they have been verified.

			The pattern goes like this:

				- Set required query (header), columns and values to be 
				- entered. Add columns and values for verified input 
				- values only. combine them at the end and run the query.

			{

				***NOTE: Facebook and Linkedin are currently not checked
				in this version. This is a hopeful addition later once a
				reasonable way around their protections can be 
				found with the intention of verifying a user exists on their
				platform without having to use their API. They are cleverly 
				hiding their users with javascript redirects. 
				(so annoying right?!)

			}

		*/

		$insert_query_prepares = array();

		$insert_query_header = "INSERT INTO `" . $dbname . "`.`" . $dbprefix . "entry` (";
		$insert_query_columns = "`" . $dbprefix . "entry`.`en_hash`,`" . $dbprefix . "entry`.`en_email`";
		$insert_query_values = ":email, :secemail ";

		$insert_query_prepares[":email"] = $emailHash;
		$insert_query_prepares[":secemail"] = $secemail;


		if(!empty(trim($name)))
		{
			$insert_query_columns .= ", `" . $dbprefix . "entry`.`en_name`";
			$insert_query_values .= ", :name";
			$insert_query_prepares[":name"] = $sec->encryptData($name, $encKey);
		}

		if(file_exists($socialPath))
		{
			include_once($socialPath);
			$soc = new \Social\User();

			if(!empty(trim($this->prepareSocialUsername($fbook))))
			{
				if($soc->facebook($fbook))
				{
					$insert_query_columns .= ", `" . $dbprefix . "entry`.`en_fbook`";
					$insert_query_values .= ", :fbook";
					$insert_query_prepares[":fbook"] = $sec->encryptData($fbook, $encKey);
				}
				else
				{
					$_SESSION["install_error"]["social_fbook"] = "Facebook user not found on https://facebook.com. Please double check the spelling or leave blank to continue.";
				}
			}

			if(!empty(trim($this->prepareSocialUsername($linkd))))
			{
				if($soc->linkedin($linkd))
				{
					$insert_query_columns .= ", `" . $dbprefix . "entry`.`en_linked`";
					$insert_query_values .= ", :lnkd";
					$insert_query_prepares[":lnkd"] = $sec->encryptData($linkd, $encKey);
				}
				else
				{
					$_SESSION["install_error"]["social_linked"] = "Linkedin user not found on https://linkedin.com. Please double check the spelling or leave blank to continue.";
				}
			}
			if(!empty(trim($this->prepareSocialUsername($twit))))
			{
				if($soc->twitter($twit))
				{
					$insert_query_columns .= ", `" . $dbprefix . "entry`.`en_twit`";
					$insert_query_values .= ", :twit";
					$insert_query_prepares[":twit"] = $sec->encryptData($twit, $encKey);
				}
				else
				{
					$_SESSION["install_error"]["social_twitter"] = "Twitter user not found on https://twitter.com. Please double check the spelling or leave blank to continue.";
				}
			}
			if(!empty(trim($this->prepareSocialUsername($git))))
			{
				if($soc->github($git))
				{
					$insert_query_columns .= ", `" . $dbprefix . "entry`.`en_git`";
					$insert_query_values .= ", :git";
					$insert_query_prepares[":git"] = $sec->encryptData($git, $encKey);
				}
				else
				{
					$_SESSION["install_error"]["social_git"] = "GitHub user not found on https://github.com. Please double check the spelling or leave blank to continue.";
				}
			}

			if(!empty(trim($this->prepareSocialUsername($redd))))
			{
				if($soc->reddit($redd))
				{
					$insert_query_columns .= ", `" . $dbprefix . "entry`.`en_reddit`";
					$insert_query_values .= ", :redd";
					$insert_query_prepares[":redd"] = $sec->encryptData($redd, $encKey);
				}
				else
				{
					$_SESSION["install_error"]["social_reddit"] = "Reddit user not found on https://reddit.com. Please double check the spelling or leave blank to continue.";
				}	
			}

			if(!empty(trim($this->prepareSocialUsername($insta))))
			{
				if($soc->instagram($insta))
				{
					$insert_query_columns .= ", `" . $dbprefix . "entry`.`en_insta`";
					$insert_query_values .= ", :insta";
					$insert_query_prepares[":insta"] = $sec->encryptData($insta, $encKey);
				}
				else
				{
					$_SESSION["install_error"]["social_insta"] = "Instagram user not found on https://instagram.com. Please double check the spelling or leave blank to continue.";
				}
			}

		}

		// Disgard the security and social classes. 
		unset($sec);
		unset($soc);

		//Set the user level to 1 (Admin)
		$insert_query_columns .= ", `" . $dbprefix . "entry`.`en_level`";
		$insert_query_values .= ", :level";
		$insert_query_prepares[":level"] = 1;
		

		$final_query = $insert_query_header . $insert_query_columns . ") VALUES (" . $insert_query_values . ");";

		/*

			End query preparation area.

		*/

		// Attempt to run the newly assembled query.
	  // (final_query) 
		try
		{
			// Set the db connection.
			$dsn = 'mysql:host='. $dbhost . ';dbname='. $dbname;

			// Attempt to connect.
			if($db = new \PDO($dsn, $dbuser, $dbpass))
			{
				// Set PDO to report exceptions.
				$db->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);

				// Actually run the query.
				$chk = $db->query($duplication_check_query);

				// Capture the result count
				$cnt = $chk->rowCount();
				unset($chk);

				// A retured row means a user already exists with the email.
				// Zero means no user already exists. Proceed.
				if($cnt === 0)
				{

					// Prepare the final_query
					$stmt = $db->prepare($final_query);

					// Bind all of the parameters
					foreach($insert_query_prepares as $ref => &$val)
					{
						$stmt->bindParam($ref, $val);
					}

					// Successful execute or
					// send error back to install user.
					if($stmt->execute())
					{
						return true;
					}
					else
					{
						$_SESSION["install_error"]["dbase_query"] = "Unable to insert admin user.";
						return false;
					}
				}
				else
				{
					$_SESSION["install_error"]["dbase_query"] = "User already exists in the database and we are unable to add the same email again. To continue, please delete the user from the database or choose another admin email to add.";
					return false;
				}
			}
			else
			{
				$_SESSION["install_error"]["dbase_query"] = "Unable to connect to the database.";
				return false;
			}
		}
		catch (\Exception $e)
		{
			$_SESSION["install_error"]["dbase_query"] = "Unable to add admin user: " . json_encode($e);
		 	return false;
		}
	}

/**
 * @param string,: int All inputs required for sitewide user settings
 *
 * @throws Nothing explicitly. Error displayed to user if needed. 
 *
 * @return Boolean: True if install is successfull
*/

	public function installSettings($website, $copy, $errors, $grc, $grcpub, $grcpriv, $timing, $length, $tries, $webdir, $path, $dir): bool
	{
		// Prepare the proper install path string.
		$fullPath = $this->preparePath($path, $dir);

		// Make sure the settings template file is there.
		if($settings_string = include("settings.php"))
		{
			// Setup the array with the needles and
			// replacement values.
			$settArray = array(
				array("~\{website\}~", $website),
				array("~\{copy\}~", htmlentities($copy)),
				array("~\{errors\}~", $errors),
				array("~\{grc\}~", $grc),
				array("~\{grcpub\}~", $grcpub),
				array("~\{grcpriv\}~", $grcpriv),
				array("~\{timing\}~", $timing),
				array("~\{length\}~", $length),
				array("~\{webdir\}~", $webdir),
				array("~\{tries\}~", $tries)
			);

			// replace needles with values and assign to 
			// variable as a string.
			$settings = $this->prepareConfigFile($settings_string, $settArray);

			// Attempt to create the new settings file
			if($this->createFile($fullPath . DIRECTORY_SEPARATOR . "Config" . DIRECTORY_SEPARATOR . "Settings.php", $settings))
			{
				return true;
			}
			else
			{
				$_SESSION["install_error"]["settings_file"] = "Unable to open or create the Dbsettings.php file in the Config directory.";
				return false;
			}
		}
		else
		{
			$_SESSION["install_error"]["file_error"] = "Unable to locate and load the settings.php file in the install directory. Please ensure it is there.";
		}
	}

/**
 * @param string: Webroot directory info.
 *
 * @throws Nothing explicitly. Error displayed to user if needed. 
 *
 * @return Boolean: True if install is successfull
*/

	public function installHtaccess($path, $dir): bool
	{
		// Prepare the proper install path string.
		$fullPath = $this->preparePath($path, $dir);

		// Set the full path to the template file.
		$file = $fullPath . DIRECTORY_SEPARATOR . "install/htaccess.php";

		// Set the target file name and path.
		$targetfile = $fullPath . DIRECTORY_SEPARATOR . ".htaccess";

		// In case there is a htaccess file already,
		// back it up in the webroot/backup dir.
		if(file_exists($targetfile))
		{
			// Check whether the backup directory
			// exists already or not.
			if(!is_dir($fullPath . DIRECTORY_SEPARATOR . "backup/"))
			{
				// Attempt to create the directory
				// and create the file.
				try
				{
					mkdir($fullPath . DIRECTORY_SEPARATOR . "backup/");
					copy($targetfile, $fullPath . DIRECTORY_SEPARATOR . "backup" . DIRECTORY_SEPARATOR . "htaccess_bak");
				}
			  catch (\Exception $e) 
			  {
			    	$_SESSION["install_error"]["htaccess_error"] = "Unable to create the backup directory or copy existing .htaccess to the directory: " . $e;
			     	return false;
			  }
			}
		}

		// Make sure the template file is there.
		if(file_exists($file))
		{
			// Assign the template file contents to
			// a string variable.
			$htaccessText = include($file);

			// Attempt to create the new .htaccess file.
			if($this->createFile($targetfile, $htaccessText, false))
			{
				return true;
			}
			else
			{
				$_SESSION["install_error"]["htaccess_error"] = "Unable to create the .htacces file in your webroot.";
				return false;
			}
		}
		else
		{
			$_SESSION["install_error"]["htaccess_error"] = "Unable to locate and load the htaccess.php file in the install directory. Please ensure it is there.";
			return false;
		}
	}

/**
 * @param string: Webroot directory info.
 *
 * @throws Nothing explicitly. Error displayed to user if needed. 
 *
 * @return Boolean: True if install is successfull
*/

	public function installFavicon($path, $dir): bool
	{
		// Prepare the required path.
		$fullPath = $this->preparePath($path, $dir);

		// Set the path to the template file.
		$file = $fullPath . DIRECTORY_SEPARATOR . "install/images.php";

		// Set the path to the target file.
		$targetfile = $fullPath . DIRECTORY_SEPARATOR . "favicon.ico";

		// Make sure the tempate file is in the install directory.
		if(!file_exists($targetfile))
		{
			// Assign the template file contents
			// to a string.
			$imageArray = include($file);

			// Decode the image (encoded in base64)
			$faviconText = base64_decode($imageArray["icon"]);

			// Attempt to create the file in the new location.
			if($this->createFile($targetfile, $faviconText, false))
			{
				return true;
			}
			else
			{
				$_SESSION["install_error"]["image_error"] = "Unable to create the favicon.ico file in your webroot.";
				return false;
			}
		}
		else
		{
			$_SESSION["install_error"]["image_error"] = "The favicon install file isn't found. Please replace it in the install directory";
			return false;
		}
	}

//\\//\\//\\//\\//\\//\\//
//       SECTION 5
// Prepare Methods.
//
// Methods that take an input and return a reworked or reformatted
// version of the input.
//\\//\\//\\//\\//\\//\\//


/**
 * @param string: Webroot directory info.
 *
 * @throws Nothing.
 *
 * @return string: Prepared combination of input.
*/

	private function preparePath($path, $dir): string
	{
		// remove any incorrect characters or slashes and combine the strings to one path.
		return $this->removeTrailingSlash(preg_replace("~[^0-9a-zA-Z_\/\\\:]~", "", $this->removeTrailingSlash($path . DIRECTORY_SEPARATOR . $dir)));
	}

/**
 * @param string: dir or web address text.
 *
 * @throws Nothing.
 *
 * @return string: Prepared combination of input.
*/

  public function removeTrailingSlash($text): string
	{
		// Check if the last character of the address
		// is a slash (win or unix)
		if(substr($text, -1) === DIRECTORY_SEPARATOR || substr($text, -1) === "/")
		{
			// If it is, remove it.
			return substr($text, 0, -1);
		}
		else
		{
			// Just return the original
			// text.
			return $text;
		}
	}

/**
 * @param mixed: template text, needle & value pair
 *
 * @throws Nothing.
 *
 * @return string: Prepared combination of input.
*/

	private function prepareConfigFile($text, $replacements): string
	{
		// Set result as default of empty.
		$return_text = "";

		// Check that the text has something,
		// and the needle, value array is not
		// empty.
		if((!empty(trim($text)) && is_array($replacements)) && count($replacements) > 0)
		{
			// Loop through the replacements.
			foreach($replacements as $nullKey => $array_values)
			{ 
				// Replace the key with the value on
				// each needle, value pair while 
				// compounding the result.
				$text = preg_replace($array_values[0], $array_values[1], $text);
			}

			// Overwrite the return variable.
			$return_text = $text;
		}
		return $return_text;
	}


/**
 * @param string: Social username to revamp.
 *
 * @throws Nothing.
 *
 * @return string or null: reformatted username.
*/

	private function prepareSocialUsername(string $username): ?string
	{
		return preg_replace("/[^a-zA-Z0-9-_\.]/","", $username);
	}

/**
 * @param string: any text string.
 *
 * @throws Nothing.
 *
 * @return string: revamped string of only letters and spaces.
*/

	private function textOnly($text): string
	{
		$text = preg_replace("~[^a-zA-Z ]~","", $text);
		if(strlen($text) > 0)
		{
			return $text;
		}
		else
		{
			return false;
		} 
	}

/**
 * @param string: any string.
 *
 * @throws Nothing.
 *
 * @return string: revamped string of only numbers.
*/

	private function numbersOnly($num): int
	{
		$text = preg_replace("~[^0-9]~","", $num);
		if((int)$num > 0)
		{
			return (int)$num;
		}
		else
		{
			return false;
		} 
	}

//\\//\\//\\//\\//\\//\\//
//       SECTION 6
// Support Methods.
//
// Any other method that doesn't fit in the above sections. Mainly conversions,
// error output and non-confirming helper functions.
//\\//\\//\\//\\//\\//\\//

/**
 * @param None directly.
 *
 * @throws Nothing.
 *
 * @return array: array of errors.
*/

	public function getError(): array
	{
		return $_SESSION["install_error"];
	}

/**
 * @param int
 *
 * @throws Nothing.
 *
 * @return Boolean: True if number falls wuthin the min/max.
*/

	private function betweenNums($num, $min, $max): bool
	{
		if($num >= $min && $num <= $max)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

/**
 * @param string: cURL error associative id.
 *
 * @throws Nothing.
 *
 * @return string: text error.
*/

	private function curlError($e): string
	{
		// Curl codes made into an array for more readable errors if needed.
		// Copied from https://curl.se/libcurl/c/libcurl-errors.html

		// Make sure the source file exists.
		if(file_exists($_SERVER["DOCUMENT_ROOT"] . "curl_errors.php"))
		{
			// Assign the file contents to a string variable.
			$curl_errcodes = include($_SERVER["DOCUMENT_ROOT"] . "curl_errors.php");

			// Compare the curl key value to the array
			// of error descriptions in the source file.
			if(array_key_exists($e, $curl_errcodes))
			{
				// If found return it!
				return $curl_errcodes[$e][1];
			}
			else
			{
				return "Unknown Curl Error: An error did occur, but it is not in the list of error codes.";
			}
		}
		else
		{
			return "Unable to locate the curl_errors.php file. Expected in " . $_SERVER["DOCUMENT_ROOT"] . "curl_errors.php. Please make sure it is there.";
		}
	}

/**
 * @param string: file contents, version header.
 *
 * @throws Nothing.
 *
 * @return Boolean: True if file is created.
*/

	public function createFile($file, $info, $configFile = true, $append = false): bool
	{
		// Make sure there is info to write
		if(strlen(trim($info)) > 0)
		{
			if($append)
			{
				$write = fopen($file, "a");
			}
			else
			{
				$write = fopen($file, "w");
			}
			// Make sure the file will open.
			if($write)
			{
				// If the version header info isn't 
				// needed (non-php files like images.
				if(!$configFile)
				{
					if($append)
					{
						// add a line break to appended text.
						$info .= PHP_EOL;
					}
					// just write the original text.
					fwrite($write, $info);
				}
				else
				{
					// Pre-add the version text 
					// and create the file.
					fwrite($write, $this->info . $info);
				}

				// close the file connection.
				fclose($write);

				// All good!
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

/**
 * @param string: image details
 *
 * @throws Nothing.
 *
 * @return string: Image or Null.
*/

	public function getb64_image($name, $height, $ext = "png"): string
	{
		// Assign the images template file 
		// path to a string variable.
		$file = $_SERVER["DOCUMENT_ROOT"] . "images.php";

		// Make sure the tempate file exists.
		if(file_exists($file))
		{
			// Assign the image tempate contents
			// to a variable
			$array = include($file);

			// Output base64 image.
			return "<img src=\"data:image/" . $ext . ";base64, " . $array[$name] . "\" height=\"" . $height . "\" border=\"0\" />";
		}
		else
		{
			return "";
		}
	}

/**
 * @param string: error string
 *
 * @throws Nothing.
 *
 * @return Null.
*/

	public function setError($error): void
	{
		// Set custom error in calling file.
		$_SESSION["install_error"]["globalset_error"] = $error;
	}

/**
 * @param None.
 *
 * @throws Nothing. Returns error to user.
 *
 * @return Boolean: True if file exists.
*/
	private function getInfo(string $path): bool
	{
		if(strlen(trim($path)) === 0) { $path = ""; }
		// Make sure the file is there.
		if(file_exists($path . 'version.php'))
		{
			// Assign the file contents to the string
			// variable.
			$this->info = include($path . 'version.php');
			return true;
		}
		else
		{
			// Return error on failure.
			$this->setError("The version file is missing from the install directory. You will need to replace this file to continue.");
			return false;
		}
	}
}