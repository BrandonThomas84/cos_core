=== Adblock Notify by b*web ===
Contributors: brikou
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7Z6YVM63739Y8
Tags:  adblock, page redirect, cookies, notify, modal box, dashboard widget, ads, notification, adBlocker, Responsive, plugin, popup, modal, jquery, ajax, free, advetissement, shortcode, images, image, CSS, lightbox
Requires at least: 3.7
Tested up to: 4.1
Stable tag: 1.6.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Detect Adblock and nofity users. Simple plugin with get around options and a lot of settings. Dashboard widget with counter & statistics included!

== Description ==

Detect Adblock and nofity users. Help to block adblock (eg: Adblock Plus) and increase your ad revenue.
Adblock Notify is a very easy to use plugin with get around options and a lot of settings. A smart dashboard widget with counter & statistics is included!

= Plugin Capabilities =

* Detect adBlocker (eg Adblock Plus)
* Random selectors and files name to prevent adblock to block the plugin
* Custom notification message with jQuery Popup ([Reveal by ZURB](http://zurb.com/playground/reveal-modal-plugin)) or Javascript redirect
* Replace blocked ads by custom message
* 3 available options to notify your users
* Help you increase your ads income with a passive approach
* Responsive design friendly
* Enqueue scripts & CSS files only when necessary
* Fully integrated in your theme design
* User Friendly
* Many design options & custom CSS available
* Smooth admin panel for an easy and fast setup (thanks to [Titan Framework](http://www.titanframework.net/))
* Statistics on you WordPress Dashboard with [chart.js](http://www.chartjs.org/)
* Follow WordPress best practices
* Support for all kind of ads, included asynchronous
* Support Images and shortcodes (eg: [PayPal button](https://www.paypal.com/us/cgi-bin/?cmd=_donate-intro-outside/))
* Use cookie for a better user UI
* Cross browser detection
* Remove settings from database on plugin uninstall
* Admin pages translatable (EN & FR are currently available)

[CHECK OUT THE DEMO](http://b-website.com/adblock-notify-plugin-for-wordpress "Try It!")


**Please ask for help or report bugs if anything goes wrong. It is the best way to make the community benefit!**


= Notice =

* **Don't forget to recreate the selectors after each plugin update if you use the random selectors option**
* Your "/uploads" directory needs to be CHMOD to 0755 (Don't worry, it is the default CHMOD)
* May not work properly with all caching system (depend on parameters, CDN)
* Should works with SSL certificate (https), but not tryed (need feedback!)

= How to use it =
You can notify users with an activated Adblocker software by one of THREE ways !

* A pretty cool and lightweight Modal Box with a custom content : **the COMPLIANT solution**
* A simple redirection to the page of your choice : **the AGRESSIVE solution**
* A custom alternative message where your hidden ads would normally appear : **the TRANSPARENT solution**

[FULL DOCUMENTATION AND EXAMPLES](http://b-website.com/adblock-notify-plugin-for-wordpress "Documentation & examples")

= WordPress requirement =

* WordPress 3.7+ (not tested on above versions, but may works)

= Supported languages =
* English [en_US]
* French [fr_FR]
* Serbian [sr_RS] - Thanks to Ogi Djuraskovic - [firstsiteguide.com](http://firstsiteguide.com "firstsiteguide.com")

Become a translator and send me your translation! [Contact-me](http://b-website.com/contact "Contact")

[CHECK OUT MY OTHER PLUGINS](http://b-website.com/category/plugins-en "More plugins by b*web")


== Installation ==
1. Upload and activate the plugin (or install it through the WP admin console)
2. Click on the "Adblock Notify" menu
3. Follow instructions, every option is documented ;)	

== Frequently Asked Questions ==

= Is it working with Google Adsense Ads? =
Yes, and probably with all kinf of content hidden by an adblocker software.

= Is it compatible with caching systeme =
Yes it is, but you have to exclude "advertisement.js" from the cache files list.

= The plugin is activated and setting up, but nothing append. =
Please, inspect your page and search for an_admin_scripts.js or advertisement.js.
If they are not visible on your page DOM, there is probably a problem with your caching/minify plugin.
Purge all cache and rebuild your minify, then check again.
You can also try to open a new private tab to have a new "clean" test environment.
If you don't have any caching/minify plugin, it is mostly due to your theme which does not contain the required wp_footer() function in the footer.php file.

== Screenshots ==
1. Modal box notification
2. Plugin admin page
3. Statistics on the WordPress Dashboard

== Changelog ==

= 1.6.1 =
* Disable fuckadblock.js because of to many bug repports
* Improve the way cookies are registered
* readme.txt update

= 1.6 =
* Remove the too simple detection by blocking file
* Add the cool fuckadblock.js detection script
* Stronger adblocker detection
* readme.txt update

= 1.5 =
* Fix major issue after regarding JS detection (previous file name has been whitlisted)
* Better js detection
* Serbian translation by Ogi Djuraskovic - [firstsiteguide.com](http://firstsiteguide.com "firstsiteguide.com")
* readme.txt update

= 1.4.5 =
* Minor security improvements
* Titan Framework update (1.7.3)
* Chart.js library update
* Better responsiv support on dashboard charts
* Added charts data on hover

= 1.4.4 =
* Fix minor PHP warnings

= 1.4.3 =
* Footer space CSS fix
* Minor PHP improvements
* Remove unncessary plugin meta

= 1.4.2 =
* Minor CSS fix
* Minor PHP fix

= 1.4.1 =
* Minor PHP fix

= 1.4 =
* Minor PHP fixes and improvements
* Improve scripts & styles enqueuing
* Fixed logo in dashboard option menu
* HTTPs issue remain an issue...
* readme.txt update
* Tested on WP 4.1 with success!

= 1.3.2 =
* Fix plugin enqueing on activation
* Fix scripts enqueing when an-path is not defined
* Fix HTTPs issue
* Fix modal z-index issue on some site


= 1.3.1 =
* Minor performance improvements
* MU activation fix
* Fix header already send warning
* Fix PHP issue on plugin remove

= 1.3 =
* Major PHP improvements and fixes
* Minor JS fixes
* Better performance (less db requests)
* Save settings will update temp files content but not name and selectors
* New svg logo

= 1.2.6 =
* Php fix for AJAX var

= 1.2.5 =
* Php fix for CSS enqueing

= 1.2.4 =
* Php fix for CSS enqueing
* Remove unnecessary files

= 1.2.3 =
* JS fix
* Better theme compatibility (no more using the_content as filter)

= 1.2.2 =
* PHP fix and improvements
* New option panel organisation
* allow_url_fopen fallback to CURL
* DB requests imrprovements
* Fix header already send warning

= 1.2.1 =
* PHP fix
* New option to activated beta features (random selectors)

= 1.2 =
* Better performance: database requests widely reduced for stats counter
* Improve JS script for better performance
* CSS selectors and file names are randomly created and stored in the upload/an-temp dir.
* Fallback if scripts can not be stored into the upload dit. (print in page)
* Major PHP improvements & fix
* Major JS fix (ajax+checking methode)
* Dashboard widget improvements + tooltip
* TitanFramework option improvements
* Plugin meta added
* New strings + french translation
* Update readme.txt

= 1.1 =
* New option to enable or disable statistics+widget
* Minor PHP improvements & fix
* Minor JS fix (cookie)
* Update readme.txt

= 1.0 =
* First release
* Minor PHP improvements
* Update readme.txt

= 0.2 =
* Admin page style enhancement.
* Change the way Titan Framework is embeded
* Translatable ready (add French translation)
* Improve widget counter function
* Some minore php fixing.

= 0.1 =
* First stable version.


== Upgrade Notice ==
= 1.3 =
* If you use beta option to generate random slectors, don't forget to flush files after plugin update!

= 1.2 =
* Please deactivate then reactivate before using.
* Update the main option settings.

= 1.1 =
* Please deactivate then reactivate if admin title is missing.
* Update the main option settings.

= 0.2 =
* Please deactivate then reactivate if admin title is missing.

= 0.1 =
* First stable version.