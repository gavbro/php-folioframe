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


// Show all errors for install. Handy for finding issues.
ini_set ('display_errors', 1);  
ini_set ('display_startup_errors', 1);  
error_reporting (E_ALL);

/*
    This class is a collection of methods to
    pre-verify the install so that the user
    will be notified if any files are missing.
*/

Class Preinstall
{
  private $currentDir; // The path to this file on the server.
  private $installDir; // The path to the install directory.
  private $files; // holder for the array of required files.
  
    public function __construct()
    {
        // Get the current directory
        $this->currentDir = realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR;

        // Set the install directory path.
        $this->installDir = $this->currentDir . "install" . DIRECTORY_SEPARATOR;

        // Define the files that the script requires
        // in the install directory.
        $this->files = array(
        "class",
        "curl_errors",
        "check_install",
        "db_config",
        "db_install",
        "init_install",
        "images",
        "newindex",
        "header",
        "settings",
        "version",
        "htaccess",
        "safety"
        );

        // Verify the install directory files
        // and main install file are intact and
        // where they are expected to be.
        if($this->checkInstall() && $this->checkFile("install", $this->currentDir))
        {
            // Display the install.
            header("Location: install.php");
        }
    }

/**
 * @param None.
 *
 * @throws Nothing explicitly. Returns errors to the user.
 *
 * @return Boolean: True if all files found.
*/

    private function checkInstall(): bool
    {
        // Get the amount of files to expect.
        $cntFiles = count($this->files);

        // Set a count variable
        $cnt = 0;

        // Cycle through the files and see
        // if they exist.
        foreach($this->files as $filename)
        {
            // Check for the file.
            if($this->checkFile($filename, $this->installDir))
            {
                // Add it to the count if found.
                $cnt++;
            }
        }

        // Return true only if 
        // all files are found.
        if($cntFiles === $cnt)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

/**
 * @param string: file name.
 *
 * @throws Nothing explicitly. Echoes errors to the user.
 *
 * @return Boolean: True if file is found in directory.
*/

    private function checkFile($file, $dir): bool
    {
        if(file_exists($dir . $file . ".php"))
        {
            return true;
        }
        else
        {
            echo "File " . $dir . $file . ".php is missing. Please replace it from the FolioFrame Repo. <br />";
            return false;
        }
    }
}

// Run the class
return new Preinstall();
?>
