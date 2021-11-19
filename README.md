# PHP - FolioFrame

### Description

A PHP MVC mini-framework for email based authentication using modern PHP design security practices, with the intent of usage for online portfolios, personal webpages or anything else requiring email/code based authentication.

### Scope

The goal of this project is to develop a viable solution to showcasing someones work, with an option for users to enter a more secure area to see more detailed information.  Although the authentication method I have chosen is a email/code based approached (think codeacademy). The idea of a username/password adapation isn't off the table. Once this is complete, the hope is to have a robust foundation on which a developer or designer can base their personal website or any other project needing a fast way to authenticate users.

### Table of Contents

1. What's the plan?
1. Recently added
1. Upcoming or planned
1. Requirements
1. Recommendations
1. Version
1. Installation 
1. Usage
1. Contributing (To be Added)
1. FAQ
1. Credits
1. Reference Links
1. Licence
1. Disclaimer

### 1. So ...... What's the Plan?

**The initial main components of this little project are**:

* A non-user assessable backend directory where all the core files are kept safe.
	* FF - Main app directory behind the webroot.
		* App - Application Files.
		* Config - Configuration files
		* Controller - The final processing file accessable through the site URL. ( example: https://domain.com/en/Controller )
		* Helper - Any classes that help with overall functionality, but not neccessarily required. 
		* Lang - The language array files accessed throug the URL. (example: /en/ or /Lang/en.php)
		* Libraries - Any extra code that might be needed for only a certain extra functionality to work. (example: reCAPTCHA)
		* Model - The heavier processing of requests, POSTs, etc.
		* Resources - Any other non-script resources to be used (currently a list of blacklisted email domains)
		* View - The php reference file to what the user actually sees. These are loaded by the controllers.
* A index.php root anchor file in the web root to call all the core files.
* Segregated databases for operations that can be segregated to other servers if needed.
* Fully installable and integrated security features right our of the box:
	* Form CSRF protection.
	* Google reCAPTCHA integration (optional)
	* Form HoneyPot.
	* Content Security Policy Pre-Configured.
	* Full database encrypt/decrypt capability done by PHP (AES-256-CTR, Random secret KEY).
	* preconfigured .htaccess.
* Multi-Language Support through a single language file.
	
#### 2. Recently Added:

* Relevant error messages to the user (Example: 'Password incorrect, try again')
* Integrated CAPTCHA or ReCAPTCHA support.
* Admin recognization.
* 20000+ list of denied emails (10 minute emails) the system checks against for each user.
* Full install package (Needs more testing)

#### 3. Upcoming or planned:

* Manual installation files (In case the installer doesn't work for some reason.)
* User panel to add alias, name, send messages, contact info, etc.
* Lots of code optimazation.
* SEO optimization.
* Image manipulation, video and audio playback support.
* Logging overhaul to keep logs and errors for admin.
* Admin messages when important things occur (Bad errors, new users, messages, etc.)


**If this becomes a little more popular I would like to see the following features added**:

* Admin area for administration of users.
* Two-factor authentication.
* Social Media integration.
	
### 4. Requirements

* HTTPS not HTTP. See https://en.wikipedia.org/wiki/HTTPS
* File or FTP access to the web server.
* PHP version 7.2 or higher minimum.
* MySQL version 5.0 or higher minimum. 
* MySQL PDO Driver loaded and ready.
* MySQL ROOT Access (to create and remove users/privileges)

### 5. Recommendations

#### Become a contributer!

If this script helps you, consider contributing! I am only one person and there is alot that can be done:

* Front-end upgrades.
* Code optimization.
* Better installation scripts.
* Much, much, more!

### 6. Version

Current version: 0.0.1 (Functioning Alpha)

### 7. Installation

* Upload the FF directory to your server root (Example: /home/User/ or C:\Webserver\)
* Upload the contents of the Public_Install directory to your web root (Example: /home/User/http_docs or C:\Webserver\www)
	* Note: This might override other files you have in there, so backup your site first.
* Navigate to your website and follow the installation.

Here are what you will need to complete the installation:
	* A valid email address (This will become the admin account)
	* Google reCAPTCHA keys (Optional)
	* A functioning database.
	* A User on the database with FULL granted rights.
	* A User on the database with EXECUTE only (Yes, the script will check)

Install!

7. Test and Enjoy!

### 8. Basic Usage 

	1. Navigate to yourdomain.com/en/ -or-yourdomain.com/en/
	2. Enter your email address and then enter the code in the code field from the email sent to you!
	3. Enjoy the foundation for your new site!

### 9. Contributing

Let me know if you want to contribute to this project. I can't promise that I will accept all submissions, but even any suggestions, helpful points or constructive criticisms that positively impact development will gain you a note as a contributor. My only request is to keep things as simple as possible. I am a big fan of PHP best practices even though time doesn't always allow me to follow them as much as I would like.

### 10. Frequently Asked Questions

**Question**: Is everything encrypted in the database?

* __Answer__: Yes and No. Everything for the pre-configured login script is encrypted, but you can choose to use the encryption or not yourself when you add your own database logic.

**Question**: I messed up a setting on the install, can I change it now?

* __Answer__: Yes. All of the database settings are kept in your /FF/Config/Dbsettings.php file, all of the site settings are in the /FF/Config/Settings.php file.

**Question**: I installed PHP-FolioFrame, but I want to start over, can I do that?

* __Answer__: You certainly can! Just re-upload the files and re-run the install.php. As long as you keep similar settings, it will overwrite the old setup (database name, table prefix, etc.)

**Question**: Can I contact you directly with questions?

* __Answer__: Sure! Just don't be disheartened if I don't get back to you right away. I have a busy family schedule. Contact me on GitHub or at gavin@gavinbrown.ca.


### 11. Credits.

* Creator: Gavin Brown - https://gavinbrown.ca/

### 12. Reference Links

* https://phptherightway.com/ - I am still trying to learn most of this.

### 13. License

MIT License provided by GitHub. See the repository LICENSE file. 

### 14. Disclaimer

Please don't just assume this script is secure. Although the aim is to progressively make it more secure as time goes on, no login script is 100% secure and this one certainly isn't either. This is intended as a starting point to something bigger, so none of its contributors take any responsibility for how you use it or any damages that might occur from using/misusing it.
