=== WP Premise Box ===
Tags: studiopress, premise, marketing, commission, box, rounded, image
Requires at least: 3.5
Tested up to: 3.7.1
Contributors: jp2112
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7EX9NB9TLFHVW
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display the Premise affiliate marketing box on your website using shortcodes or PHP.

== Description ==

WP Premise Box is a plugin for WordPress that helps you create membership sites, sell digital downloads and make landing pages that will help increase sales. Copyblogger Media (the company that makes Premise) affiliates can earn commission on every referral. This content box displays marketing text and logo that can help drive referrals through your website.

= Features =

- Display your affiliate link anywhere
- Works with most browsers, but degrades nicely in older browsers
- CSS only loads on pages with shortcode or function call
- Multiple images available for inclusion
- Links can be opened in new window
- Includes standard marketing language, or use your own
- Automatically insert the Premise box after each post

= Shortcode =

To display on any post or page, use this shortcode:

[wp-premise-box]

Make sure you go to the plugin settings page after installing to set options.

<strong>If you use and enjoy this plugin, please rate it and click the "Works" button below so others know that it works with the latest version of WordPress.</strong>

== Installation ==

1. Upload the plugin through the WordPress interface.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Settings &raquo; WP Premise Box, configure the plugin
4. Insert shortcode on posts or pages, or use PHP function.

To remove this plugin, go to the 'Plugins' menu in WordPress, find the plugin in the listing and click "Deactivate". After the page refreshes, find the plugin again in the listing and click "Delete".

== Frequently Asked Questions ==

= What are the plugin defaults? =

The plugin arguments and default values may change over time. To get the latest list of arguments and defaults, look at the settings page after installing the plugin.

= How do I use the plugin? =

You must have an affiliate account with <a href="http://www.studiopress.com/affiliates">Studiopress</a>, and a URL that you would use to refer visitors to purchase Premise.

After going to Settings &raquo; WP Premise Box and inserting your affiliate link, use a shortcode to call the plugin from any page or post like this:

[wp-premise-box]

You can also use the following function in your PHP code (functions.php, or a plugin):

`echo premise_aff_box();`

You can also use this:

`do_shortcode('[wp-premise-box]');`

<strong>You must define the URL to be displayed</strong>. If you do not set the URL in the plugin's settings page, or when you call the shortcode/function, the plugin won't do anything.</strong> 

You may also use shortcodes within the shortcode, ex:

`[wp-premise-box][my_shortcode][/wp-premise-box]`

And you can specify your own text to be displayed, if you do not want the default text, ex:

`[wp-premise-box affurl="my premise affiliate link"]Click here to purchase Premise[/wp-premise-box]`

or

`if (function_exists('premise_aff_box') {
  premise_aff_box(array('show' => true, 'affurl' => 'my premise affiliate link'), 'Click here to buy Premise');
}`

= Examples =

You want to display the Premise Box at the end of your blog posts, as many affiliates do. Here is <a href="http://digwp.com/2010/04/wordpress-custom-functions-php-template-part-2/">one possible snippet</a>:

`add_filter('the_content', 'include_premise_box');
function include_premise_box($content) {
  if (is_single()) { // it's a single post
    // append Premise box after content
    if (function_exists('premise_aff_box') {
      $content .= premise_aff_box(); // assume affiliate URL is on plugin settings page
    }
  }
  return $content;
}`

Always wrap plugin function calls with a `function_exists` check so that your site doesn't go down if the plugin isn't active.

For Genesis framework users, use the <a href="http://my.studiopress.com/docs/hook-reference/">genesis_after_post_content</a> hook:

`add_action('genesis_after_post_content', 'include_premise_box');
function include_premise_box() {
  if (is_single()) {
    if (function_exists('premise_aff_box') {
      echo premise_aff_box(); // or: premise_aff_box(array('show' => true, 'affurl' => 'my premise affiliate link'), 'Click here to buy Premise');
    }
  }
}`

This will echo the Premise box after the post content on each post.

Or you can simply check the "Auto insert Premise box" checkbox on the plugin settings page and not have to use the shortcode or call the function.

= I want to use the plugin in a widget. How? =

Add this line of code to your functions.php:

`add_filter('widget_text', 'do_shortcode');`

Or install a plugin to do it for you: http://blogs.wcnickerson.ca/wordpress/plugins/widgetshortcodes/

Now, add the built-in text widget that comes with WordPress, and insert the shortcode into the text widget. See above for how to use the shortcode.

See http://digwp.com/2010/03/shortcodes-in-widgets/ for a detailed example.

<strong>Important: If using a widget in the sidebar, make sure you choose one of the smaller images so that it will fit.</strong>

= I don't want the buttons on my post editor toolbar. How do I remove them? =

Add this to your functions.php:

`remove_action('admin_print_footer_scripts', 'add_wppb_quicktag');`

= I inserted the shortcode but don't see anything on the page. =

Clear your browser cache and also clear your cache plugin (if any). If you still don't see anything, check your webpage source for the following:

`<!-- WP Premise Box: plugin is disabled. Check Settings page. -->`

This means you didn't pass a necessary setting to the plugin, so it disabled itself. You need to pass at least the affiliate URL, either by entering it on the settings page or passing it to the plugin in the shortcode or PHP function. You should also check that the "enabled" checkbox on the plugin settings page is checked. If that box is unchecked, the plugin will be disabled even if you pass the affiliate URL.

= I cleared my browser cache and my caching plugin but the output still looks wrong. =

Are you using a plugin that minifies CSS? If so, try excluding the plugin CSS file from minification.

= I cleared my cache and still don't see what I want. =

The CSS files include a `?ver` query parameter. This parameter is incremented with every upgrade in order to bust caches. Make sure none of your plugins or functions are stripping this query parameter. Also, if you are using a CDN, flush it or send an invalidation request for the plugin CSS files so that the edge servers request a new copy of it.

= I don't want the admin CSS. How do I remove it? =

Add this to your functions.php:

`remove_action('admin_head', 'insert_wppb_admin_css');`

= I don't want the plugin CSS. How do I remove it? =

Add this to your functions.php:

`add_action('wp_enqueue_scripts', 'remove_wppb_style');
function remove_wppb_style() {
  wp_deregister_style('wp_premise_box_style');
}`

= I want to use my own text instead of the text output by the plugin. =

If you are using the shortcode, do this:

`[wp-premise-box]Your content here[/wp-premise-box]`

The text output by the plugin will be overriden by whatever you type inbetween the shortcode tags.

If you are using the PHP function, do this:

`premise_aff_box(array('show' => true, 'affurl' => 'my premise affiliate url'), 'Click <a href="my link">here</a> to buy Premise');`

The second argument of the function is the content you want to use. You can use HTML tags and shortcodes in this string.

= I don't see the plugin toolbar button(s). =

This plugin adds one or more toolbar buttons to the HTML editor. You will not see them on the Visual editor.

The label on the toolbar button is "Premise Box".

== Screenshots ==

1. Settings page
2. Example output

== Changelog ==

= 0.0.8 =
- refactored admin CSS
- added helpful links on plugin settings page and plugins page

= 0.0.7 =
fixed uninstall routine, actually deletes options now

= 0.0.6 =
- updated the plugin settings page list of parameters to indicate whether they are required or not
- updated FAQ section of readme.txt

= 0.0.5 =
some security hardening added

= 0.0.4 =
minor admin code update

= 0.0.3 =
- target="_blank" is deprecated, replaced with javascript fallback

= 0.0.2 =
- minor code refactoring

= 0.0.1 =
created

== Upgrade Notice ==

= 0.0.8 =
- refactored admin CSS
- added helpful links on plugin settings page and plugins page

= 0.0.7 =
fixed uninstall routine, actually deletes options now

= 0.0.6 =
- updated the plugin settings page list of parameters to indicate whether they are required or not
- updated FAQ section of readme.txt

= 0.0.5 =
some security hardening added

= 0.0.4 =
minor admin code update

= 0.0.3 =
- target="_blank" is deprecated, replaced with javascript fallback

= 0.0.2 =
- minor code refactoring

= 0.0.1 =
created