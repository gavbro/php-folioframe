<h1>Welcome to FolioFrame!</h1>
    <h3>Version 0.0.1 (Alpha)</h3>
    <h4>Thank you for choosing FolioFrame for your online project!</h4>
    <p>If you haven't already, please consider checking out the <a href="https://github.com/gavbro/php-folioframe/blob/main/README.md" rel="noopener noreferrer" target="_blank">README file</a></p>
    <?php
        $domain = parse_url(TLD);
    ?>
    <p>If you are curious about how good the Content Security is pre-setup try <a href="https://cspscanner.com/?q=<?php echo $domain["host"]; ?>" rel="noopener noreferrer" target="_blank">cspscanner</a> and see for yourself!<span class="note">I have no affilation with this site, I just like how it works. Try another one if you like!</span></p>
    <h2>Rough Documentation</h2>
    <p>Until I get time to make a seperate and better organized Documentation area, here are some usage points to check out:</p>
    <ul>
        <li>In order to create a new page like the example "About" one above, you need to do the following: 
            <ol>
                <li>Create a new controller in the /FF/Controller directory and name it to whatever you want the new page to be called.<span class="note">The file must have a capital letter for the first character of the name followed by all lower case letter (Example: News.php)</span><span class="note">It is much easier to copy the /FF/Controller/About.php file and edit it, instead of starging from scratch.</span></li>
                <li>Create a new view in the /FF/View directory and name it to whatever you want.<span class="note">It doesn't matter what you name the view file, as long as you call it by that name in the controller.</span></li>
                <li>There must be a class in the controller file with a matching name, including the case (Example: News.php class would be News)</li>
                <li>The new controller class should be under the <u>vController</u> namespace.<span class="note">Namespace vController;</span></li>
                <li>An associative array must be set that includes the following elements and value types: <span class="note">array("name1" => value, "name2" => value); or $array["name1"] = value; $array["name2"] = value;</span>
                    <ul>
                        <li>title - String (The page title)</li>
                        <li>desc - String (The page description)</li>
                        <li>gpc - Boolean (Use Google reCAPTCHA)<span class="note">Default is the Global: GRC</span></li>
                        <li>csp - Boolean (Use Content Security Policy)<span class="note">Default is the Global: CSP</span><span class="note"><srong>Default is <u>highly</u> recommended.</srong></span></li>
                        <li>fonts - Array (Google Fonts)<span class="note">The name must match Google exactly. (Example: array("Abel", "Oxygen"))</span></li>
                        <li>css - Array (css file[s] to load)<span class="note">Minus the .css extension. Loads from the ./css directory.</span></li>
                        <li>js - Array (js file[s] to load)<span class="note">Minus the .js extension. Loads from the ./js directory.</span></li>
                    </ul>
                </li>
                <li>Load the form helper if you intend on using any secure forms.<span class="note">This helper automatically manages the CSRF, Google reCAPTCHA, and HoneyPot functions.</span><span class="note">Load it using $this->loadHelper('form'); and $arrayFromItemFive['form'] = new \Input\Form();</li>
                <li>A call to load the view file. <span class="note">Example: $this->loadView("ViewName", $arrayFromItemFive); <--_ </span><span class="note">This turns the array key->value pairs into $key = value on the view. So $array["blam"] = value; turns into $blam = value.</span></li>
                <li>A call to the controller class at the end of the controller. <span class="note">Example: return new \vController\News();</span></li>
                <li>If you call a main view file. That file must have the following methods setup correctly to make everyhing work with the controller:
                    <ul>
                        <li>The setup method. <span class="note">This prepares all of the CSP, NONCE, reCAPTHCA and Fonts for us.</span><span class="note">$view->setup($title, $desc, $gcp, $csp, $js, $css, $fonts);</span></li>
                        <li>The head method. <span class="note">$view->head($loggedIn);</span><span class="note">The parameter is optional and shows a different header for logged in users. Default is FALSE if left empty.</span></li>
                    </ul>
                </li>
            </ol>
        </li>
        <li>Another important thing to remember is that any view can be included using a direct PHP include. But it will only work on non-view formatted files.<span class="note">The menu and login form above are loaded this way [Example: include(V . "menu" . E);]</span><span class="note">V and E are both global shortcut definitions in /FF/App/Config.php as the absolute path to the [V]iew directory and the [E]xtention (.php)</span></li>
        <li>The language files are stored in the /FF/lang/ directory, but there is currently just an english version. Accessing a new language is as simple:
            <ul>
                <li>Make a copy of the english file. (en.php)</li>
                <li>Send it to a good human translator (Costs $$$) or a decent auto translating service. (DeepL is pretty good).</li>
                <li>Double check to make sure the array Key names didn't translate, just the value text.</li>
                <li>Name the file to the two letter representation of the new language (Example: de for German, fr for french, kl for Klingon, etc..)</li>
                <li>Navigate to the new language in the browser <span class="note">Do <?php echo TLD; ?>kl/ instead of <?php echo TLD . $_SESSION["LN"]; ?>/</span></li>
                <li>Voila!</li>
                <li>You can change my values in the language file, make your own or do whatever. The main requirement is that the array structure stays the same. Otherwise nightmares will happen.</li>
            </ul>
        </li>
    </ul>