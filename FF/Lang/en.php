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

return array(
	"Error"=>
	array(
		"title_0"=>"Oops! Something went wrong!",
		"message_0"=>"Our top minds are working on it, but it may take some time.",
		"message_1"=>"We think it may be=> ",
		"message_2"=>"We think it may be one of the following=>",
		"message_3"=>"Missing or invalid email address used.",
		"message_4"=>"Google ReCAPTCHA failed. Are you a Robot?",
		"message_5"=>"Your email is from a restricted domain.",
		"email_0"=>"Unable to send the email. Please try again later.",
		"email_1"=>"Maximum number of sent emails reached for now. You have reached thhe maximum attempts in a short period. <br><br> Please wait and try again later.",
		"code_0"=> "Your code has timed out. Please try again.",
		"code_1" => "The code you entered is incorrect. Please use the link in your email to try again.",
		"code_2" => "You have made too many incorrect attempts.",
		"system_0"=>"Secure Encryption Error.",
		"system_1"=>"An Error internal error has occurred. The details have been compiled and forwarded to the webmaster for review and repair. If the problem persists, please contact the webmaster.",
		"system_2"=>"No further action is required by you. Thank you for your cooperation."
	),
	"Content"=>
	array(
		"page_0"=>"Congratulations! FolioFrame has been installed!",
		"desc_0"=>"Brand new install of PHP-FolioFrame MVC.",
		"link_0"=>"Logout",
		"link_1"=>"Home",
		"link_2"=>"About",
		"link_3"=>"Login",
		"link_4"=>"Admin Panel"
	),
	"Page"=>
	array(
		"about_title"=>"FF: Test about page!",
		"about_desc"=>"A page to demonstrate FolioFrame Content loading."
	),
	"User"=>
	array(
		"label_0"=>"Logged in as:"
	),
	"Form"=>
	array(
		"title_0"=>"Enter your email address.",
		"title_1"=>"Please enter the code provided to your email.",
		"message_0"=>"If you can read this, please enable javascript in your browser. Otherwise things might get ... weird.",
		"message_1"=>"Your already logged in!",
		"label_0"=>"Email Address",
		"label_1"=>"Code",
		"placeholder_0"=>"example@domain.tld",
		"submit_0"=>"   GO   ",
		"submit_1"=>"Verify Code",
		"button_0"=>"Close"
	),
	"Email"=>
	array(
		"title_0"=>"Your " . NM . " code is ready!",
		"body_0"=>"Access code: ",
		"body_1"=>"It will last for " . EC . " minutes before you will have to grab another one.",
		"body_2"=>"If you closed your code window, no worries! Just ",
		"body_3"=>"open a new window!",
		"disclaim_0"=>"This message has been sent as requested as part of the registration process for " . NM . ".",
		"disclaim_1"=>"If you believe you have received this message by mistake, we would be most grateful if you <a href=\"mailto:webmaster@" . NM . "\"?subject=Email not meant for me!&body=Hello,\r\n\r\nPlease remove my email address from your mailing lists and system.\r\n\r\n\r\nThanks.>informed us that the message has been sent to you.</a>",
		"disclaim_2"=>"In this case, we also ask that you delete this message from your mailbox, and do not forward it or any part of it to anyone else. Thank you for your cooperation and understanding."
	)
);