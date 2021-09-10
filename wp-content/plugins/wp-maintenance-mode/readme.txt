=== WP Maintenance Mode ===
Contributors: Designmodo
Plugin Name: WP Maintenance Mode
Plugin URI: https://designmodo.com/
Author: Designmodo
Author URI: https://designmodo.com/
Tags: maintenance mode, admin, administration, unavailable, coming soon, multisite, landing page, under construction, contact form, subscribe, countdown
Requires at least: 3.5
Tested up to: 5.8
Stable tag: 2.4.1
Requires PHP: 5.6
License: GPL-2.0+

Adds a splash page to your site that lets visitors know your site is down for maintenance. It's perfect for a coming soon page. The new Bot functionality is here!

== Description ==

Add a maintenance page to your blog that lets visitors know your blog is down for maintenance, or add a coming soon page for a new website. User with admin rights gets full access to the blog including the front end.

Activate the plugin and your blog is in maintenance-mode, works and only registered users with enough rights can see the front end. You can use a date with a countdown timer for visitor information or set a value and unit for information. 

Also works with WordPress Multisite installs (each blog from the network has it's own maintenance settings).

= Features =

* Fully customizable (change colors, texts and backgrounds);
* Subscription form (export emails to .csv file);
* Countdown timer (remaining time);
* Contact form (receive emails from visitors);
* Coming soon page;
* Landing page templates;
* WordPress multisite;
* Responsive design;
* Social media icons;
* Works with any WordPress theme;
* SEO options;
* Exclude URLs from maintenance;
* Bot functionality to collect the emails in a friendly and efficient way;
* GDPR Ready;

= Bugs, technical hints or contribute =

Please give us feedback, contribute and file technical bugs on [GitHub Repo](https://github.com/andrianvaleanu/WP-Maintenance-Mode).

= Credits =

Developed by [Designmodo](https://designmodo.com)

== Installation ==

1. Unpack the download package
2. Upload all files to the `/wp-content/plugins/` directory, include folders
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Go to `Settings` page, where you can change what settings you need (pay attention to **Exclude** option!)

== Screenshots ==

1. Maintenance Mode Example
2. Maintenance Mode Example #2
3. Bot Example
4. Dashboard General Settings
5. Dashboard Design Settings
6. Dashboard Modules Settings
7. Dashboard Bot Settings
8. Contact Form

== Frequently Asked Questions ==

= How to use plugin filters = 
Check out our [Snippet Library](https://github.com/WP-Maintenance-Mode/Snippet-Library/).

= Cache Plugin Support =
WP Maintenance Mode can be unstable due to the cache plugins; we recommend deactivating any cache plugin when maintenance mode is active. If you **really** want to use a cache plugin, make sure you delete the entire cache after each change.

= Exclude list =
If you change your login url, please add the new slug (url: http://domain.com/newlogin, then you should add: newlogin) to Exclude list from plugin settings -> General Tab.

Notice: `wp-cron.php` is excluded by default.

== Changelog ==

= 2.4.1 (20/07/2021) =
* Misc: WordPress 5.8 compatibility

= 2.4.0 (13/05/2021) =
* Design: add "Custom CSS" setting; Finally! :)
* Design: add "Footer links" color setting
* Design: add a list of available shortcodes under the "Text" editor
* Bot: make {visitor_name} placeholder work in all messages after the visitor types his name
* Misc: add [embed] shortcode for responsive video embeds; Compatible with YouTube, Vimeo, DailyMotion.
* Misc: make the exclude mechanism work with Cyrillic characters
* Misc: add `wpmm_maintenance_template` filter; It works the same way as the `wpmm_contact_template` filter, but for the maintenance template.
* Misc: now you can override the `contact` email template too; Check `/views/contact.php` for more details.
* Misc: better compatibility with translation plugins like Loco Translate
* Misc: the image uploaders (from the dashboard) are now translatable
* Misc: improve uninstall routine
* Misc: add `wpmm_delete_cache` action; It is called after each setting change.
* Misc: add support for cache plugins like WP Rocket, WP Fastest Cache, Endurance Page Cache, Swift Performance Lite, Cache Enabler, SG Optimizer, LiteSpeed Cache, Nginx Helper;
* Misc: remove `wpmm_count_where` helper function
* Misc: code improvements

= 2.3.0 (07/12/2020) =
* Modules: add support for Google Analytics 4 measurement ID
* Design: enable media buttons on wp_editor (now you can add images from the editor)
* Bot: fix translation issue
* Misc: add filters for capabilities `wpmm_settings_capability`, `wpmm_subscribers_capability`, and `wpmm_all_actions_capability` (the last one can be used to override all capabilities)
* Misc: fix [loginform] shortcode redirect attribute
* Misc: a few CSS & Javascript improvements
* Misc: bump "Tested up to" version to 5.6

= 2.2.4 (20/05/2019) =
* bump "Tested up to" to 5.2.0
* fix typo in Italian translation (it_IT)
* Bot: add a note about how you can export the list of subscribers [#195](https://github.com/andrianvaleanu/WP-Maintenance-Mode/issues/195)
* Bot: add client-side sanitization to the input fields [#176](https://github.com/andrianvaleanu/WP-Maintenance-Mode/issues/176)

= 2.2.3 (20/02/2019) =
* bump "Tested up to" version to 5.1.0
* replace "wpmu_new_blog" action with "wp_initialize_site" action for WP 5.1.0 users because the first one is deprecated in the new version
* small improvement to "check_exclude" method from "WP_Maintenance_Mode" class

= 2.2.2 (27/11/2018) =
* Google Analytics module: migrate from analytics.js to gtag.js + add ip anonymization [#178](https://github.com/andrianvaleanu/WP-Maintenance-Mode/issues/178)
* GDPR module: accept links inside texareas + add policy link target [#188](https://github.com/andrianvaleanu/WP-Maintenance-Mode/issues/188)
* add charset meta tag [#200](https://github.com/andrianvaleanu/WP-Maintenance-Mode/issues/200)
* fix PHP Notice:  Undefined index: HTTP_USER_AGENT
* add plural and single form translation for subscribers number (settings page)

= Earlier versions =
For the changelog of earlier versions, please refer to the [full changelog](http://plugins.svn.wordpress.org/wp-maintenance-mode/trunk/changelog.txt).
