=== Plugin Name ===

Contributors: Rolf van Gelder
Donate link: http://cagewebdev.com
Plugin Name: Optimize Database after Deleting Revisions
Plugin URI: http://cagewebdev.com/index.php/optimize-database-after-deleting-revisions-wordpress-plugin
Tags: database, delete, revisions, optimize, post, posts, page, pages, clean up, trash, spam, trashed, spammed
Author URI: http://cagewebdev.com
Author: Rolf van Gelder, Eindhoven, The Netherlands
Requires at least: 2.0
Tested up to: 3.4.2
Stable tag: 1.3.1
Version: 1.3.1

== Description ==

<p><b>This plugin is a 'one click' database optimizer.</b></p>
<p>It deletes the redundant revisions of posts and pages, trashed items and/or spammed items and, after that, optimizes all database tables.</p>
<p>http://cagewebdev.com/index.php/optimize-database-after-deleting-revisions-wordpress-plugin</p>
<p>Author: Rolf van Gelder, Eindhoven, The Netherlands - http://cagewebdev.com</p>

== Installation ==

<ol>
<li>Upload `rvg-optimize-db.php` to the `/wp-content/plugins/` directory</li>
<li>Activate the plugin through the 'Plugins' menu in the WordPress Dashboard</li>
<li>Change the settings (if needed) through WordPress Dashboard, Settings, Optimize DB Options</li>
</ol>

== Upgrade Notice ==

No Upgrade Notice available.

== Screenshots ==

No Screenshots available.

== Changelog ==

<p><b>1.0</b>   11/22/2011 Initial release</p>
<p><b>1.0.1</b> 11/24/2011 A few updates for the readme.txt file</p>
<p><b>1.0.2</b> 12/02/2011 Some minor updates</p>
<p><b>1.0.3</b> 12/15/2011 Some minor layout updates</p>
<p><b>1.0.4</b> 06/06/2012 Now also works with non short_open_tag's</p>
<p><b>1.0.5</b> 08/21/2012 Depreciated item ('has_cap') replaced, abandoned line of code removed</p>
<p><b>1.1</b>   08/29/2012 Added: a new option page, in de plugins section, where you can define the maximum number of - most recent - revisions you want to keep per post or page</p>
<p><b>1.1.2</b> 08/30/2012 Minor bug fix for the new option page</p>
<p><b>1.1.3</b> 09/01/2012 Moved the 'Optimize DB Options' item to Dashboard 'Settings' Menu and the 'Optimize Database' item to the Dashboard 'Tools' Menu. That makes more sense!</p>
<p><b>1.1.4</b> 09/01/2012 Something went wrong deploying 1.1.3, so I deployed it again as 1.1.4</p>
<p><b>1.1.5</b> 09/01/2012 Something went wrong deploying 1.1.4, so I deployed it again as 1.1.5. *sigh*</p>
<p><b>1.1.6</b> 09/01/2012 Fixed the link to the options page</p>
<p><b>1.1.7</b> 09/03/2012 Some textual and link fixes</p>
<p><b>1.1.8</b> 09/08/2012 Another link fix</p>
<p><b>1.1.9</b> 09/27/2012 Using a different method for retrieving database table names</p>
<p><b>1.2</b>   10/03/2012 Major update: new options 'delete trash', 'delete spam', 'only optimize WordPress tables'</p>
<p><b>1.3</b>   10/06/2012 Extra button for starting optimization, shows savings (in bytes) now</p>
<p><b>1.3.1</b> 10/07/2012 Minor changes</p>

== Frequently Asked Questions ==

<p><b>Q:</b> <em>How can I change the settings of this plugin?</em></p>
<p><b>A:</b> In the WordPress Dashboard go to '<b>Settings / Optimize DB Options</b>'. There you can define the maximum number of - most recent - revisions you want to keep per post or page and some more options.</p>
<p><b>Q:</b> <em>How do I run this plugin?</em></p>
<p><b>A:</b> In the WordPress Dashboard go to '<b>Tools</b>'. Click on '<b>Optimize Database</b>'. Then click the 'Start Optimization'-button. Et voila!</p>
<p><b>Q:</b> <em>Why do I see 'Table does not support optimize, doing recreate + analyze instead' while optimizing my database?</em></p>
<p><b>A:</b> That is because the table type of that table is not 'MyISAM'</p>
