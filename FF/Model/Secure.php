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

/*

		Generates, encrypts and decrypts data. Also any other
		functions required to aid other parts of the project be more 
		secure.

 */
 
Class Secure 
{

	private $cipher; // The type of cipher to use for encrypt/decrypt
	private $hashal; // The type of hash algo to use.
	private $ivlen; // holds the length of the cipher
	private $iv; // randomly generated string sent with the encrypted data.

	// Set the basic required strings to be used throughout
	// the class when instanced.
	public function __construct()
	{
		$this->cipher = "AES-256-CTR"; // Default is AES-256-CTR 
		$this->hashal = "sha256"; // SHA256 is best, change this and also change it in the stored procedures.
		$this->ivlen = openssl_cipher_iv_length($this->cipher); // Get the cipher length
	}
	
/**
* @param array: array to check if it has more than one dimension.
*
* @throws None.
*
* @return Boolean: 
*/

	private function is_multi_array($a): bool
	{
    foreach ($a as $v)
    {
        if (is_array($v)) return true;
    }
    return false;
	}

/**
* @param string: data to be encrypted and the key.
*
* @throws Custom \Exception: Secure::encryptData.
*
* @return String|Boolean: Encrypted string or false.
*/

	public function encryptData($data, $key)//: string|bool - Cant use until PHP 8.0
	{
		// Decode the key for user
		$key = base64_decode($key);

		// Generate the random IV value.
		$iv = openssl_random_pseudo_bytes($this->ivlen);

		// Make sure there is data to encrpypt
		if(strlen($data) === 0 || null === $data)
		{
			throw new \Exception("Secure::encryptData() - Data length: " . strlen($data) . " is less than reference key length {$this->ivlen}");
      return false;
  	}
  	else
  	{
  		// Attempt to performm the encryption.
    	$enc = openssl_encrypt($data, $this->cipher, $key, 0, $iv);

    	// It failed, throw an exception.
    	if ($enc === false)
      {
        throw new \Exception('Secure::encryptData() - Encryption failed: ' . openssl_error_string());
        return false;
      }
      else
      {
      	// Append the IV value to the encrypted string beginning
      	// and convert to Hex for easier storage.
      	$res = bin2hex($iv . $enc);

      	// Return the completed encryption.
      	return $res;
      }
  	}
  }

/**
* @param string: data to be encrypted and the key.
*
* @throws None.
*
* @return String: Decrypted string or empty.
*/

  public function decryptData($sectext, $key): string
  {
  	// Convert the encrypted string back from HEX.
		$sectext = pack('H*', $sectext);

		// Get the text only by cutting out the IV prefix.
		$text = substr($sectext, $this->ivlen);

		// Get the prefix IV value from the front of the encryption.
		$iv = substr($sectext, 0, $this->ivlen);

		// Decode the Db key that is set on install in \FF\Config\Dbsettings.php
		$key = base64_decode($key);

		// Make sure the text is not empty.
		if(strlen($text) === 0)
		{
			return "";
  	}
	  else
	  {
	  	// Text is not empty, so attempt to decrypt.
	  	$res = openssl_decrypt($text, $this->cipher, $key, 0, $iv); //OPENSSL_RAW_DATA
    	if ($res === false)
      {
      		return "";
      }
      else
      {
      	// Decryption complete, return the decrypted text.
      	return $res;	
      }
	  }
	}

/**
* @param string: the email to be obfuscated
*
* @throws None.
*
* @return String: the obfuscated email address.
*/

	//obfuscate email to me****@ds****.com
	//Not yet implemented.
	public function ob_Email($email): string
	{
		if(filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			// Split the email to get the pre@ and post@ values.
			$em = explode("@", $email);

			// Split the post@ by the dot to get the tld (hotmail, outlook, gmail, etc.)
			$prext = explode(".", $em[1]);

			// Split the post@ value by the dot to get the domain type (.com, .net, etc..)
			$ext = $prext[count($prext)-1];

			// Return the original email back with everything but the following as *
			//	- everything after the first 2 characters in the pre@.
			//	- everything after the first 2 characters in the post@.
			//  - the full domain type.
			
			// Final result of myself@example.com would be my****@ex*****.com
			return substr($em[0], 0, 2) . str_repeat('*', strlen($em[0])-2) . "@" . substr($prext[0], 0, 2) . str_repeat('*', strlen($prext[0])-2) . "." . $ext; 
		}
		else
		{
			// return a randomly genrated email placeholder (Example: ****@*****.***)
			return str_repeat('*', rand(4,8)) .  "@" . str_repeat('*', rand(5,12)) . "." . str_repeat('*', rand(2,5));
		}
	}
}