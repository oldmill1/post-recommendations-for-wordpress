=== Post Recommendations for WordPress ===
Contributors: ankurt
Tags: recommendations, post, jquery, ajax, images, thumbnails
Requires at least: 2.0.2
Tested up to: 3.2.1
Stable tag: 1.1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

jQuery-powered recommendations.

== Description ==

**Please update to version 1.1** to fix broken AJAX issues with logged-out users.

Use as a short code: 

`[recommend_posts_for_me]`

Or as a template tag (in your theme files): 

`$args = array(); 
wp_recommendations($args);` 

That will create a jQuery-powered recommendations widget, wherever you want!

For more info, including options, visit the project's <a href="http://oldmill1.github.com/post-recommendations-for-wordpress/">Github page</a>. 

For example, you may do

[recommend_posts_for_me size=200,200]

== Installation ==

Put the unzipped folder into the Plugins directory and activate the plugin. Then, use [recommend_posts_for_me] in or the PHP template tag. 

== Changelog ==

= 1.1 =
* There was a bug in 1.0 that only let logged in viewers view the recommendations. This bug has now been fixed to let non-prillaged users also view recommendations via AJAX. 