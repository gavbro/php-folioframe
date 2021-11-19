<?php

// Define the namespace
Namespace Input;

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
	This namespace/class attempts to automate some form
	display. Although not all are required. The methods
	dealing with the CSRF should not be ignored. 

	This area could do with some more attention to better
	streamline things for sure.
*/

Class Form
{

	// String variable to hold output
	private $tag_form;

/**
* @param string: All settings required to begin a secure form.
*
* @throws None.
* 
* @return Nothing: Sets strings for later output..
*/

	public function secure_start($name, $action = "", $id="", $spacing = "", $charset = "utf8", $enctype = "", $method = "POST", $target = "", $autocomplete = ""): void
	{

		// behin with the form tag and name.
		$this->tag_form .= "<form name=\"" . $name . "\" ";

		// If an id was specified, append it to the 
		// form tag
		if(strlen($id) > 0)
		{
			$this->tag_form .= "id=\"" . $id . "\" ";
		}

		// If an action was specified, append  
		// it to the form tag
		if(strlen($action) > 0)
		{
			$this->tag_form .= "action=\"" . $action . "\" ";
		}

		// Set the character set (Default is utf8)
		$this->tag_form .= "charset=\"" . $charset . "\" ";

		// Set the enctype if it was specified.
		if(strlen($enctype) > 0)
		{
			$this->tag_form .= "enctype=\"" . $enctype . "\" ";
		}

		// Method is required. (Default is POST)
		$this->tag_form .= "method=\"" . $method . "\" ";

		// If a target was specified, append it as well.
		if(strlen($target) > 3)
		{
			$this->tag_form .= "target=\"" . $target . "\" ";
		}

		// on or off. Whether or not the browser should 
		// remember the field values for next time.
		if(strlen($autocomplete) > 0)
		{
			$this->tag_form .= "autocomplete=\"" . $autocomplete . "\" ";
		}

		// End the opening form tag.
		$this->tag_form .= "/>\n";
		
		// Add hidden CSRF Tag to the Form
		$this->tag_form .= $this->setCSRF();
		
		// Add HoneyPot Input to the Form
		$this->tag_form .= $this->genHoneyPot();
	}

/**
* @param None. 
*
* @throws None.
* 
* @return String: Returns the entire form with or without reCAPTCHA.
*/

	public function display(): string
	{
		// Check to see if reCAPTCHA is setup.
		if(GRC === true)
		{
			// Append GRC to the form before closing and returning.
			return $this->tag_form . $this->genREcaptcha() . "\n</form>\n";
		}
		else
		{
			// Return the form.
			return $this->tag_form . "\n</form>\n";
		}
	}
	
/**
* @param None. 
*
* @throws None.
* 
* @return String: Sets the CSRF session variable and returns the hidden field.
*/

  private function setCSRF(): string
  {
  	// Generate the CSRF hash based on random bytes.
  	$csrf = bin2hex(openssl_random_pseudo_bytes(32));

  	// Assign it to the session variable.
  	$_SESSION["CSRF"] = $csrf;

  	// Return the hidden input CSRF field.
  	return SA . "<input type=\"hidden\" name=\"frsc\" value=\"" . $csrf . "\" />\n";
  }
	
/**
* @param None. 
*
* @throws None.
* 
* @return String: Generates and returns the honeypot form input.
*/

	private function genHoneyPot(): string
	{
		// Temporarily load the main controller
		// to use to access the language array.
		$mc = new \Controller\MC();

		// Return the honeypot input text field.
		return SA . "<div id=\"required\"><label for=\"website\">" . $mc->showLang("Form","message_0") . "</label><input type=\"text\" name=\"website\" value=\"\" size=\"20\" /></div>\n";
	}
	
/**
* @param None. 
*
* @throws None.
* 
* @return Nothing: Appends the form input to the form text when called.
*/

	public function fieldEmail($name, $display, $title = "", $class = "fieldEmail"): void
	{
		$this->tag_form .= $this->genLabel($name, $display) . SA . "<input type=\"email\" name=\"" . $name . "\" class=\"" . $class . "\" title=\"" . $title . "\" placeholder=\"" . $title . "\" />\n";
	}
	
/**
* @param string: names, titles and settings for the code field. 
*
* @throws None.
* 
* @return Nothing: Appends the form input to the form text when called.
*/

	public function fieldCode($name, $display, $title = "", $code, $class = "fieldCode", $idprefix="fieldCode"): void
	{
		// Begin with the divs to match the css markup.
		$this->tag_form .= "<div class=\"codeBox\">\n";

		// Generate the same amount of fields as the 
		// number of fields setup in the install settings (Default: 6)
		for($i=1;$i<=CN;$i++)
		{
			//Add the generic input code that doesn't change.
			$this->tag_form .= SA . "<input class=\"" . $class . "\" type=\"number\" size=\"3\" maxlength=\"1\" ";

			// If the code array was set
			if(!empty($code))
			{
				// Repopulate each field with the 
				// corresponding code number.
				$this->tag_form .= "value=\"" . $code[$i-1] . "\" ";
				$this->tag_form .= "name=\"" . $name . "_" . $i . "\" id=\"" . $idprefix . "_" . $i . "\" min=\"0\" max=\"9\" ";
			}
			else
			{
				// Create the fields without values.
				$this->tag_form .= "name=\"" . $name . "_" . $i . "\" id=\"" . $idprefix . "_" . $i . "\" min=\"0\" max=\"9\" ";

				// Each field after the first should be disabled by default.
				if(($i > 1 && $i<=CN))
				{
					$this->tag_form .= "DISABLED ";
				}
			}

			// End each code input tag.
			$this->tag_form .= "/>\n";
			
		}

		// Set a hidden field with the total number of code
		// fields generated. This is to make some javascript
		// work a little easier.
		$this->tag_form .= SA . "<input type=\"hidden\" id=\"codeLen\" name=\"codeLen\" value=\"" . ($i-1) . "\"/>\n";

		// Generate the submit button.
		$this->tag_form .= $this->genSubmit("codeSubmit", $title);

		// End css markup div.
		$this->tag_form .= "\n</div>";
	}
	
/**
* @param string: hash value generated by stored procedure.
*
* @throws None.
* 
* @return Nothing: Appends the hidden field to form string.
*/

	public function showHash($hash): void
	{
		$this->tag_form .= SA . "<input type=\"hidden\" id=\"hash\" name=\"hash\" value=\"" . $hash . "\" />\n";
	}
	
/**
* @param string: submit button name and display value..
*
* @throws None.
* 
* @return Nothing: Appends the hidden field to form string.
*/

	public function genSubmit($name, $display): void
	{
		$this->tag_form .= SA . "<input type=\"submit\" class=\"" . $name . "\" id=\"" . $name . "\" name=\"" . $name . "\" value=\"" . $display . "\" />";
	}
	
/**
* @param string: name and display value of label..
*
* @throws None.
* 
* @return string: Returns the pre-formatted label.
*/

	private function genLabel($name, $display): string
	{
		return SA . "<label for=\"" . $name . "\" class=\"label_" . $name . "\">" . $display . "</label>\n";
	}
	
/**
* @param None.
*
* @throws None.
* 
* @return string: Returns the pre-formatted Google reCAPTCHA hidden input.
*/

	public function genREcaptcha(): string
	{
		return "\n  <input type=\"hidden\" name=\"g-recaptcha-response\" value=\"\" id=\"g-recaptcha-response\">\n";	
	}
}	