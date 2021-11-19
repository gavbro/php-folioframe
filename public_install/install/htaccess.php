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

return "# Disable directory browsing
Options All -Indexes

# ----------------------------------------------------------------------
# Rewrite engine
# ----------------------------------------------------------------------

# Turning on the rewrite engine is necessary for the following rules and features.
# FollowSymLinks must be enabled for this to work.
<IfModule mod_rewrite.c>
	Options +FollowSymlinks
	RewriteEngine On

	# Redirect Trailing Slashes...
	RewriteCond %{REQUEST_FILENAME} !-d
    	RewriteRule ^(.*)/\$ /\$1 [L,R=301]

	# Rewrite \"www.example.com -> example.com\"
	RewriteCond %{HTTPS} !=on
	# RewriteCond %{HTTP_HOST} ^www\.(.+)\$ [NC]
	RewriteRule ^(.*)\$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
	# RewriteRule ^ https://%1%{REQUEST_URI} [R=301,L]

	# Checks to see if the user is attempting to access a valid file,
    # such as an image or css document, if this isn't true it sends the
    # request to the front controller, index.php
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteRule ^(.*)\$ index.php/\$1 [L]

	# Ensure Authorization header is passed along
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
</IfModule>

<IfModule !mod_rewrite.c>
    # If we don't have mod_rewrite installed, all 404's
    # can be sent to index.php, and everything works as normal.
    ErrorDocument 404 index.php
</IfModule>

# Disable server signature start
    ServerSignature Off
# Disable server signature end

# Use HTTP Strict Transport Security to force client to use secure connections only Header always set 

Header set Strict-Transport-Security \"max-age=31536000; includeSubDomains; preload\"";