<?php

// Define the namespace
Namespace Social;

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
	Class to handle any processing required for social media accounts.
	It currently verifies social media account links to make sure
	there is a user on the social media site.

	Note: Facebook and Linkedin are not currently verified. I am still
	hoping to find a reliable way to check usernames for those services
	without having to get an API key.

	Note2: instagram and others to come soon hopefully.
*/

Class User
{

/**
* @param string: twitter handle.
*
* @throws None.
*
* @return Boolean. True if the user is found.
*/

	public function twitter($username): bool
	{
		// Set the full url to the public user
		// profile
		$url = "https://twitter.com/" . $username;

		// Check if it exists
		return $this->checkUser($url);
	}

/**
* @param string: facebook profile name.
*
* @throws None.
*
* @return Boolean. True if the user is found.
*/

	public function facebook($username): bool
	{
		// Haven't found a good way to do this without the API yet.

		//$url = "https://www.facebook.com/" . $username;
		//return $this->checkUser($url);

		// assume the entered account is correct
		return true;
	}

/**
* @param string: github profile name.
*
* @throws None.
*
* @return Boolean. True if the user is found.
*/

	public function github($username): bool
	{
		// Set the full url to the public user
		// profile
		$url = "https://github.com/" . $username;

		// Check to see if it exists
		return $this->checkUser($url);
	}

/**
* @param string: Linkedin profile name.
*
* @throws None.
*
* @return Boolean. True if the user is found.
*/

	public function linkedin($username): bool
	{
		// Haven't found a good way to do this without the API yet.

		//$url = "https://www.linkedin.com/in/" . $username;
		//return $this->checkUser($url)

		// assume the entered account is correct
		return true;
	}

/**
* @param string: reddit profile name.
*
* @throws None.
*
* @return Boolean. True if the user is found.
*/

	public function reddit($username): bool
	{
		// Set the url to the user profile.
		$url = "https://old.reddit.com/user/" . $username;

		// Check to see if it exists.
		return $this->checkUser($url);
	}

/**
* @param string: instagram profile name.
*
* @throws None.
*
* @return Boolean. True if the user is found.
*/

	public function instagram($username): bool
	{
		// Haven't found a good way to do this without the API yet.

		//$url = "https://www.instagram.com/" . $username;
		//return $this->checkUser($url);

		// assume the entered account is correct
		return true;
	}


/**
* @param string: URL to check for 200 response.
*
* @throws None.
*
* @return Boolean. True if the user is found.
*/

	private function checkUser(string $CheckUrl): bool
	{

		$verify = curl_init(); // Begin a curl session.
		curl_setopt($verify, CURLOPT_URL, $CheckUrl); // Set the url for curl to target
		curl_setopt($verify, CURLOPT_MAXREDIRS, 10); // Only allow up to 10 x 301 redirects
		curl_setopt($verify, CURLOPT_HEADER, true); // Pull out the header of the response
 		curl_setopt($verify, CURLOPT_NOBODY, true); // Don't bother with the body.
		curl_setopt($verify, CURLOPT_TIMEOUT, 30); // Set the max timeout to 30 seconds
		curl_setopt($verify, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1); // Versioning

		// Do not cache the results.
		curl_setopt($verify, CURLOPT_HTTPHEADER, array("cache-control: no-cache", "content-type: application/x-www-form-urlencoded"));
		curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, true); // Make sure to use SSL
		curl_setopt($verify, CURLOPT_RETURNTRANSFER, true); // We want to get the response from the server
		curl_exec($verify); // Execute
		$data = curl_getinfo($verify); // Assign the server response to an array.
		curl_close($verify); // Close the connnection. We should have what we need at this point.

		// If the server returns a valid response code
		// of 200, the page exists!
		if($data["http_code"] === 200)
		{
	    	return true;
		}
		else
		{
			return false;
		}
	}
}