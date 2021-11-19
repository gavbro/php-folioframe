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
?>
<ul class="menutop">
  <li><a href="<?php echo TLD; ?>"><img src="<?php echo TLD; ?>img/logo_bare.png" /></a></li>
  <li><a href="<?php echo TLD . $_SESSION["LN"]; ?>/" class="menuLink"><?php echo $view->findLang("Content","link_1"); ?></a></li>
  <li><a href="<?php echo TLD . $_SESSION["LN"]; ?>/About" class="menuLink"><?php echo $view->findLang("Content","link_2"); ?></a></li>
<?php if ($loggedIn) { 
  if($_SESSION["authAccess"] === 1)
  {
?>
  <li><a href="#" class="menuLink"><?php echo $view->findLang("Content","link_4"); ?></a></li>
<?php    
  }

?>

  <li class="right"><a href="<?php echo TLD . $_SESSION["LN"]; ?>/Logout" class="menuLink"><?php echo $view->findLang("Content","link_0"); ?></a></li>
  <li class="right"><span class="text"><?php echo $view->findLang("User","label_0"); ?></span><a href="#" class="menuLink"><?php
    if(strlen(trim($_SESSION["name"])) > 0)
    {
      echo $_SESSION["name"];
    }
    else
    {
      echo $_SESSION["email"];
    }
  ?></a></li>
<?php } else { ?>
  <li class="right"><a href="#" id="togg_login" class="menuLink"><?php echo $view->findLang("Content","link_3"); ?></a></li>
<?php } ?>
</ul>