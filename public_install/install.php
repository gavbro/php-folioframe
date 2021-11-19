<?php
// Start/Resume the session.
session_start([
    'cookie_httponly' => true,
    'cookie_secure' => true
]);

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


include_once("./install/class.php");
$cp = new \FF\Install("./install/");

/*

This file is essentially broken into two (2) sections:

	1) Form Entry Area.
	2) Supporting JS.

The $cp variable refers to a called instance of class.php in the install directory.
See that file for all of the validation and processing methods.

*/




//////////////////////////
//       SECTION 1
// Form Entry Area.
//////////////////////////


// Load the checkmark.png image from the install directory.
// Load it at 15 pixels high and assign it to a varable.
$checkmark = "<span class=\"checkmark\">" . $cp->getb64_image('checkmark', 15) . "</span>";

// Load the FolioFrame logo.png image from the install directory.
// Load it at 200 pixels high and assign it to a varable.
$logo = "<a href=\"https://github.com/gavbro/php-folioframe\" target=\"_blank\">" . $cp->getb64_image('logo', 200)  . "</a>";

// Load the header file from the install directory
// and inject the css file info before loading.
$headerFile = realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . "install" . DIRECTORY_SEPARATOR . "header.php";
if(file_exists($headerFile))
{
	$headerlink = "https://" . $cp->removeTrailingSlash($_SERVER['SERVER_NAME']) . "/css/install.css";
	$headdata = include($headerFile);
	$header = preg_replace("~\{webdir\}~", $headerlink, $headdata);
	echo $header;
}

/*

	Instead of tediously commenting every form field below, here is a breakdown of how each form field works.

		- Check to see if there was an error in section 2 by looking into the error array for a matching error key.
		  If there is first display all errors in a header div.

		- On the individual form field level, check the error array for the specific key correspoding
		  to the current form field. If the erro is there, wrap the field in a div that turns it red 
		  and display the error.
		- Display the form field either way while checking if the _POST value for that form field isset.
		  If it is, repopulate the field with the previous value or selection.
*/


?>

	<div class="logo"><?php echo $logo; ?></div>
	<p class="note">For more information check out <a href="https://github.com/gavbro/php-folioframe/blob/main/README.md" target = "_blank">the README file.</a></p>
<div id="main">

<?php 
		// Check for any errors in the error array. 
		// If there are any display them all.
		echo $cp->checkError(NULL, "all");

?>


	<h1>Requirements</h1>
	<ul>
		<li>File or FTP access to the web server (to upload the files).</li>
		<?php echo $cp->checkError("reqSecure") . "\n"; ?>
		<li>HTTPS not HTTP. <?php if($cp->testSecure()) { echo $checkmark; } else { echo "<span class=\"err\">Your site isn't using a secure certificate, <a href=\"https://en.wikipedia.org/wiki/HTTPS\" target=\"_blank\">read about it here</a>.</span>\n"; }  ?></li>
		<?php echo $cp->checkError("reqSecure", "end") . "\n"; ?>
		<?php echo $cp->checkError("reqVersion") . "\n"; ?>
		<li>PHP version 7.2 or higher minimum. <?php if($cp->testVersion(7.2)) { echo $checkmark; } else { echo "<span class=\"err\">Your PHP version is too old. Please upgrade to 7.2 or higher!</span>\n"; }  ?></li>
		<?php echo $cp->checkError("reqVersion", "end") . "\n"; ?>
		<?php echo $cp->checkError("reqSSL") . "\n"; ?>
		<li>OPENSSL (for encrypt and decrypt functions). <?php  if($cp->testOpenSSL()) { echo $checkmark; } else { echo "<span class=\"err\">You must have OPENSSL for this to work!</span>\n"; }   ?></li>
		<?php echo $cp->checkError("reqSSL", "end") . "\n"; ?>
		<?php echo $cp->checkError("reqPDO") . "\n"; ?>
		<li>MySQL PDO Driver loaded and ready. <?php  if($cp->testPDO()) { echo $checkmark; } else { echo "<span class=\"err\">You must have the PDO extension running!</span>\n"; }   ?></li>
		<?php echo $cp->checkError("reqPDO", "end") . "\n"; ?>
		<li>MySQL version 5.0 or higher minimum. </li>
		<li>Access to create, modify, and delete databases and database users.</li>
		

	</ul>
	<h1>Instructions</h1>
	<ol>
		<li>Copy the <span class="dirname">FF</span> directory including all of the files to your server root, which is usually one above your webroot. (Example: /home/user/ or C:\Webserver)
			<p class="note">Do not put in your actual webroot, but your project root. (not in httpdocs, public_html, etc. At least one behind that!)</p>
		</li>
		<li>Once you have the <span class="dirname">FF</span> directory in your server root. Copy all of the files in the public_install folder to your webroot. (httpdocs, public_html, etc.)</li>
		<li>Create a new database.</li>
		<li>Create two new users to acces the database:</li>
		<ol>
			<li>A full access install user. This one will need to be deleted after install is done.</li>
			<li>Another user with <strong>ONLY</strong> execute privileges.</li>
		</ol>
		<li>Fill in the rest of the information below, including your new database users details.</li>
		<li>Click the "Verify" button to make sure everything is OK and begin the install process.</li>
		<li>INSTALL!!</li>
	</ol>
	<h1>Settings</h1>
	<h2>Global Settings</h2>
	<h3>File System info:</h3>
<form name="ffinstall" id="ffinstall" charset="utf8" data-ajax="false" action="<?php echo "https://" . htmlspecialchars($_SERVER["HTTP_HOST"]); ?>/install/init_install.php" method="POST">
	<?php echo $cp->checkError("filedir") . "\n"; ?>
	<div class="formfield">
	  <label for="installdirpath">Your web server root:</label>
	  <input type="text" name="installdirpath" id="installdirpath" class="inputtext"  value="<?php 
	  if(isset($_SESSION["POST_VARS"]["installdirpath"]) && !empty($_SESSION["POST_VARS"]["installdirpath"]))
  	{
  		if(substr($_SESSION["POST_VARS"]["installdirpath"], -1) === "/" || substr($_SESSION["POST_VARS"]["installdirpath"], -1) === "\\")
		{
			echo substr($_SESSION["POST_VARS"]["installdirpath"], 0, -1);
		}
		else
		{
			echo $_SESSION["POST_VARS"]["installdirpath"];
		}
  	}
  	else
  	{
  		echo realpath(dirname(__FILE__). "/../");
  	}
 ?>" />&nbsp;
 	  <p class="note">Location of <span class="dirname">FF</span> directory.</p>
	  <p class="note">If you installed the <span class="dirname">FF</span> directory somewhere else, please specify it here.</p>
  </div>
	<div class="formfield">
	  <label for="installdirname">Application root directory name: </label><span class="webdirmarker"></span>
	  <input type="text" name="installdirname" id="installdirname" class="inputtext" value="<?php
	  if(isset($_SESSION["POST_VARS"]["installdirname"]) && !empty($_SESSION["POST_VARS"]["installdirname"]))
  	{
	 		echo $_SESSION["POST_VARS"]["installdirname"];
  	}
  	else
  	{
  		echo "FF";
  	}

	?>" />
	  <p class="note">If you changed the directory name of the backend install files, put it here. Default is FF</p>
	  <p class="note">Forget to set this here and you can still updated it manually in the new index.php file after.</p>
  </div>
<?php echo $cp->checkError("filedir", "end") . "\n"; ?>
<?php echo $cp->checkError("webdir") . "\n"; ?>
	<div class="formfield">
	  <label for="webdir">Your Web Root:</label>
	  <span class="preinput"><span class="webdirmarker"></span></span><input type="text" name="webdir" id="webdir" class="inputtext" placeholder="httpdocs" value="<?php  
	  	if(isset($_SESSION["POST_VARS"]["webdir"]) && !empty($_SESSION["POST_VARS"]["webdir"]))
	  	{
	  		echo $cp->removeTrailingSlash($_SESSION["POST_VARS"]["webdir"]);
	  	}
	  	else
	  	{
	  		echo substr(str_replace(realpath(dirname(__FILE__). "/../"), "", realpath(dirname(__FILE__))),1);
	  	}
	?>" />
	  <p class="note">If your web root is somewhere else other than in your server root, please specify it here (Example: subdomain/http_docs	).</p>
  </div>
  <?php echo $cp->checkError("webdir", "end") . "\n"; ?>
<h3>Web Info:</h3>
<?php echo $cp->checkError("website") . "\n"; ?>
	<div class="formfield">
	  <label for="website">Your website:</label>
	  <span class="preinput">https://</span><input type="text" name="website" id="website" class="inputtext" placeholder="example.com/" value="<?php  
	  	if(isset($_SESSION["POST_VARS"]["website"]) && !empty($_SESSION["POST_VARS"]["website"]))
	  	{
	  		echo $cp->removeTrailingSlash($_SESSION["POST_VARS"]["website"]);
	  	}
	  	else
	  	{
	  		echo $cp->removeTrailingSlash($_SERVER['SERVER_NAME']);
	  	}
	?>/" />
	  <p class="note">www.example.com or subdomain.example.com is acceptable as long as that is where the index.php is located.</p>
  </div>
<?php echo $cp->checkError("website", "end") . "\n"; ?>
<?php echo $cp->checkError("copy") . "\n"; ?>
  <div class="formfield">
	  <label for="copyfield">Copyright of your site:</label>
	  <span class="preinput">Copyright &copy; <?php echo date("Y"); ?> </span><input type="text" name="copyfield" id="copyfield" class="inputtext" placeholder="Company Name" value="<?php  
	  	if(isset($_SESSION["POST_VARS"]["copyfield"]) && !empty($_SESSION["POST_VARS"]["copyfield"]))
	  	{
	  		echo $_SESSION["POST_VARS"]["copyfield"]; 
	  	} 
	  	?>" />
	  	<p class="note">This is seperate from your name below in case you want to use a different alias or company name for the copyright tag.</p>
	</div>
	<?php echo $cp->checkError("copy", "end") . "\n"; ?>
	<h2>Admin Account Settings</h2>
  <?php echo $cp->checkError("admin_install") . "\n"; ?>
  <div class="formfield">
  	<label for="admin_email">Your Email Address:</label>
  	<input type="email" name="admin_email" id="admin_email"  class="inputtext" size="30" placeholder="myself@example.com" value="<?php  
	  	if(isset($_SESSION["POST_VARS"]["admin_email"]) && !empty($_SESSION["POST_VARS"]["admin_email"]))
	  	{
	  		echo $_SESSION["POST_VARS"]["admin_email"];
	  	}
	  	?>" />
  	<p class="note">This will regester your email in the database with admin access levels.</p>
  	<p class="notealert">Make sure you use a valid email address that you have access to, otherwise you may need to install again.</p>
  </div>
<?php echo $cp->checkError("admin_install", "end") . "\n"; ?>
	<div class="formfield">
  	<label for="admin_name">Your Name:</label>
  	<input type="text" name="admin_name" id="admin_name"  class="inputtext" size="30" placeholder="Jane Smith aka. Mrs. Smith" value="<?php  
	  	if(isset($_SESSION["POST_VARS"]["admin_name"]) && !empty($_SESSION["POST_VARS"]["admin_name"]))
	  	{
	  		echo $_SESSION["POST_VARS"]["admin_name"];
	  	}
	  	?>" />
  	<p class="note">This is optional. Your email will replace your name in the site if this isn't filled in.</p>
  </div>
  <?php echo $cp->checkError("social_fbook") . "\n"; ?>
  <div class="formfield">
  	<label for="admin_fbook">Facebook:</label>
  	<span class="preinput">https://www.facebook.com/</span><input type="text" name="admin_fbook" id="admin_fbook"  class="inputtext" size="30" placeholder="jane.smith.1234" value="<?php  
	  	if(isset($_SESSION["POST_VARS"]["admin_fbook"]) && !empty($_SESSION["POST_VARS"]["admin_fbook"]))
	  	{
	  		echo $_SESSION["POST_VARS"]["admin_fbook"];
	  	}
	  	?>" />
	 <p class="note">Facebook makes it annoying to check if a user exists without their API, so this one isnt checked by the script.</p>
  	<p class="note">(Example: https://www.facebook.com/{jane.smith.1234} <---  This part).</p>
  </div>
<?php echo $cp->checkError("social_fbook", "end") . "\n"; ?>
<?php echo $cp->checkError("social_linked") . "\n"; ?>
  <div class="formfield">
  	<label for="admin_linked">Linkedin:</label>
  	<span class="preinput">https://www.linkedin.com/in/</span><input type="text" name="admin_linked" id="admin_linked"  class="inputtext" size="30" placeholder="jane-smith-a1b2c3d4" value="<?php  
	  	if(isset($_SESSION["POST_VARS"]["admin_linked"]) && !empty($_SESSION["POST_VARS"]["admin_linked"]))
	  	{
	  		echo $_SESSION["POST_VARS"]["admin_linked"];
	  	}
	  	?>" />
	<p class="note">Linkedin also makes it hard to verify an account, so you are on your own here.</p>
  	<p class="note">(Example: https://www.linkedin.com/in/{jane-smith-a1b2c3d4} <---  This part).</p>
  </div>
<?php echo $cp->checkError("social_linked", "end") . "\n"; ?>
<?php echo $cp->checkError("social_twitter") . "\n"; ?>
  <div class="formfield">
  	<label for="admin_twit">Twitter:</label>
  	<span class="preinput">https://twitter.com/</span><input type="text" name="admin_twit" id="admin_twit"  class="inputtext" size="30" placeholder="janesmith" value="<?php  
	  	if(isset($_SESSION["POST_VARS"]["admin_twit"]) && !empty($_SESSION["POST_VARS"]["admin_twit"]))
	  	{
	  		echo $_SESSION["POST_VARS"]["admin_twit"];
	  	}
	  	?>" />
  	<p class="note">(Example: https://twitter.com/{janesmith} <---  This part).</p>
  </div>
  <?php echo $cp->checkError("social_twitter", "end") . "\n"; ?>
  <?php echo $cp->checkError("social_reddit") . "\n"; ?>
  <div class="formfield">
  	<label for="admin_reddit">Reddit:</label>
  	<span class="preinput">https://www.reddit.com/user/</span><input type="text" name="admin_reddit" id="admin_reddit"  class="inputtext" size="30" placeholder="jane_smithyrex_20" value="<?php  
	  	if(isset($_SESSION["POST_VARS"]["admin_reddit"]) && !empty($_SESSION["POST_VARS"]["admin_reddit"]))
	  	{
	  		echo $_SESSION["POST_VARS"]["admin_reddit"];
	  	}
	  	?>" />
  	<p class="note">(Example: https://www.reddit.com/user/{janesmith} <---  This part).</p>
  </div>
  <?php echo $cp->checkError("social_reddit", "end") . "\n"; ?>
  <?php echo $cp->checkError("social_git") . "\n"; ?>
  <div class="formfield">
  	<label for="admin_git">GitHub:</label>
  	<span class="preinput">https://github.com/</span><input type="text" name="admin_git" id="admin_git"  class="inputtext" size="30" placeholder="janesmith" value="<?php  
	  	if(isset($_SESSION["POST_VARS"]["admin_git"]) && !empty($_SESSION["POST_VARS"]["admin_git"]))
	  	{
	  		echo $_SESSION["POST_VARS"]["admin_git"];
	  	}
	  	?>" />
  	<p class="note">(Example: https://github.com/{janesmith} <---  This part).</p>
  </div>
  <?php echo $cp->checkError("social_git", "end") . "\n"; ?>
   <?php echo $cp->checkError("social_insta") . "\n"; ?>
  <div class="formfield">
  	<label for="admin_insta">Instagram:</label>
  	<span class="preinput">https://www.instagram.com/</span><input type="text" name="admin_insta" id="admin_insta"  class="inputtext" size="30" placeholder="janesmith" value="<?php  
	  	if(isset($_SESSION["POST_VARS"]["admin_insta"]) && !empty($_SESSION["POST_VARS"]["admin_insta"]))
	  	{
	  		echo $_SESSION["POST_VARS"]["admin_insta"];
	  	}
	  	?>" />
  	<p class="note">(Example: https://www.insta.com/user/{janesmith} <---  This part).</p>
  </div>
  <?php echo $cp->checkError("social_reddit", "end") . "\n"; ?>
	<h2>Security Settings</h2>
	<div class="formfield">
	  <label for="devmode">Enable Development Mode:</label><br />
	  <span class="preinput">Yes</span><input type="radio" id="devmodeyes" name="devmode" value="TRUE" <?php 
	  	if(isset($_SESSION["POST_VARS"]["devmode"]) && !empty($_SESSION["POST_VARS"]["devmode"]) && $_SESSION["POST_VARS"]["devmode"] === "TRUE")
	  	{
	  		echo "checked";
	  	}
		?> /><br />
	  <span class="preinput">No</span><input type="radio" id="devmodeno" name="devmode" value="FALSE" <?php 
	  	if((!isset($_SESSION["POST_VARS"]["devmode"]) && empty($_SESSION["POST_VARS"]["devmode"])) || $_SESSION["POST_VARS"]["devmode"] === "FALSE"	)
	  	{
	  		echo "checked";
	  	}
		?> />
	  <p class="note">Development mode enables all errors to be displayed to the browser, which is handy when you are in development.</p>
	  <p class="note">Default is FALSE.</p>
	  <p class="note" id="devred">It is NOT a good idea to leave this enabled in production.</p>
	</div>
	<div class="formfield">
	  <label for="recapfield">Use Google reCAPTCHA (v3):</label><br />
	  <span class="preinput">Yes</span><input type="radio"  id="recapyes" name="recapfield" value="TRUE"<?php 
	  	if((!isset($_SESSION["POST_VARS"]["recapfield"]) && empty($_SESSION["POST_VARS"]["recapfield"])) || $_SESSION["POST_VARS"]["recapfield"] === "TRUE")
	  	{
	  		echo " checked";
	  	}
		?> /><br />
	  <span class="preinput">No</span><input type="radio" id="recapno" name="recapfield" value="FALSE"<?php 
	  	if(isset($_SESSION["POST_VARS"]["recapfield"]) && $_SESSION["POST_VARS"]["recapfield"] === "FALSE")
	  	{
	  		echo " checked";
	  		echo " />\n		<div id=\"showCap\" style=\"display:none;\">\n";
	  	}
	  	else
	  	{
	  		echo "/>\n		<div id=\"showCap\">\n";
	  	}
?>
			<p class="note">This is highly recommended to keep bots away from log in attempts!</p>
	  	<p class="note">See <a href="https://developers.google.com/recaptcha/" target="_jfds">https://developers.google.com/recaptcha/</a> to get your code.</p>
	  	<?php echo $cp->checkError("rcpubkey") . "\n"; ?>
			<label for="pubkeyfield">Public Key:</label>
			<input type="text" name="pubkeyfield" id="pubkeyfield" class="inputtext" placeholder="Public Key here" value="<?php  
	  	if(isset($_SESSION["POST_VARS"]["pubkeyfield"]) && !empty($_SESSION["POST_VARS"]["pubkeyfield"]))
	  	{
	  		echo $_SESSION["POST_VARS"]["pubkeyfield"];
	  	} 
	  	?>" /><br />
			<?php echo $cp->checkError("rcpubkey", "end") . "\n"; ?>
			<?php echo $cp->checkError("rckey") . "\n"; ?>
			<label for="privkeyfield">Private Key:</label>
			<input type="text" name="privkeyfield" id="privkeyfield" class="inputtext" placeholder="Private Key here" value="<?php  
	  	if(isset($_SESSION["POST_VARS"]["privkeyfield"]) && !empty($_SESSION["POST_VARS"]["privkeyfield"]))
	  	{
	  		echo $_SESSION["POST_VARS"]["privkeyfield"];
	  	} 
	  	?>" />
			<?php echo $cp->checkError("rckey", "end") . "\n"; ?>
	  </div>
	  
	</div>
	<?php echo $cp->checkError("timing") . "\n"; ?>
	<div class="formfield">
  	<label for="codetimefield">Code Timeout:</label>
  	<input type="number" name="codetimefield" id="codetimefield" style="width:50px;" min="5" max="60" class="inputtext" value="<?php  
	  	if(isset($_SESSION["POST_VARS"]["codetimefield"]) && !empty($_SESSION["POST_VARS"]["codetimefield"]))
	  	{
	  		echo $_SESSION["POST_VARS"]["codetimefield"];
	  	}
	  	else
	  	{
	  		echo "15";
	  	}
	  	?>" />
  	<p class="note">The length of time in minutes that a email verification code will be valid for.</p>
  </div>
  <?php echo $cp->checkError("timing", "end") . "\n"; ?>
  <?php echo $cp->checkError("length") . "\n"; ?>
  <div class="formfield">
  	<label for="codelenfield">Code Length:</label>
  	<input type="number" name="codelenfield" id="codelenfield" style="width:50px;" min="4" max="8" class="inputtext" value="<?php  
	  	if(isset($_SESSION["POST_VARS"]["codelenfield"]) && !empty($_SESSION["POST_VARS"]["codelenfield"]))
	  	{
	  		echo $_SESSION["POST_VARS"]["codelenfield"];
	  	}
	  	else
	  	{
	  		echo "6";
	  	}
	  	?>" />
  	<p class="note">The length in digits of the code sent to users email for verification. (min = 4, max = 8)</p>
  </div>
  <?php echo $cp->checkError("length", "end") . "\n"; ?>
  <?php echo $cp->checkError("tries") . "\n"; ?>
  <div class="formfield">
  	<label for="codetriesfield">Code Attempts:</label>
  	<input type="number" name="codetriesfield" id="codetriesfield" style="width:50px;" min="3" max="9" class="inputtext" value="<?php  
	  	if(isset($_SESSION["POST_VARS"]["codetriesfield"]) && !empty($_SESSION["POST_VARS"]["codetriesfield"]))
	  	{
	  		echo $_SESSION["POST_VARS"]["codetriesfield"];
	  	}
	  	else
	  	{
	  		echo "3";
	  	}
	  	?>" />
  	<p class="note">How many tries the user has to get the code correct before it disables the code. (min = 3, max = 9)</p>
  </div>
  <?php echo $cp->checkError("tries", "end") . "\n"; ?>
  <h2>Database Settings</h2>
  <?php echo $cp->checkError("dbase_main") . "\n"; ?>
  <div class="formfield">
  	<label for="dbase_name">Database Address:</label>
  	<input type="text" name="dbase_address" id="dbase_address"  class="inputtext" value="<?php  
	  	if(isset($_SESSION["POST_VARS"]["dbase_address"]) && !empty($_SESSION["POST_VARS"]["dbase_address"]))
	  	{
	  		echo $_SESSION["POST_VARS"]["dbase_address"];
	  	}
	  	else
	  	{
	  		echo "localhost";
	  	}
	  	?>" />
  	<p class="note">You can specify and IP address here, but that is not secured yet for SSL. Be Warned!</p>
  </div>
  <div class="formfield">
  	<label for="dbase_name">Database Name:</label>
  	<input type="text" name="dbase_name" id="dbase_name"  class="inputtext" value="<?php  
	  	if(isset($_SESSION["POST_VARS"]["dbase_name"]) && !empty($_SESSION["POST_VARS"]["dbase_name"]))
	  	{
	  		echo $_SESSION["POST_VARS"]["dbase_name"];
	  	}
	  	else
	  	{
	  		echo "folioframe_auth";
	  	}
	  	?>" />
  	<p class="note">Please make sure this matches the database you already have created for this above.</p>
  </div>
  <?php echo $cp->checkError("dbase_main", "end") . "\n"; ?>
  <div class="formfield">
  	<label for="dbase_engine">Database Engine:</label>
  	<select name="dbase_engine" class="inputtext">
  		<option value="MyISAM" <?php if(!isset($_SESSION["POST_VARS"]["dbase_engine"]) || isset($_SESSION["POST_VARS"]["dbase_engine"]) && $_SESSION["POST_VARS"]["dbase_engine"] === "MyISAM") { echo "SELECTED"; } ?>>MyIsam</option>
  		<option value="InnoDB" <?php if(isset($_SESSION["POST_VARS"]["dbase_engine"]) && $_SESSION["POST_VARS"]["dbase_engine"] === "InnoDB") { echo "SELECTED"; } ?>>InnoDB</option>
  	</select>
  	</div>
  	<div class="formfield">
  	<label for="dbase_char">Database Charset:</label>
  	<select name="dbase_char" class="inputtext">
  		<option value="latin1" <?php if(isset($_SESSION["POST_VARS"]["dbase_char"]) && $_SESSION["POST_VARS"]["dbase_char"] === "latin1") { echo "SELECTED"; } ?>>Latin1</option>
  		<option value="utf8mb4" <?php if(!isset($_SESSION["POST_VARS"]["dbase_char"]) || isset($_SESSION["POST_VARS"]["dbase_char"]) && $_SESSION["POST_VARS"]["dbase_char"] === "utf8mb4") { echo "SELECTED"; } ?>>UTF8</option>
  	</select>
  </div>
  <h3>Installation Database User (Full Access Temporary)</h3>
  <?php echo $cp->checkError("dbase_install") . "\n"; ?>
  <div class="formfield">
  	<label for="inst_dbase_user">Install Database User:</label>
  	<input type="text" name="inst_dbase_user" id="inst_dbase_user"  class="inputtext" size="30" placeholder="Install Db User Name" value="<?php  
	  	if(isset($_SESSION["POST_VARS"]["inst_dbase_user"]) && !empty($_SESSION["POST_VARS"]["inst_dbase_user"]))
	  	{
	  		echo $_SESSION["POST_VARS"]["inst_dbase_user"];
	  	}
	  	?>" />
  	<p class="note">Please enter the full access username you assigned to the database.</p>
  </div>
  <div class="formfield">
  	<label for="inst_dbase_pass">Install Database Password:</label>
  	<input type="password" name="inst_dbase_pass" id="inst_dbase_pass"  class="inputtext" size="30" placeholder="Install Password" value="<?php  
	  	if(isset($_SESSION["POST_VARS"]["inst_dbase_pass"]) && !empty($_SESSION["POST_VARS"]["inst_dbase_pass"]))
	  	{
	  		echo $_SESSION["POST_VARS"]["inst_dbase_pass"];
	  	}
	  	?>" />
  	<p class="note">Please enter the full access password you assigned to the database.</p>
  </div>
  <?php echo $cp->checkError("dbase_install", "end") . "\n"; ?>
   <h3>Usage Database User (Execute privilage only)</h3>
   <?php echo $cp->checkError("dbase_user") . "\n"; ?>
  <div class="formfield">
  	<label for="dbase_user">Database User:</label>
  	<input type="text" name="dbase_user" id="dbase_user"  class="inputtext" size="30" placeholder="Exe Only Db User Name" value="<?php  
	  	if(isset($_SESSION["POST_VARS"]["dbase_user"]) && !empty($_SESSION["POST_VARS"]["dbase_user"]))
	  	{
	  		echo $_SESSION["POST_VARS"]["dbase_user"];
	  	}
	  	?>" />
  	<p class="note">Please enter the production username you assigned to the database.</p>
  </div>
  <div class="formfield">
  	<label for="dbase_pass">Database Password:</label>
  	<input type="password" name="dbase_pass" id="dbase_pass"  class="inputtext" size="30" placeholder="Exe Only Password" value="<?php  
	  	if(isset($_SESSION["POST_VARS"]["dbase_pass"]) && !empty($_SESSION["POST_VARS"]["dbase_pass"]))
	  	{
	  		echo $_SESSION["POST_VARS"]["dbase_pass"];
	  	}
	  	?>" />
  	<p class="note">Please enter the production password you assigned to the database.</p>
  </div>
  <?php echo $cp->checkError("dbase_user", "end") . "\n"; ?>
   <div class="formfield">
  	<label for="dbase_prefix">Db Table Prefix:</label>
  	<input type="text" name="dbase_prefix" id="dbase_prefix"  class="inputtext" value="<?php  
	  	if(isset($_SESSION["POST_VARS"]["dbase_prefix"]) && !empty($_SESSION["POST_VARS"]["dbase_prefix"]))
	  	{
	  		echo $_SESSION["POST_VARS"]["dbase_prefix"];
	  	}
	  	else
	  	{
	  		echo "ff_";
	  	}
	  	?>" />
  	<p class="note">by default folioframe uses the "ff_" prefix, you can change this here if you want.</p>
  	<p class="note">Having a prefix will help you differentiate the required DB tables for FolioFrame from your own.</p>
  </div>
  <input type="hidden" name="install_url" value="<?php echo "https://" . htmlspecialchars($_SERVER["HTTP_HOST"]); ?>" />
	<p><input type="button" class="inputButton" id="resetButt" value="Reset" /><input type="submit" name="subbut" class="inputButton" value="Verify" /></p>
</form>
</div>
<?php


//////////////////////////
//       SECTION 2
// Supporting JS.
//////////////////////////


?>
<script>
	// Grab all instances of the FF directory mention in the text
	// They are all currently wrapped in a span tag named dirname.
	const instdirs = document.getElementsByClassName("dirname");

	// this is the same idea, just with the rootdir value.
	const rootdirs = document.getElementsByClassName("webdirmarker");

	// If the user selects no to Google reCAPTCHA, hide the key fields.
	document.getElementById('recapno').addEventListener("click", function(){
  		document.getElementById("showCap").style.display = "none";
	}); 

	// If the user selects yes to Google reCAPTCHA, show the key fields.
	document.getElementById('recapyes').addEventListener("click", function(){
  		document.getElementById("showCap").style.display = "block";
	});

	// Clicking no to display all errors makes the text normal.
	document.getElementById('devmodeno').addEventListener("click", function(){
		document.getElementById("devred").style.color = '#838383';
		document.getElementById("devred").style.fontWeight = 'normal';
	}); 

	// Clicking yes to display all errors makes some of the text RED.
	document.getElementById('devmodeyes').addEventListener("click", function(){
		document.getElementById("devred").style.color = 'red';
		document.getElementById("devred").style.fontWeight = 'bold';
	});

	// The reset button reloads the page, circumventing a more complicated
	// approach of manually setting everything back to normal.
	document.getElementById('resetButt').addEventListener("click", function(){
			window.location.replace("<?php echo $_SERVER['REQUEST_URI'] ?>");
	});

	// On page load, set the default values for the dirpath.
	document.addEventListener("DOMContentLoaded", function(e) {
		if(document.getElementById('installdirpath').value.length > 0)
		{
			for(var f = 0; f < rootdirs.length; f++)
			{
				rootdirs[f].innerHTML = document.getElementById('installdirpath').value + '\<?php echo DIRECTORY_SEPARATOR; ?>';
			}
		}
	});

	// After the users cursor leaves the dirpath field, set the value everywhere
	// in the text.
	document.getElementById('installdirpath').addEventListener("focusout", function(e){
		if(e.target.value.length > 0)
		{
			for(var g = 0; g < rootdirs.length; g++)
			{
				rootdirs[g].innerHTML = e.target.value + '\<?php echo DIRECTORY_SEPARATOR; ?>';
			}
		}
	});

	// After the users cursor leaves the install dir field, set the value everywhere
	// in the text.
	document.getElementById('installdirname').addEventListener("focusout", function(e){
		if(e.target.value.length > 0)
		{
			for(var c = 0; c < instdirs.length; c++)
			{
				instdirs[c].innerHTML = e.target.value;
			}
		}
		else
		{
			e.target.value = 'FF';
			for(var c = 0; c < instdirs.length; c++)
			{
				instdirs[c].innerHTML = 'FF';
			}
		}
	});
</script>

</body>
</html>
<?php

// Clear any errors after display.
// If not they will keep showing up and
// accumulating.
if(isset($_SESSION["install_error"]))
{
	unset($_SESSION["install_error"]);
}

// Clear the POST session variables
// We don't want the script using 
// old or defunct settings on a
// refresh.
if(isset($_SESSION["POST_VARS"]))
{
	unset($_SESSION["POST_VARS"]);
}

?>