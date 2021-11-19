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

/*
	This is a script page to check that the install files are removed
	and the install user is as well. If not, it displays them to the user
	with warnings and the option to delete the files automatically.
*/

/**
 * @param string: Db credentials
 *
 * @throws General Exception. 
 *
 * @return Array or Bool: Result array or false.
*/

function listPerms($host, $db, $user, $pass)// : array|bool -- Cannot use until PHP 8.0
{
	try
	{
		$dsn = 'mysql:host='. $host . ';dbname='. $db;
		if($dbc = new \PDO($dsn, $user, $pass))
		{
			// Select all permissions for this user from the DB
			$s = $dbc->query("select * from information_schema.schema_privileges");
	        $row = $s->fetchAll();

	        // Return result array.
	        return $row;
		}
		else
		{
			return false;
		}
	}
	catch (\Exception $e)
	{
		// Display error if anything fails.
		echo "MYSQL ERROR: " . $e;
		return false;
	}
}

// Set install file locations.
$installFile = PUB . "install.php";
$installDir = PUB . "install" . DS;
$backupDir = PUB . "backup" . DS;
$installCSS = PUB . "css" . DS . "install.css";

// Set location of newly created db credentials 
// for install user.
$db_check_file = $installDir . DIRECTORY_SEPARATOR . "inst_db.php";

// Make sure the post was sent and the temporary 
// CSRF token is set and matches the session equivelent.
if(isset($_POST["fastCheck"]) && ($_POST["fastCheck"] === $_SESSION["fastcheck"]))
{
	// Instantiate array to hold
	// list of backup files.
	$backFiles = array();

	// Set array of file list in the
	// install directory
	$InstFiles = glob($installDir . "*");

	// Make sure the backup directory
	// exists before trying to get
	// a list of files in it.
	if(is_dir($backupDir) && file_exists($backupDir))
	{
		$backFiles = glob($backupDir . "*");
	}

	// Merge both file list arrays
	// instead of processing them
	// seperately.
	$files = array_merge($InstFiles, $backFiles);
	try
	{
		// Go through the list of files
		// deleting them.
		foreach($files as $nD => $file)
		{
			unlink($file);
		}
		// Also delete the 
		unlink($installFile);
		unlink($installCSS);
		rmdir($installDir);
		sleep(1);
		header("Location: " . TLD);
	}
	catch(\Exception $e)
	{
		// Display the error to the user.
		echo "Unable to remove files: " . $e;
	}
}

// Make sure the required file
// wasn't already deleted externally.
if(file_exists($db_check_file))
{
	// Assign the file contents to a string
	// variable.
	$db_check = include_once($db_check_file);

	// Invoke the permission check function
	// to retreive and array of all the 
	// permissions listed for the install user.
	if($permListArr = listPerms($db_check["host"], $db_check["db_name"], $db_check["install_user"], base64_decode($db_check["install_pass"])))
	{
		// Display all that are found as a warning.
		echo "<h1>The install database user: `" . $db_check["install_user"] . "`@`" . $db_check["host"] . "` still exists with the following permissions on the " . $db_check["db_name"] . " database!</h1>";
		echo "<ul>";
		foreach($permListArr as $numKey => $permArray)
		{
			echo "<li>" . $permArray["PRIVILEGE_TYPE"] . "</li>\n";
		}
		echo "</ul>";
		echo "<h2>This is not best practice, so unless you plan on reinstalling FolioFrame again, please remove the user, or at least revoke all priveleges on the " . $db_check["db_name"] . " database before prodeeding. </h2><p>Note: <i>If you intend to keep this user active regardless, just ignore the above message and proceed to remove the files below. This will also remove this warning.</i></p><br /><br /><br />";
	}
}

// Check to see if anything is still
// around that should be removed.
if(is_dir($installDir) || (file_exists($installFile) || file_exists($installCSS)))
{
	echo "<h1>Install files detected!</h1>\n";
	echo "<h2>Please remove the following files from your webroot:</h2> \n";
	echo "<h3>They are either redundant or a potential security concern if not removed.</h3> \n";

	// Are the install files (.php & .css)
	// still there?
	if(file_exists($installFile) || file_exists($installCSS))
	{
		echo "<ul>";
		echo "<li>" . PUB . "<ol>";

		// Display the filename of the install
		// php if it is there
		if(file_exists($installFile))
		{
			echo "<li>" . str_replace(PUB, "", $installFile) . "</li>\n";
		}

		// Display the filename of the install
		// css if it is there
		if(file_exists($installCSS))
		{
			echo "<li>" . str_replace(PUB, "", $installCSS) . "</li>\n";
		}
		echo "</ol></li></ul>\n";
	}

	// Make sure the install directory wasn't
	// already manually deleted and is still
	// there.
	if(is_dir($installDir))
	{	
		echo "<ul>";
		echo "<li>" . $installDir . "<ol>";

		// Set array of file list in the
		// install directory
		$files = glob($installDir . "*");

		// Cycle through the remaining files
		// still in the install directory
		// and display the file names.
		foreach($files as $nullKey => $filename)
		{
			echo "<li>" . str_replace($installDir, "", $filename) . "</li>";
		}
		echo "</ol></li></ul>";
	}
	
	// See if the install script found anything to 
	// backup and dislay a list of the files to 
	// the user as a reminder to back them up.
	if(is_dir($backupDir))
	{   
		echo "<h4>Oops!! It looks like you had existing files that the script backed up for you before replacing them:</h4>\n";
		echo "<ul>";
		echo "<li>" . $backupDir . "<ol>";

		// Set array of file list in the
		// backup directory
		$backFiles = glob($backupDir . "*");

		// Cycle through the remaining files
		// still in the backup directory
		// and display the file names.
		foreach($backFiles as $nullKey => $filename)
		{
			echo "<li>" . str_replace($backupDir, "", $filename) . "</li>";
		}
		echo "</ol></li></ul>";
		echo "<h4>If there is anything important here, you had better go back it up before going forward.</h4>\n";
		echo "<p>The below automatic install file removal script will delete these backup files as well.</p>\n";
	}
?>
	<h1>Automatic Install File Removal</h1>
	<h3>You can also try the below form to remove them for you.</h3>
	<p>This will attempt to delete all of the install files listed above. It should work, but might not if your file permissions are too restrictive.</p>
<?php
	$smallCheck = base64_encode(openssl_random_pseudo_bytes(32));

	$_SESSION["fastcheck"] = $smallCheck;
?>
	<form name="remForm" method="POST">
		<input type="hidden" name="fastCheck" value="<?php echo $smallCheck; ?>">
		<input type="button" id="subRem" value= " Remove Files ">
	</form>
	<script nonce="<?php echo NONCE; ?>">
		const formDoc = document.getElementById('subRem');
		formDoc.addEventListener("click", function(e) {
			if(confirm("These files are not needed any longer, though if you want them again you can just re-upload them from the FolioFrame source at:\n\n https://github.com/gavbro/php-folioframe/.\n\n\n So, no worries!"))
			{
				document.remForm.submit();
			}
		});
	</script>
	<br /><br /><br />
<?php
}
?>
<br /><br /><br />
