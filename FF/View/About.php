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
<?php include(V . "login_form" . E); ?>
<?php 
    // include the top menu
    include_once(V . "Menu" . E) 
?>
<div id="main">
    <!-- This is the main area for your homepage stuff -->

    <?php 
        // Just remove this include and delete.
        // the lorum_delete.php file from the /FF/View 
        // directory to start new.
    if(file_exists(V . "lorum_delete" . E))
    {
        include(V . "lorum_delete" . E); 
    }
    ?>

    <?php echo $view->cp(); ?>
</div>
</body>
</html>