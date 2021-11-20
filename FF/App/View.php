<?php

// Declare the namespace
Namespace Display;

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
	This namespace/class includes methods required
	to load view pages and their supporting markup.
*/

// Bring in the lang module
use \User\Lang as Lang;


class View 
{
	
	// Header markup variables
	private $title;
	private $desc;
	private $incTags;
	private $begin;
	private $meta;
	private $csp;
	private $canon;
	
	// Copyright tag
	private $cp;
	
	// OG Holder
	private $OG;
	
	// language object variable.
	private $lang;
	
	public function __construct()
	{
		// Set language object to a variable.
		$this->lang = new Lang();
	}

/**
* @param mixed: HTML header info
*
* @throws None.
*
* @return None. Sets initial class variables.
*/

	public function setup(string $title, ?string $desc = "", bool $gcp = false, ?string $csp = "", array $js = array(), array $css = array(), array $fonts = array()): void
	{
		$this->title = $title; // String
		$this->desc = $desc; // String
		
		// Tag Processing
		if(strlen($csp) > 0)
		{
			$this->genCSP($csp);
		}
		$this->genHtag($fonts, "FONT");
		$this->genHtag($css, "CSS");
		$this->genHtag($js, "JS");
		
		// Generate the reCAPTCHA SCRIPT Tag if the page view asks for it.
		if($gcp)
		{
			$this->genRecap();
		}
		
		// Dynamically load any fonts, css or js by single var or an array of vars.
		$this->begin = "<!DOCTYPE html>\n<html>\n" . $this->indent(1) . "<head>\n";
		$this->begin .= $this->indent(2) . "<title>" . $this->title . "</title>\n";
		$this->meta = $this->indent(2) . "<meta charset=\"utf-8\">\n";
		$this->meta .= $this->indent(2) . "<meta name=\"description\" content=\"" . $this->desc . "\">\n";
		$this->meta .= $this->indent(2) . "<meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\n";
		$this->meta .= $this->indent(2) . "<meta name=\"referrer\" content=\"no-referrer\">\n";
		
		//Set canonical reference.
		$this->canon = $this->indent(2) . "<link rel=\"canonical\" href=\"" . TLD . "\" />\n";
		
		//Set up copyright tag.
		$this->cp = "<p class=\"cp\">Copyright &copy; " . date("Y") . " - " . COPY .  "</p>\n";
	}

/**
* @param Boolean: Load the public or logged in headers.
*
* @throws None.
*
* @return Null. prints to the page directly.
*/

	public function head(bool $sec = false): void
	{
		if($sec)
		{
			echo $this->begin;
			echo $this->csp;
			echo $this->favico();
			echo $this->incTags;
		}
		else
		{
			echo $this->begin;
			echo $this->meta;
			echo $this->csp;
			echo $this->OG;
			echo $this->canon;
			echo $this->favico();
			echo $this->incTags;
		}
	}

/**
* @param None.
*
* @throws None.
*
* @return Null. Prints the copyright if set.
*/

	public function foot(): void
	{
		echo "<footer>" . $this->cp() . "</footer>\n</body>/n</html>";
	}

/**
* @param None.
*
* @throws None.
*
* @return string. Full favico tag.
*/

	private function favico(): string
	{
		// return the formatted favico html tag
		return $this->indent(2) . "<link rel=\"icon\" type=\"image/png\" sizes=\"16x16\" href=\"" . TLD . "favicon.ico\" />\n";
	}

/**
* @param None.
*
* @throws None.
*
* @return string. Copyright if set.
*/

	public function cp(): string
	{
		return $this->cp;	
	}

/**
* @param mixed: array or string of tag names, string of type (js, css, etc.)
*
* @throws None.
*
* @return string: completed tag string
*/

	private function genHtag(array $tag, string $type): string
	{
		// Make sure an array has been passed
		if(is_array($tag))
		{
			// Also make sure it isn't an empty array
			if(!empty($tag))
			{
				// Loop through the tags names an generate the tags.
				foreach($tag as $key => $tName)
				{
					// Append the tag text to the tag string builder variable.
					$this->incTags .= $this->indent(2) . $this->genIncTag($tName, $type);
				}
			}
			else
			{
				// Nothing is there, so add nothing to the variable.
				$this->incTags .= "";
			}
		}
		else
		{
			
			$this->incTags .= "";		
		}

		// String is complete. Return it.
		return $this->incTags;
	}

/**
* @param string: The name and type of tag to generate
*
* @throws None.
*
* @return string: Completed tag string
*/

	private function genIncTag(string $nam, string $type): string
	{
		// Convery both parameters to lower case.
		$nam = strtolower($nam);
		$type = strtoupper($type);

		if($type == "FONT") // For google fonts.
		{
			return "<link href='https://fonts.googleapis.com/css?family=" . ucfirst($nam) . "' rel='stylesheet'>\n";
		}
		elseif($type == "CSS") // CSS files
		{
			// Make sure the file is there.
			if(file_exists(CSSDIR . $nam . ".css"))
			{
				// If it is, generate the tag
				return "<link type=\"text/css\" href=\"". CSS . "/" . $nam . ".css\" rel=\"stylesheet\">\n";
			}
			else
			{
				// If not, return blank.
				return "";
			}
		}
		elseif($type == "JS") // .js files
		{
			// Make sure the file is there.
			if(file_exists(JSDIR . $nam . ".js"))
			{
				// If it is, generate the include tag
				return "<script type=\"text/javascript\" src=\"" . JS . "/" . $nam . ".js\" nonce=\"" . NONCE . "\"></script>\n";
			}
			else
			{	
				// Nothing to add.
				return "";
			}
		}
	}

/**
* @param None.
*
* @throws None.
*
* @return None. Adds the Google reCAPTCHA code.
*/

	private function genRecap(): void
	{
		// First, make sure the user opted in to Google reCAPTCHA
		// Setting for this is in /FF/Config/Settings.php
		if(GRC === true)
		{
			// Make sure the public and private keys are set.
			if((null !== GPUBKEY && strlen(GPUBKEY) > 0) && (null !== NONCE && strlen(NONCE) > 0))
			{
				// Generate the tags and add them to the classwide echo string.
				$this->incTags .=  $this->indent(2) . "<script src=\"https://www.google.com/recaptcha/api.js?render=" . GPUBKEY . "\" nonce=\"" . NONCE . "\"></script>\n";
				$this->incTags .= $this->indent(2) . "<script nonce=\""  . NONCE . "\">\n";
			    $this->incTags .= $this->indent(2) . "  grecaptcha.ready(function() {\n";
				$this->incTags .= $this->indent(2) . "    grecaptcha.execute('" . GPUBKEY . "', {action: 'gbemlogin'}).then(function(token)\n";
				$this->incTags .= $this->indent(2) . "    {\n";
				$this->incTags .= $this->indent(2) . "      document.getElementById('g-recaptcha-response').value = token;\n";
				$this->incTags .= $this->indent(2) . "    });\n";
				$this->incTags .= $this->indent(2) . "  });\n";
				$this->incTags .= $this->indent(2) . "</script>\n";
			}
		}
	}

/**
* @param string|array: js code to be displayed.
*
* @throws \Exception: No NONCE set.
*
* @return string|bool. Returns the full script or false.
*/

	public function genInlineJS($code)// : string|bool - Not available until PHP 8.0
	{
		// Initialize a string variable
		// to hold the compiled string.
		$js_string = "";

		// Make sure the globally generate nonce is set.
		if(null !== NONCE && strlen(NONCE) > 0)
		{
			// Append the script opening tag with nonce
			// included.
			$js_string .=  $this->indent(2) . "<script nonce=\""  . NONCE . "\">\n";

			// Check to see if the code is an array 
			// or string.
			if(is_array($code))
			{
				// For an array, break it apart and 
				// appent each value as a new line.
				foreach($code as $nulKey => $snippet)
				{
					$js_string .=  $this->indent(3) . $snippet . "\n";
				}
			}
			else
			{
				// Append the string
				$js_string .=  $this->indent(3) . $code . "\n";
			}

			//Add the ending script tag
			$js_string .=  $this->indent(2) . "</script>\n";

			// Return the complete script with tags.
			return $js_string;
		}
		else
		{
			// Throw exception. Nonce not set.
			throw new \Exception("NONCE is not set, this is a default CSP protection. Please re-enable it.");
			return false;
		}
	}

/**
* @param string: Content Security Policy directive(s).
*
* @throws Nothing.
*
* @return Void: Sets the CSP class variable.
*/

	private function genCSP(string $csp): void
	{
		// Make sure something is in the
		// parameter string
		if(strlen(trim($csp)) > 0)
		{
			// Setup an array of opening CSP tags 
			// for each browser type
			$tagarr = array("Content-Security-Policy","X-Content-Security-Policy","X-WebKit-CSP");

			// Add each CSP tag to the CSP class
			// variable.
			foreach ($tagarr as $ranKey => $val)
			{
				$this->csp .= $this->indent(2) . "<meta http-equiv=\"" . $val . "\" content=\"" . strtolower($csp) . "\" />\n";
				
				//Also set the header CSP.
				header($val . ":" . strtolower($csp));
			}
		}
		else
		{
			// Don't add anything.
			$this->csp = "";
		}
	}
/**
* @param string: Open Graph values.
*
* @throws Nothing.
*
* @return Nothing: Sets the OG class variable.
*/

	public function OG_gen(string $uri, string $type, string $title, string $description): void
	{
		$this->OG .= $this->indent(2) . "<meta property=\"og:url\" content=\"" . $uri . "\" />\n";
		$this->OG .= $this->indent(2) . "<meta property=\"og:type\" content=\"" . $type . "\" />\n";
		$this->OG .= $this->indent(2) . "<meta property=\"og:title\" content=\"" . $title . "\" />\n";
		$this->OG .= $this->indent(2) . "<meta property=\"og:description\" content=\"" . $description . "\" />\n";

		// OG - for articles
		/* This is here as reference for future implementation
		<meta property="og:url"                content="http://www.example.com/article.html" />
		<meta property="og:type"               content="article" />
		<meta property="og:title"              content="Article Title" />
		<meta property="og:description"        content="Article Description" />
		*/
	}

/**
* @param string: Open Graph image information.
*
* @throws Nothing.
*
* @return Nothing: Appends more OG info to class variable.
*/
	// Method meant to be called for each image that requires OG tags. 
	public function OG_img(string $img, string $ext, ?string $desc = ""): void // Image filename & extension, assumed to be in the default image folder.
	{
		$ogholder = ""; //Temporary string variable to hold the tags.
		$fullImg = IMGDIR . $img . "." . $ext;
		$lnkImg = IMG . "/" . $img . "." . $ext;
		
		if(file_exists($fullImg))
		{
			if($this->checkImg($fullImg, $ext))
			{
				$imginfo = getimagesize($fullImg);
				$this->OG .= $this->indent(2) . "<meta property=\"og:image:secure_url\" content=\"" . $lnkImg . "\" />\n";
				$this->OG .= $this->indent(3) . "<meta property=\"og:image:type\" content=\"" . $imginfo["mime"] . "\" />\n";
				$this->OG .= $this->indent(3) . "<meta property=\"og:image:width\" content=\"" . $imginfo[0] . "\" />\n";
				$this->OG .= $this->indent(3) . "<meta property=\"og:image:height\" content=\"" . $imginfo[1] . "\" />\n";
				if(strlen($desc) > 0)
				{
					$this->OG .= $this->indent(3) . "<meta property=\"og:image:alt\" content=\"" . $desc . "\" />\n";
				}
			}
		}
	}

/**
* @param int: number of default space character to display
*
* @throws Nothing.
*
* @return string: requested spaces or empty string
*/

	private function indent(?int $spaces = 1): string
	{
		// Make sure it is an integer.
		if(is_int($spaces) && $spaces > 0)
		{
			// Loop count through until
			// int is met, while appending
			// the space value each time.
			$spaced = "";
			for($s=0;$s<=$spaces;$s++)
			{
				$spaced .= SA;
			}

			// Return the spaces.
			return $spaced;
		}
		else
		{
			return "";
		}
	}

/**
* @param string: full path of image and extension.
*
* @throws Nothing.
*
* @return Boolean: True if extension matches
*/

	private function checkImg(string $imgPath, string $ext): bool
	{
		// Check image type vs extension, true if the 
		// extension matches the type, false if not.
		if(strtolower($ext) == "gif")
		{
			if (exif_imagetype($imgPath) != IMAGETYPE_GIF) 
			{
				Return false;
			}
			else
			{
				Return true;
			}
		}
		elseif (strtolower($ext) == "png")
		{
			if (exif_imagetype($imgPath) != IMAGETYPE_PNG) 
			{
				Return false;
			}
			else
			{
				Return true;
			}
		}
		elseif (strtolower($ext) == "jpg")
		{
			if (exif_imagetype($imgPath) != IMAGETYPE_JPEG) 
			{
				Return false;
			}
			else
			{
				Return true;
			}
		}
		else
		{
			Return false;
		}
	}

/**
* @param string: category and lang id
*
* @throws Nothing.
*
* @return string: requested language text if found.
*/

	public function findLang(string $name, string $id): string
	{
		// Make sure the lang value is found.
		if(null !== $this->lang->getLang($name, $id))
		{
			// Return the corresponding text
			return $this->lang->getLang($name, $id);
		}
		else
		{
			// Return blank
			return "";
		}
	}

/**
* @param None.
*
* @throws Nothing.
*
* @return Nothing: Redirects if error is not set.
*/

	public function checkErr(): void
	{
		// Check if session variable is set.
		if(!isset($_SESSION["Err"]) || strlen($_SESSION["Err"]) === 0)
		{
			// not set, so redirect.
			header("Location: " . TLD . "404/");
			exit;
		}
	}

/**
* @param None.
*
* @throws Nothing.
*
* @return Nothing: Sets session variable as empty.
*/

	public function resetErr(): void
	{
		if(isset($_SESSION["Err"]) && strlen($_SESSION["Err"]) > 0)
		{
			$_SESSION["Err"] = "";
		}
	}

/**
* @param None.
*
* @throws Nothing.
*
* @return String: returns the session variable.
*/
	
	public function showErr()//: string|bool -- Not available until PHP 8.0
	{
		if(isset($_SESSION["Err"]) && strlen($_SESSION["Err"]) > 0)
		{
			return $_SESSION["Err"];
		}
		else
		{
			return false;
		}
	}
}