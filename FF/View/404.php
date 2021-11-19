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
 
 // This is the view file for the standart 404 message called by the script when anything goes wrong.

	$view->setup($title, $desc, FALSE, "", $js, $css, $fonts);
	$view->head();
?>
</head>
<body>
<div class="err404">
<h1>Well, this is embarrasing..</h1>
<h2>it looks like the page you are looking for isn't there!</h2>
<img src="<?php echo TLD ?>/img/404error.png" />
<p>You can click the back button or <a href="<?php echo TLD; ?>">head back to the main page</a></p>
</div>
<?php echo $view->cp(); ?>
</body>
</html>
