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

// This file is meant to be an easy way to include the login form wherever
// you want to display it. It is ready to go once included.

// If the user isn't already logged in, then show the email
// login input.
if(!$loggedIn) { $form->secure_start("emlogin", TLD . $_SESSION["LN"] .  "/Login"); ?>
<div id="emailOverlay">
    <div class="login">
        <img src="<?php echo TLD ?>img/logo_med.png" class="emailLoginLogo" />
    <?php $form->fieldEmail("mail", $view->findLang("Form","label_0") . " :", $view->findLang("Form","placeholder_0")); ?>
    
    <?php echo $form->display(); ?>
    <input type="button" class="logButton" id="logSubmit" name="logSubmit" value="<?php echo $view->findLang("Form","submit_0"); ?>" />
    <input type="button" id="emailCloseX" class="logButton" value="<?php echo $view->findLang("Form","button_0"); ?>" />
    </div>
    <span href="#" id="emailCloseBut" class="emailOverlayClose"><?php echo $view->findLang("Form","button_0"); ?> (X)</span>
<?php
    // Check if Google reCAPTCHA is enabled.
    if(GRC)
    {  
        //If so, show the required links (part of the Google EULA)
?>
    <div class="RecapProt">
        This site is protected by reCAPTCHA and the Google
        <a href="https://policies.google.com/privacy">Privacy Policy</a> and
        <a href="https://policies.google.com/terms">Terms of Service</a> apply.
    </div>
<?php
    }
 ?>
</div>
<?php 
    // You can use the echo $view->genInlineJS(array("")); to print inline JS as well.
    // each element is considered a line of code.
?>
<script type="text/javascript" nonce="<?php echo NONCE; ?>"> 
    if (window.addEventListener) 
    {
      window.addEventListener('load', SetOverlay, false); 
    } 
    else if (window.attachEvent) 
    {
      window.attachEvent('onload', SetOverlay);
    }

    function SetOverlay()
    {
        const overlay = document.getElementById('emailOverlay');
        const emForm = document.emlogin;
        const goButton = document.getElementById('logSubmit');
        const toggle = document.getElementById('togg_login');
        const closer1 = document.getElementById('emailCloseX');
        const closer2 = document.getElementById('emailCloseBut');
        toggle.addEventListener('click', function(e) {
            overlay.style.display = 'block';
        });
        closer1.addEventListener('click', function(e) {
            overlay.style.display = 'none';
        });
        closer2.addEventListener('click', function(e) {
            overlay.style.display = 'none';
        });
        goButton.addEventListener('click', function(e) {
            emForm.submit();
        });
        document.getElementById('required').style.display = 'none';
    }
</script>
<?php
}



?>
