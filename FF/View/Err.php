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

	// Make sure an error is set.
	// Otherwise it's a 404
	$view->checkErr();

	$view->setup($title, $desc, FALSE, "", $js, $css, $fonts);
	$view->head();
?>
  </head>
<body>
<h1><?php echo $view->findLang("Error","title_0"); ?></h1>
<h3><?php echo $view->showErr(); ?></h3>
</body>
</html>


<?php 

// Reset the error session variable.
// We don't want it to build up after 
// being viewed.
$view->resetErr();

?>