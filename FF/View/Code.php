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

	// This is the view file for the code entry, including required js/css.
	$view->setup($title, $desc, $gcp, $csp, $js, $css, $fonts);
	$view->head();
?>
</head>
<body>
<div class="loginForm">
<?php $form->secure_start("emcode", TLD . $_SESSION["LN"] . "/Vcode"); ?>
<div class="codeTitle"><?php echo $view->findLang("Form","title_1"); ?> : <?php /*echo $_SESSION["precode"];*/ ?>	</div>
<?php $form->fieldCode("secCode", $view->findLang("Form","label_1") . " :", $view->findLang("Form","submit_1"), $code); ?>
<?php $form->showHash($hash); ?>
<?php echo $form->display(); ?>
<?php 
    // Each element is a JS line of code. 
    // You can just do your own code without using this
    /* using <script type="text\javascript" nonce="<?php echo NONCE; ?>"></script> */
    // The code will not work without the nonce value set in the script tag.

    echo $view->genInlineJS(array("const inputElement = document.getElementById('fieldCode_1');", "inputElement.addEventListener('keydown', limitChars);", "inputElement.addEventListener('keydown',enforceFormat);", "inputElement.addEventListener('keyup',movetoNext);", "inputElement.addEventListener('paste', isPasteCode);", "inputElement.addEventListener('keydown', isUndo);", "window.addEventListener('load', codeFocus);"));  ?>
</div>
