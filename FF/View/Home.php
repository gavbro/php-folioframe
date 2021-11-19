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

// Prevent outside inclusion of files. Because.. you know, Paranoia
defined('ROOT') OR exit();
 
	$view->setup($title, $desc, $gcp, $csp, $js, $css, $fonts);
    $view->head($loggedIn);
?>
</head>
<body>

<?php 
// Everything below here is really for the first load of the index. 
// It checks to make sur tThe install has been properly removed.

// If this isn't a worry, just remove it. 


// As more or less a last ditch effort to get the install user to clean up.
// First check for the install and check_install files.
if(file_exists(PUB . "install.php") || file_exists(PUB . "install/check_install.php"))
{
    // If check_install is there, load it.
    // it will check the rest for us.
    if(file_exists(PUB . "install/check_install.php"))
    {
        include_once(PUB . "install/check_install.php");
    }
    else
    {
        // check_install isn't there, so just install is.
        // display the warning to the install user.
        echo "<h3>FolioFrame still shows that the main install.php (" . PUB . "install.php) file is in the webroot.</h3>";
        echo "<h4>Please make sure to remove it, along with the following directories and their contents if they still exist:</h4>";
        echo "<ul><li>" . PUB . "install" . DS . "</li><li>" . PUB . "backup" . DS . "</li></ul>";
    }
}
else
{
    // Check for the class file
    if(file_exists(PUB . "install/class.php"))
    {
        // if it is there, include and load it!!
        include_once(PUB . "install/class.php");
        $cp = new \FF\Install();
    }
    else
    {
        // Install is cleaned up, so load 
        // the rest of the home view.
?>
<?php include(V . "login_form" . E); ?>
<?php 
    // include the top menu
    include_once(V . "Menu" . E) 
?>
<div id="main">
    <!-- This is the main area for your homepage stuff -->

        <?php 
        // Just remove this include and delete.
        // the documentation_delete.php file from the /FF/View 
        // directory to start new.
        if(file_exists(V . "documentation_delete" . E))
        {
            include(V . "documentation_delete" . E);
        }
    ?>
     <?php echo $view->cp(); ?>
</div>
<?php
    
    } 
}
?>
</body>
</html>