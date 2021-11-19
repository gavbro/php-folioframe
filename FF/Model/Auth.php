<?php

// Define the namespace
Namespace Security;

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

// Load in the Main Controller to help with
// some of the language and error loading.
use \Controller\MC as MC;

/*

	Accepting email/code login information and handling everything 
	required from user entry to landing at the secure page.

*/

Class Auth 
{
	private $bl; // Email blacklist array. false if not set.
	private $mc; // Main Controller instance holder
	private $db; // Database class instance holder
	private $dbs; // Database connection instance holder
	private $sec; // Security class instance holder
	
	public function __construct()
	{
		// If the user is already loggedin, then what are 
		// we doing here. Stop script and redirect back
		// home.
		if($this->checkLoggedIn())
		{
			header("Location: " . TLD . $_SESSION["LN"] . "/");
			exit;
		}

		// Assign the global timeout user setting.
		// to a class variable.
		$this->timeout = EC;

		// Get the email domain blacklist array.
		$this->bl = $this->isBl();   

		// Setup the connections and throw an exception on failure.                                                                                                             
		try
		{
			$this->mc = new MC();
			$this->mc->loadModel("Database");
			$this->db = new \Database\Db("Entry");
			$this->dbs = $this->db->Set();
			$this->mc->loadModel("Secure");
			$this->sec = new \Security\Secure(); 
		}
		catch( \PDOException $e ) 
		{
			// A failure to connect to the db is
			// a deal breaker. Stop the script and
			// send the error.
			throw new \Exception('Auth::DB CONNECTION FAILURE: ' . $e->getMessage());
			exit;
		}
	}

/**
* @param string: users email and security tokens.
*
* @throws None.
*
* @return None. Errors are sent back to the user or handled by the other methods..
*/

	public function processEmail($mail, $hp, $grc, $csrf): void
	{
		// Make sure the form security checks pass
		if($this->formSecure($hp, $grc, $csrf))
		{
			// Verifty that the email is valid.
			if($this->checkEmail($mail))
			{
				// Update the database and send the
				// code email.
				$this->sendEmail($this->initEmail($mail));
			}
			else
			{
				// Display an error to the user saying
				// that the email is invalid.
				$this->addError("Error","message_3");
				$this->doError();
			}		
		}
		else
		{
			// The error text would have been set in the
			// formSecure class. Go ahead and display it.
			$this->doError();
		}
	}

/**
* @param string: code string and security tokens.
*
* @throws None.
*
* @return None. Handled by other methods.
*/

	public function processCode($code, $hash, $hp, $grc, $csrf): void
	{
		// Make sure the form security checks pass
		if($this->formSecure($hp, $grc, $csrf))
		{
			// Check the code vs hash in the db.
			// and log the user in on success
			$this->validateCode($code, $hash, EC, TR);
		}
		else
		{
			// The form is not secure, so destroy the
			// session just in case and show the error
			// from formSecure.
			$this->Logout();
			$this->doError();
		}
	}

/**
* @param string: email address.
*
* @throws \Exception: Auth::DB CONNECT ERROR.
*
* @return array|null: Array of success or exit.
*/

	private function initEmail($email)//: array|null Can't use this until PHP 8.0
	{
		try 
		{
			// Get the entry database key for encrypting the user data
			$sec = $this->sec->encryptData($email, $this->db->getKey());

			// Get the maximum code length.
			$maxCode = $this->getMaxCode();

			// Call the stored procedure to:
			//	- Check the email address isn't already there.
			//  - Register it if it isn't
			//  - Generate a code either way.
			$statement = $this->dbs->prepare("CALL auth_email(:em, :sec, :mins, :maxc, @hash, @code, @emOK)");
			$statement->bindParam(":em", $email, \PDO::PARAM_STR, 255); // User entered email
			$statement->bindParam(":sec", $sec, \PDO::PARAM_STR, 255); // Encrypted email
			$statement->bindParam(":mins", $this->timeout, \PDO::PARAM_INT); // Code timeout (set in /FF/Config/Settings.php)
			$statement->bindParam(":maxc", $maxCode, \PDO::PARAM_INT); // Code length to be generated  (set in /FF/Config/Settings.php)
			$statement->execute();
			// Get the result as an associative array
			$result = $this->dbs->query("SELECT @hash, @code, @emOK")->fetch(\PDO::FETCH_ASSOC);
			unset($statement);

			// Return the result as a new array
			return array($result["@emOK"], $email, $result["@hash"], $result["@code"]);
		}
		catch( \PDOException $e ) 
		{
			 throw new \Exception('Auth::DB CONNECT ERROR: ' . $e->getMessage());
			 exit;
		}
	}
 
/**
* @param array: result of class initEmail().
*
* @throws \Exception: Auth::DB PROCESSING ERROR.
*
* @return void: Error or redirect.
*/

	private function sendEmail($initReturn): void
	{
	  	try
		{	
			// First see if the stored procedure reported success
			if($initReturn[0] === 1)
			{
				// Get the email address
				$to = $initReturn[1];
				
				// Setup the basic email html including language
				// variables.		         
				$message = "<html><head><title>" . $this->mc->showLang("Email", "title_0") . "</title></head><body>";
				$message .= "<table width=100%><tr height=75><td align=center>";
				$message .= "<p><font face=verdana size=20 color=black>" . $this->mc->showLang("Email", "title_0") . "</font></p>";
				$message .= "</td></tr><tr height=300><td align=center>";
				$message .= "<p><font face=ariel size=50 color=blue>" . $this->mc->showLang("Email", "body_0") . " " . $initReturn[3] . "</font><font face=garamond size=3 color=black><br>" . $this->mc->showLang("Email", "body_1"). "</font></p>";
				$message .= "</td></tr><tr height=100><td align=center>";
				$message .= "<p><font face=ariel size=3 color=black>" . $this->mc->showLang("Email", "body_2"). "<a href=" . TLD . $_SESSION["LN"] . "/Code/" . $initReturn[2] . "&code=" . base64_encode($initReturn[3]) . ">" . $this->mc->showLang("Email", "body_3"). "</a></font></p>";
				$message .= "</td></tr><tr height=50><td align=center valign=bottom><font face=garamond size=2 color=grey>";
				$message .= $this->mc->showLang("Email", "disclaim_0") . " " . $this->mc->showLang("Email", "disclaim_1"). " " . $this->mc->showLang("Email", "disclaim_2");
				$message .= "</font></td></tr></table></body></html>";

				// Default the reply email to noreply@yourdomain.tld.
				// this can be changed to whatever you want really.
				$header = "From:noreply@" . NM . " \r\n";
				$header .= "MIME-Version: 1.0\r\n";
				$header .= "Content-type: text/html\r\n";

				// Attempt to send the email.
				$retval = mail($to,$this->mc->showLang("Email", "title_0"),$message,$header);

				// If the email sent, send the user to the code entry 
				// page and include the code hash to be verified there.
				if($retval == true) 
				{
					header("Location: " . TLD . $_SESSION["LN"] . "/Code/" . $initReturn[2]);
					exit;
				}
				else
				{
					// Return a regular error to the user.
					// from the language file (\FF\Lang\en.php by Default)
					$this->addError("Error","email_0");
					$this->doError();
				}
			}
			else
			{
				// Return a regular error to the user.
				// from the language file (\FF\Lang\en.php by Default)
				$this->addError("Error","email_1");
				$this->doError();
			}
		}
		catch( \Exception $e ) 
		{
			// The DB query failed. Output why and stop the script.
			throw new \Exception('Auth::DB PROCESSING ERROR: ' . $e->getMessage());
			exit;
		}
	}
 
/**
* @param string: the three security form types (HoneyPot, Google reCAPTCHA, CSRF).
*
* @throws Nothing.
*
* @return Boolean: All pass or false.
*/

	private function formSecure($hp, $grc, $csrf): bool
	{
		// Set the Boolean results to temp
		// variables to avoid having to run
		// the methods more than once.
		$hpRes = $this->checkHoneyPot($hp);
		$grcRes = $this->checkGRC($grc);
		$csrfRes = $this->checkCSRF($csrf);

		// Do a check to see if they all pass.
		if($hpRes && $grcRes && $csrfRes)
		{
			return true;
		}
		else
		{
			// At least one failed. Check which one(s)
			// and return an error to the user.
			// Error messages from current language file.
			// (\FF\Lang\en.php by Default)
		  	if(!$hpRes)
		  	{
		  		$this->addError("Error","message_0");
		  	} 
		  	
		  	if(!$grcRes)
		  	{
		  		$this->addError("Error","message_4");
		  	}
		  	
		  	if(!$csrfRes)
		  	{
		  		$this->addError("Error","system_0");
		  	}

		  	// Either way, return false.
		  	// If one fails, the check fails
		  	return false;
		}
	}
 
/**
* @param string: User entered email.
*
* @throws Nothing.
*
* @return Boolean: Pass or fail.
*/

	private function checkEmail($email): bool
	{
	  	// Check the address structure, MX records and Domain all at once.
	  	if(filter_var($email, FILTER_VALIDATE_EMAIL) && $this->checkMX($email) && $this->checkDomain($email))
	  	{
	  		return true;
	  	}
	  	else
	  	{
	  		return false;
	  	}
	}
 
/**
* @param string: Entered code, code hash and settings.
*
* @throws \Exception: Auth::DB CONNECT ERROR.
*
* @return Void: Nothing returned, but errors shown.
*/

	private function validateCode($code, $hash, $timeout, $maxtries): void
	{
			try 
		{
			// Call the stored procedure to handle checking the code credentials.
			$statement = $this->dbs->prepare("CALL auth_codeCheck(:inhash, :incode, :setmins, :maxtries, @codeOK, @Locked, @Stage, @uID, @Access)");
			$statement->bindParam(":inhash",  $hash, \PDO::PARAM_STR, 255); // The random hash passed through the email.
			$statement->bindParam(":incode", $code, \PDO::PARAM_INT); // The user entered code combination.
			$statement->bindParam(":setmins", $timeout, \PDO::PARAM_INT); // The code timeout in minutes.
			$statement->bindParam(":maxtries", $maxtries, \PDO::PARAM_INT); // The maximum code tries before the code is disabled.
			$statement->execute();

			// Grab the returned value from the stored procedure into an associative array.
			$oc = $this->dbs->query("SELECT @codeOK, @Locked, @Stage, @uID, @Access")->fetch(\PDO::FETCH_ASSOC);

			// Was the code OK?
			if($oc["@codeOK"] === 1)
			{
				// The user is logged in. Set the session.
				$_SESSION["logged_in"] = true;
				$_SESSION["authStage"] = $oc["@Stage"]; // The numeric stage the login is at.
				$_SESSION["authID"] = $oc["@uID"];
				$_SESSION["authAccess"] = $oc["@Access"];

				//Set the rest of the $_SESSION variables.
				$this->setUserDetails($oc["@uID"]);

				// Redirect back to home.
				header("Location: " . TLD . $_SESSION["LN"] . "/");
				exit;
			}
			else
			{
				// Was this the last try?
				if($oc["@Locked"] === 1)
				{
					// It was, send an error to say that.
					$this->addError("Error", "code_2");
					$this->doError();
				}
				else
				{
					// Go back and try to enter the code again.
					header("Location: " . TLD . $_SESSION["LN"] . "/Code/" . $hash);
					exit;
				}
			}
		}
		catch( \PDOException $e ) 
		{
			// The database failed to connect. Report why.
			throw new \Exception('Auth::DB CONNECT ERROR: ' . $e->getMessage());
			exit;
		}
	}
	
/**
* @param string: hash to be verified.
*
* @throws \Exception: Auth::DB CONNECT ERROR.
*
* @return Boolean: True if hash matches.
*/

	public function validateHash($hash): bool
	{
		try 
		{
			// Call the stored procedure to check the hash.
			$statement = $this->dbs->prepare("CALL auth_hashCheck(:inhash, :setmins, @isHash)");
			$statement->bindParam(":inhash",  $hash, \PDO::PARAM_STR, 255); // hash string
			$statement->bindParam(":setmins", $this->timeout, \PDO::PARAM_INT); // how old is too old?
			$statement->execute();

			// Grab the result
			$oc = $this->dbs->query("SELECT @isHash")->fetch(\PDO::FETCH_ASSOC);

			// Was the hash good?
			if($oc["@isHash"] === 1)
			{
				return true;
			}
			else
			{
				return false;
			}
		}	
		catch( \PDOException $e ) 
		{
			throw new \Exception('Auth::DB CONNECT ERROR: ' . $e->getMessage());
			exit;
		}
	}
	
/**
* @param string: HoneyPot form input value.
*
* @throws Nothing.
*
* @return Boolean: True if HoneyPot was empty.
*/

	private function checkHoneyPot($hp): bool
	{
		// Was there anything entered?
		// If there was, possible bot entry.
		if(strlen($hp) > 0 || $hp !== "")
		{
			return false;
		}
		else
		{
			return true;
		}
	}

/**
* @param string: Google reCAPTCHA (v3) response code.
*
* @throws Nothing.
*
* @return Boolean: True if Google passes.
*/

	private function checkGRC($grc): bool
	{
		// Load and invoke the reCAPTCHA class
		$this->mc->loadLibrary("reCAPTCHA");
		$rec = new reCAPTCHA();

		// First check if Google reCAPTCHA is disabled in the settings.
		// If it is, just pass. If it is enabled, run the response code
		// and the other required info through the Googe API.
		if((GRC === false) || (GRC === true && $rec->check_Token(GPSECKEY, $grc, $_SERVER['REMOTE_ADDR']) === true))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

/**
* @param string: CSRF value passed by the form submission.
*
* @throws Nothing.
*
* @return Boolean: True if matches the session equivelant.
*/

	private function checkCSRF($csrf)
	{
		// Make sure both the session and passed parameter
		// CSRF values exists, then compare them.
		// if they are a match we are good to go.
		if((isset($csrf) && isset($_SESSION['CSRF'])) && (trim($csrf) === trim($_SESSION['CSRF'])))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
/**
* @param Null.
* 
* @throws Nothing.
*
* @return Boolean: True if logged in.
*
*/

	private function checkLoggedIn()
	{	
		// Check the loggedin session
		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

/**
* @param Null.
* 
* @throws Nothing.
*
* @return int: timeout minutes.
*
*/

	private function getMaxCode()
	{
		// Make sure the number of security code digits
		// set in the settings file is within range.
		// if not default to 6
		if((CN > 3) && (CN < 9))
		{
			return CN;
		}
		else
		{
			return 6;
		}
	}

/**
* @param String: User hash ID.
* 
* @throws \Exception: DB Connect Error.
*
* @return Void: Sets the session variables.
*
*/

	private function setUserDetails(string $code): void
	{
		try 
		{
			// Call the stored procedure to grab the user details.
			$stmt = $this->dbs->prepare("CALL auth_getUserDetails(:code, @email, @name, @linked, @fbook, @twit, @git, @reddit, @insta);");
			$stmt->bindParam(":code",  $code, \PDO::PARAM_STR, 255); // hash string
			$stmt->execute();

			// Grab the result
			$details = $this->dbs->query("SELECT @email, @name, @linked, @fbook, @twit, @git, @reddit, @insta;")->fetch(\PDO::FETCH_ASSOC);

			// Check each detail and see if it is set.
			foreach($details as $key => $detail)
			{
				// If it is, decrypt it and set it to a session variable.
				// If not, set the session to a blank string.
				if(null !== $detail && strlen(trim($detail)) > 0)
				{
					$_SESSION[substr($key, 1)] = $this->sec->decryptData($detail, $this->db->getKey());
				}
				else
				{
					$_SESSION[substr($key, 1)] = "";
				}
			}
		}	
		catch( \PDOException $e ) 
		{
			throw new \Exception('Auth::DB CONNECT ERROR: ' . $e->getMessage());
			exit;
		}
	}

/**
* @param Null.
* 
* @throws Nothing.
*
* @return Nothing: redirect to 404 page.
*
*/

	public function do404(): void
	{
		header("Location: " . TLD . $_SESSION["LN"] . "/404/");
		exit;
	}

/**
* @param string: reference category and key for language array.
* 
* @throws Nothing.
*
* @return Bool: Add text to error session, return true.
*
*/

	public function addError($err, $no): bool
	{
		$errtxt = $this->mc->showLang($err, $no);
		$_SESSION["Err"] .= "<li>" . $errtxt . "</li>\n";	
		unset($errtxt);
		return true;
	}

/**
* @param None. 
* 
* @throws Nothing.
*
* @return Nothing. Just redirect to error page.
*
*/

	public function doError()
	{
		header("Location: " . TLD . $_SESSION["LN"] . "/err/");
		exit;
	}

/**
* @param None. 
* 
* @throws Nothing.
*
* @return Nothing. top level domain.
*
*/

	private function doTLD()
	{
		header("Location: " . TLD);
		exit;
	}

/**
* @param string: Email address to check MX for. 
* 
* @throws Nothing.
*
* @return Boolean. True if MX records exist.
*
*/

	private function checkMX($email): bool
	{
		// Break apart the email prefix and suffix into
		// strings.
		list($user, $domain) = explode("@", $email);

		// Get the dns MX record for the domain.
		// as an array.
		$arr = dns_get_record($domain, DNS_MX);

		// Check the array for matching host and value
		// as the target
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
* @param string: Email address to check 
* 
* @throws \Exception: failed to load file.
*
* @return Boolean. True if MX records exist.
*
*/

	private function checkDomain($email): bool
	{
		// break the email address prefix/suffix
		/// apart and into an array.
		$domarr = explode("@", $email);

		// Get the domain.
		$domain = $domarr[1];

		// Import the list of banned domains
		// (10 minute emails and the like)
		if($domlist = $this->isBl())
		{	
			// Make sure the list populated
			if(is_array($domlist) && $domlist !== false)
			{
				if(!in_array($domain, $domlist))
				{
					return true;
				}
				else
				{
					return false;
				}
			}
			else
			{
				throw new \Exception('Auth::checkDomain() - Domain List Failed to Load. ');
				return false;
			}
		}
		else
		{
			throw new \Exception('Auth::checkDomain() - Domain List Failed to Set. ');
			return false;
		}
	}

/**
* @param Nothing.
* 
* @throws \Exception: File error.
*
* @return Array|Boolean. Array of domains if true.
*
*/

	private function isBl()//: array|bool Can't enforce this until PHP 8.0
	{
		$file = R . ".mail_blacklist";
		if(file_exists($file))
		{
			$rawarr = array();
			
			//Get all listed domains.
			$rawarr = explode("\n", file_get_contents($file));
			if(is_array($rawarr) && !empty($rawarr))
			{
				//Remove any annoying spaces and return the array.
				return array_map('trim', $rawarr);
			}
			else
			{

				throw new \Exception("File Error: Blacklist file not formed correctly.");
				return false;
			}
		}
		else
		{
			throw new \Exception("File Error: Blacklist file not found");
			return false;	
		}
	}

/**
* @param Nothing: 
* 
* @throws Nothing.
*
* @return Nothing. Runs the logout method.
*
*/

	public function Logout(): void
	{
		// Set user logged out
		// and reset the session.
		$this->mc->Logout();
	}
}

