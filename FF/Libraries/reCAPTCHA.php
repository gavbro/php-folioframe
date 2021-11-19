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

	This class deals entirely with the Google reCAPTCHA (v3)
	API. 

	This needs work to include try/catch and good exceptions.

*/
 
Class reCAPTCHA
{

/**
* @param string: reCAPTCHA required data
*
* @throws Nothing
*
* @return Boolean: True if token is good.
*/

  public function check_Token($secret, $token, $ip): bool
  {
  	// Set an error holder. This isn't used yet.
		$err = "";

		// If the token is set then create the curl data
		// array to send to  google for verification.
		if(null !== $token)
		{
			$data = array(
				'secret' => $secret,
				'response' => $token,
				'remoteip' => $ip
			);

			// Start the connection.
			$verify = curl_init();

			// Set the url to send the data to.
			curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
			curl_setopt($verify, CURLOPT_CUSTOMREQUEST, "POST"); // Send via POST, not GET
			curl_setopt($verify, CURLOPT_ENCODING, ""); // No Encoding
			curl_setopt($verify, CURLOPT_MAXREDIRS, 10); // Only redirect a maximum of 10 times.
			curl_setopt($verify, CURLOPT_TIMEOUT, 30); // Timeout after 30 seconds.
			curl_setopt($verify, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1); // Versioning.

			// Don't cache the results.
			curl_setopt($verify, CURLOPT_HTTPHEADER, array("cache-control: no-cache", "content-type: application/x-www-form-urlencoded"));
			curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data)); // build the query from the array set above.
			curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, true); // Send via verified SSL
			curl_setopt($verify, CURLOPT_RETURNTRANSFER, true); // Request the return information.
			$response = curl_exec($verify); // Execute and get the response. Returns false if failed.
			$err = curl_error($verify); // Intercept any errors and assign to variable.
			curl_close($verify); // Close the connection.

			// Check that there was a response. 
			if($response)
			{
				// Decode the JSON repsonse into an array.
				$final = $this->check_response($response);

				// Did we get the response?
				if($final)
				{
					// Check that Google reported a successful reCAPTCHA 
					// with a score above 0.5 (0-1 with 1 being super strict)
					if($final->success == true && $final->score >= 0.5)
					{
						// User is not a robot.
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
* @param string: reCAPTCHA response JSON
*
* @throws Nothing
*
* @return array|bool: array if json is decoded, false if not.
*/

  private function check_response($response)//: array|bool -- Can't use this until PHP 8.0
  {
  	$jsonInfo = json_decode($response);
  	if(IS_NULL($jsonInfo))
  	{
  		return false;
  	}
  	else
  	{
  		return $jsonInfo;
  	}
	}
}
?>