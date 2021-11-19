<?php 

// Define the namespace
Namespace Err;

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
    This needs work to be more robust and is on the list to get
    looked into more. Time permitting of course.
*/

Class Xception extends \Exception
{
    private $priorException; // Holds the previous exception
    private $err; // Holds the Exception details in an array

    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous); // Get the exception info from \Exception

        // Assign the info to an array.
        $this->err = array("message" => $message, "code" => $code, "previous" => $previous);

        // If there was a previous Exception.
        // assign it to a variable as well.
        if (!is_null($previous))
        {
            $this->priorException = $previous;
        }
    }   

/**
 * @param None
 *
 * @throws Nothing new.
 *
 * @return Array: Returns the exception information as an array.
*/

    public function getErr(): array
    {
    	return $this->err;
    }
    
/**
 * @param None
 *
 * @throws Nothing new.
 *
 * @return Array: Returns the previous exception information.
*/

    public function getPrior(): array
    {	
       return $this->priorException;
    }
    
}



// Rethrow exceptions through
// the new exception handler (Xception)
function getException($code, $message)
{
 	throw new \Err\Xception($message, $code);   
}

// Set Error Handler.
set_error_handler('\Err\getException');