<?php
/*
Plugin Name: WP Premise Box
Plugin URI: http://www.jimmyscode.com/wordpress/wp-premise-box/
Description: Display the Premise affiliate box on your WordPress website. Make money as a Premise affiliate.
Version: 0.0.8
Author: Jimmy Pe&ntilde;a
Author URI: http://www.jimmyscode.com/
License: GPLv2 or later
*/
// plugin constants
define('WPPB_VERSION', '0.0.8');
define('WPPB_PLUGIN_NAME', 'WP Premise Box');
define('WPPB_SLUG', 'wp-premise-box');
define('WPPB_OPTION', 'wp_premise_box');
define('WPPB_LOCAL', 'wp_premise_box');
/* defaults */
define('WPPB_DEFAULT_ENABLED', true);
define('WPPB_DEFAULT_URL', '');
define('WPPB_ROUNDED', false);
define('WPPB_NOFOLLOW', true);
define('WPPB_AVAILABLE_IMAGES', '125x125_premise,250x250_premise,260x125_premise,300x250_premise');
define('WPPB_DEFAULT_IMAGE', '');
define('WPPB_DEFAULT_AUTO_INSERT', false);
define('WPPB_DEFAULT_SHOW', false);
define('WPPB_DEFAULT_NEWWINDOW', false);
/* default option names */
define('WPPB_DEFAULT_ENABLED_NAME', 'enabled');
define('WPPB_DEFAULT_URL_NAME', 'affurl');
define('WPPB_DEFAULT_ROUNDED_NAME', 'rounded');
define('WPPB_DEFAULT_NOFOLLOW_NAME', 'nofollow');
define('WPPB_DEFAULT_IMAGE_NAME', 'img');
define('WPPB_DEFAULT_AUTO_INSERT_NAME', 'autoinsert');
define('WPPB_DEFAULT_SHOW_NAME', 'show');
define('WPPB_DEFAULT_NEWWINDOW_NAME', 'opennewwindow');

// oh no you don't
if (!defined('ABSPATH')) {
  wp_die(__('Do not access this file directly.', WPPB_LOCAL));
}

// delete option when plugin is uninstalled
register_uninstall_hook(__FILE__, 'uninstall_wppb_plugin');
function uninstall_wppb_plugin() {
  delete_option(WPPB_OPTION);
}

// localization to allow for translations
add_action('init', 'wp_premise_box_translation_file');
function wp_premise_box_translation_file() {
  $plugin_path = plugin_basename(dirname(__FILE__)) . '/translations';
  load_plugin_textdomain(WPPB_LOCAL, '', $plugin_path);
  register_wp_premise_box_style();
}
// tell WP that we are going to use new options
add_action('admin_init', 'wp_premise_box_options_init');
function wp_premise_box_options_init() {
  register_setting('wp_premise_box_options', WPPB_OPTION, 'wppb_validation');
  register_wppb_admin_style();
	register_wppb_admin_script();
}
// validation function
function wppb_validation($input) {
  // sanitize url
  $input[WPPB_DEFAULT_URL_NAME] = esc_url($input[WPPB_DEFAULT_URL_NAME]);
  // sanitize image
  $input[WPPB_DEFAULT_IMAGE_NAME] = sanitize_text_field($input[WPPB_DEFAULT_IMAGE_NAME]);
  if (!$input[WPPB_DEFAULT_IMAGE_NAME]) { // set to default
    $input[WPPB_DEFAULT_IMAGE_NAME] = WPPB_DEFAULT_IMAGE;
  }
  return $input;
}
// add Settings sub-menu
add_action('admin_menu', 'wppb_plugin_menu');
function wppb_plugin_menu() {
  add_options_page(WPPB_PLUGIN_NAME, WPPB_PLUGIN_NAME, 'manage_options', WPPB_SLUG, 'wp_premise_box_page');
}
// plugin settings page
// http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/
function wp_premise_box_page() {
  // check perms
  if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permission to access this page', WPPB_LOCAL));
  }
?>
  <div class="wrap">
    <?php screen_icon(); ?>
    <h2><?php echo WPPB_PLUGIN_NAME; ?></h2>
    <form method="post" action="options.php">
      <div>You are running plugin version <strong><?php echo WPPB_VERSION; ?></strong>.</div>
      <?php settings_fields('wp_premise_box_options'); ?>
      <?php $options = wppb_getpluginoptions(); ?>
	<?php /* update_option(WPPB_OPTION, $options); */ ?>
      <table class="form-table" id="theme-options-wrap">
        <tr valign="top"><th scope="row"><strong><label title="<?php _e('Is plugin enabled? Uncheck this to turn it off temporarily.', WPPB_LOCAL); ?>" for="wp_premise_box[<?php echo WPPB_DEFAULT_ENABLED_NAME; ?>]"><?php _e('Plugin enabled?', WPPB_LOCAL); ?></label></strong></th>
		<td><input type="checkbox" id="wp_premise_box[<?php echo WPPB_DEFAULT_ENABLED_NAME; ?>]" name="wp_premise_box[<?php echo WPPB_DEFAULT_ENABLED_NAME; ?>]" value="1" <?php checked('1', $options[WPPB_DEFAULT_ENABLED_NAME]); ?> /></td>
        </tr>
	  <tr valign="top"><td colspan="2"><?php _e('Is plugin enabled? Uncheck this to turn it off temporarily.', WPPB_LOCAL); ?></td></tr>
        <tr valign="top"><th scope="row"><strong><label title="<?php _e('Enter your affiliate URL here. This will be inserted wherever you use the shortcode.', WPPB_LOCAL); ?>" for="wp_premise_box[<?php echo WPPB_DEFAULT_URL_NAME; ?>]"><?php _e('Your Affiliate URL', WPPB_LOCAL); ?></label></strong></th>
          <td><input type="url" id="wp_premise_box[<?php echo WPPB_DEFAULT_URL_NAME; ?>]" name="wp_premise_box[<?php echo WPPB_DEFAULT_URL_NAME; ?>]" value="<?php echo $options[WPPB_DEFAULT_URL_NAME]; ?>" /></td>
        </tr>
        <tr valign="top"><td colspan="2"><?php _e('Enter your affiliate URL here. This will be inserted wherever you use the shortcode.', WPPB_LOCAL); ?></td></tr>
        <tr valign="top"><th scope="row"><strong><label title="<?php _e('Do you want to apply rounded corners CSS to the output?', WPPB_LOCAL); ?>" for="wp_premise_box[<?php echo WPPB_DEFAULT_ROUNDED_NAME; ?>]"><?php _e('Rounded corners CSS?', WPPB_LOCAL); ?></label></strong></th>
		<td><input type="checkbox" id="wp_premise_box[<?php echo WPPB_DEFAULT_ROUNDED_NAME; ?>]" name="wp_premise_box[<?php echo WPPB_DEFAULT_ROUNDED_NAME; ?>]" value="1" <?php checked('1', $options[WPPB_DEFAULT_ROUNDED_NAME]); ?> /></td>
        </tr>
	  <tr valign="top"><td colspan="2"><?php _e('Do you want to apply rounded corners CSS to the output?', WPPB_LOCAL); ?></td></tr>
        <tr valign="top"><th scope="row"><strong><label title="<?php _e('Check this box to automatically insert the output at the end of blog posts. If you do not do this then you will need to manually insert shortcode or call the function in PHP.', WPPB_LOCAL); ?>" for="wp_premise_box[<?php echo WPPB_DEFAULT_AUTO_INSERT_NAME; ?>]"><?php _e('Auto insert Premise box at the end of posts?', WPPB_LOCAL); ?></label></strong></th>
		<td><input type="checkbox" id="wp_premise_box[<?php echo WPPB_DEFAULT_AUTO_INSERT_NAME; ?>]" name="wp_premise_box[<?php echo WPPB_DEFAULT_AUTO_INSERT_NAME; ?>]" value="1" <?php checked('1', $options[WPPB_DEFAULT_AUTO_INSERT_NAME]); ?> /></td>
        </tr>
	  <tr valign="top"><td colspan="2"><?php _e('Check this box to automatically insert the output at the end of blog posts. If you don\'t do this then you will need to manually insert shortcode or call the function in PHP.', WPPB_LOCAL); ?></td></tr>
        <tr valign="top"><th scope="row"><strong><label title="<?php _e('Do you want to add rel=nofollow to all links?', WPPB_LOCAL); ?>" for="wp_premise_box[<?php echo WPPB_DEFAULT_NOFOLLOW_NAME; ?>]"><?php _e('Nofollow links?', WPPB_LOCAL); ?></label></strong></th>
		<td><input type="checkbox" id="wp_premise_box[<?php echo WPPB_DEFAULT_NOFOLLOW_NAME; ?>]" name="wp_premise_box[<?php echo WPPB_DEFAULT_NOFOLLOW_NAME; ?>]" value="1" <?php checked('1', $options[WPPB_DEFAULT_NOFOLLOW_NAME]); ?> /></td>
        </tr>
	  <tr valign="top"><td colspan="2"><?php _e('Do you want to add rel="nofollow" to all links?', WPPB_LOCAL); ?></td></tr>
        <tr valign="top"><th scope="row"><strong><label title="<?php _e('Check this box to open links in a new window.', WPPB_LOCAL); ?>" for="wp_premise_box[<?php echo WPPB_DEFAULT_NEWWINDOW_NAME; ?>]"><?php _e('Open links in new window?', WPPB_LOCAL); ?></label></strong></th>
		<td><input type="checkbox" id="wp_premise_box[<?php echo WPPB_DEFAULT_NEWWINDOW_NAME; ?>]" name="wp_premise_box[<?php echo WPPB_DEFAULT_NEWWINDOW_NAME; ?>]" value="1" <?php checked('1', $options[WPPB_DEFAULT_NEWWINDOW_NAME]); ?> /></td>
        </tr>
	  <tr valign="top"><td colspan="2"><?php _e('Check this box to open links in a new window.', WPPB_LOCAL); ?></td></tr>
        <tr valign="top"><th scope="row"><strong><label title="<?php _e('Select the default image.', WPPB_LOCAL); ?>" for="wp_premise_box[<?php echo WPPB_DEFAULT_IMAGE_NAME; ?>]"><?php _e('Default image', WPPB_LOCAL); ?></label></strong></th>
		<td><select id="wp_premise_box[<?php echo WPPB_DEFAULT_IMAGE_NAME; ?>]" name="wp_premise_box[<?php echo WPPB_DEFAULT_IMAGE_NAME; ?>]" onChange="picture.src=this.options[this.selectedIndex].getAttribute('data-whichPicture');">
                <?php $images = explode(",", WPPB_AVAILABLE_IMAGES);
                      for($i=0, $imagecount=count($images); $i < $imagecount; $i++) {
                        $imageurl = plugins_url(plugin_basename(dirname(__FILE__) . '/images/' . $images[$i] . '.png'));
                        if ($images[$i] === $options[WPPB_DEFAULT_IMAGE_NAME]) { $selectedimage = $imageurl; }
                        echo '<option data-whichPicture="' . $imageurl . '" value="' . $images[$i] . '" ' . selected($images[$i], $options[WPPB_DEFAULT_IMAGE_NAME]) . '>' . $images[$i] . '</option>';
                      } ?>
            </select></td></tr>
        <tr><td colspan="2"><img src="<?php if (!$selectedimage) { echo plugins_url(plugin_basename(dirname(__FILE__) . '/images/' . WPPB_DEFAULT_IMAGE . '.png')); } else { echo $selectedimage; } ?>" id="picture" /></td></tr>
	  <tr valign="top"><td colspan="2"><?php _e('Select the default image.', WPPB_LOCAL); ?></td></tr>
      </table>
      <?php submit_button(); ?>
    </form>
    <h3>Plugin Arguments and Defaults</h3>
    <table class="widefat">
      <thead>
        <tr>
          <th title="<?php _e('The name of the parameter', WPPB_LOCAL); ?>"><?php _e('Argument', WPPB_LOCAL); ?></th>
	  <th title="<?php _e('Is this parameter required?', WPPB_LOCAL); ?>"><?php _e('Required?', WPPB_LOCAL); ?></th>
          <th title="<?php _e('What data type this parameter accepts', WPPB_LOCAL); ?>"><?php _e('Type', WPPB_LOCAL); ?></th>
          <th title="<?php _e('What, if any, is the default if no value is specified', WPPB_LOCAL); ?>"><?php _e('Default Value', WPPB_LOCAL); ?></th>
        </tr>
      </thead>
      <tbody>
    <?php $plugin_defaults_keys = array_keys(wppb_shortcode_defaults());
					$plugin_defaults_values = array_values(wppb_shortcode_defaults());
					$wppb_required = wppb_required_parameters();
					for($i=0; $i<count($plugin_defaults_keys);$i++) { ?>
        <tr>
          <td><?php echo $plugin_defaults_keys[$i]; ?></td>
					<td><?php echo $wppb_required[$i]; ?></td>
          <td><?php echo gettype($plugin_defaults_values[$i]); ?></td>
          <td><?php 
						if ($plugin_defaults_values[$i] === true) {
							echo 'true';
						} elseif ($plugin_defaults_values[$i] === false) {
							echo 'false';
						} elseif ($plugin_defaults_values[$i] === '') {
							echo '<em>(this value is blank by default)</em>';
						} else {
							echo $plugin_defaults_values[$i];
						} ?></td>
        </tr>
    <?php } ?>
    </tbody>
    </table>
    <?php screen_icon('edit-comments'); ?>
    <h3>Support</h3>
	<div class="support">
		<?php echo '<a href="http://wordpress.org/extend/plugins/' . WPPB_SLUG . '/">' . __('Documentation', WPPB_LOCAL) . '</a> | ';
        echo '<a href="http://wordpress.org/plugins/' . WPPB_SLUG . '/faq/">' . __('FAQ', WPPB_LOCAL) . '</a><br />';
			?>
      If you like this plugin, please <a href="http://wordpress.org/support/view/plugin-reviews/<?php echo WPPB_SLUG; ?>/">rate it on WordPress.org</a> and click the "Works" button so others know it will work for your WordPress version. For support please visit the <a href="http://wordpress.org/support/plugin/<?php echo WPPB_SLUG; ?>">forums</a>. <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7EX9NB9TLFHVW"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" alt="Donate with PayPal" title="Donate with PayPal" width="92" height="26" /></a>
    </div>
  </div>
  <?php 
}
// shortcode for posts and pages
add_shortcode('wp-premise-box', 'premise_aff_box');
// one function for shortcode and PHP
function premise_aff_box($atts, $content = null) {
  // get parameters
  extract(shortcode_atts(wppb_shortcode_defaults(), $atts));
  // plugin is enabled/disabled from settings page only
  $options = wppb_getpluginoptions();
  $enabled = $options[WPPB_DEFAULT_ENABLED_NAME];

  // ******************************
  // derive shortcode values from constants
  // ******************************
  $temp_url = constant('WPPB_DEFAULT_URL_NAME');
  $affiliate_url = $$temp_url;
  $temp_nofollow = constant('WPPB_DEFAULT_NOFOLLOW_NAME');
  $nofollow = $$temp_nofollow;
  $temp_window = constant('WPPB_DEFAULT_NEWWINDOW_NAME');
  $opennewwindow = $$temp_window;
  $temp_show = constant('WPPB_DEFAULT_SHOW_NAME');
  $show = $$temp_show;
  $temp_rounded = constant('WPPB_DEFAULT_ROUNDED_NAME');
  $rounded = $$temp_rounded;
  $temp_image = constant('WPPB_DEFAULT_IMAGE_NAME');
  $img = $$temp_image;

  // ******************************
  // sanitize user input
  // ******************************
  $affiliate_url = esc_url($affiliate_url);
  $rounded = (bool)$rounded;
  $nofollow = (bool)$nofollow;
  $opennewwindow = (bool)$opennewwindow;
  $show = (bool)$show;
  $img = sanitize_text_field($img);

  // ******************************
  // check for parameters, then settings, then defaults
  // ******************************
  if ($enabled) {
    // check for overridden parameters, if nonexistent then get from DB
    if ($affiliate_url === WPPB_DEFAULT_URL) { // no url passed to function, try settings page
      $affiliate_url = $options[WPPB_DEFAULT_URL_NAME];
      if (($affiliate_url === WPPB_DEFAULT_URL) || ($affiliate_url === false)) { // no url on settings page either
        $enabled = false;
      }
    }
    if ($rounded == WPPB_ROUNDED) {
      $rounded = $options[WPPB_DEFAULT_ROUNDED_NAME];
      if ($rounded === false) {
        $rounded = WPPB_ROUNDED;
      }
    }
    if ($nofollow == WPPB_NOFOLLOW) {
	$nofollow = $options[WPPB_DEFAULT_NOFOLLOW_NAME];
	if ($nofollow === false) {
	  $nofollow = WPPB_NOFOLLOW;
	}
    }
    if ($img == WPPB_DEFAULT_IMAGE) {
      $img = $options[WPPB_DEFAULT_IMAGE_NAME];
      if ($img === false) {
        $img = WPPB_DEFAULT_IMAGE;
      }
    }
    if ($opennewwindow == WPPB_DEFAULT_NEWWINDOW) {
      $opennewwindow = $options[WPPB_DEFAULT_NEWWINDOW_NAME];
      if ($opennewwindow === false) {
        $opennewwindow = WPPB_DEFAULT_NEWWINDOW;
      }
    }
  } // end enabled check

  // ******************************
  // do some actual work
  // ******************************
  if ($enabled) {
    // enqueue CSS only on pages with shortcode
    wp_premise_box_styles();

    if ($content) {
      $text = wp_kses_post(force_balance_tags($content));
    } else {
      $text = '<p><a' . ($opennewwindow ? ' onclick="window.open(this.href); return false;" onkeypress="window.open(this.href); return false;" ' : ' ') . ($nofollow ? ' rel="nofollow" ' : ' ') . 'href="' . $affiliate_url . '">Premise</a> ';
      $text .= __('is the complete digital sales and lead generation engine for WordPress. It empowers you to quickly and easily build custom, graphically-enhanced landing pages without cost, code or hassle.', WPPB_LOCAL);
      $text .= __(' Copywriting advice is delivered directly into WordPress for each landing page type.', WPPB_LOCAL) . '</p>';
      $text .= '<p><a' . ($opennewwindow ? ' onclick="window.open(this.href); return false;" onkeypress="window.open(this.href); return false;" ' : ' ') . ($nofollow ? ' rel="nofollow" ' : ' ') . 'href="' . $affiliate_url . '">Premise</a> ';
      $text .= __('makes conversion and search engine optimization a snap, so you can get back to focusing on your business.', WPPB_LOCAL);
      $text .= __(' Plus, the included conversion optimization, copywriting and strategy seminars keep you up to speed.', WPPB_LOCAL) . '</p>';
      $text .= '<p>' . __('Create Better Pages, Write Better Copy, Get Better Results. ', WPPB_LOCAL);
      $text .= '<a' . ($opennewwindow ? ' onclick="window.open(this.href); return false;" onkeypress="window.open(this.href); return false;" ' : ' ') . ($nofollow ? ' rel="nofollow" ' : ' ') . 'href="' . $affiliate_url . '">' . __('See what Premise can do!', WPPB_LOCAL) . '</a></p>';
    }
    // calculate image url
    $images = explode(",", WPPB_AVAILABLE_IMAGES);
    if (!in_array($img, $images)) {
      $img = $images[$options[WPPB_DEFAULT_IMAGE_NAME]];
      if (!$img) { $img = WPPB_DEFAULT_IMAGE; }
    }
    $imageurl = plugins_url(plugin_basename(dirname(__FILE__) . '/images/' . $img . '.png'));
    $imagedata = getimagesize($imageurl);
    $output = '<div id="premise-box"' . ($rounded ? ' class="wppb-rounded-corners"' : '') . '>';
    $output .= '<h3>' . __('Point-and-Click Membership Sites and Landing Pages with Premise for WordPress', WPPB_LOCAL) . '</h3>';
    $output .= '<a' . ($opennewwindow ? ' onclick="window.open(this.href); return false;" onkeypress="window.open(this.href); return false;" ' : ' ') . ($nofollow ? ' rel="nofollow" ' : ' ') . 'href="' . $affiliate_url . '">';
    $output .= '<img class="alignright" src="' . $imageurl . '" alt="' . __('Premise', WPPB_LOCAL) . '" title="' . __('Premise', WPPB_LOCAL) . '" width="' . $imagedata[0] . '" height="' . $imagedata[1] . '" /></a>';
    $output .= do_shortcode($text) . '</div>';
  } else { // plugin disabled
    $output = '<!-- ' . WPPB_PLUGIN_NAME . ': plugin is disabled. Either you did not pass a necessary setting to the plugin, or did not configure a default. Check Settings page. -->';
  }
  if ($enabled) {
    if ($show) {
      echo $output;
    } else {
      return $output;
    }
  }
} // end shortcode function
// auto insert at end of posts?
add_action('the_content', 'wppb_insert_premise_box');
function wppb_insert_premise_box($content) {
  if (is_single()) {
    $options = wppb_getpluginoptions();
    if ($options[WPPB_DEFAULT_AUTO_INSERT_NAME]) {
      $content .= premise_aff_box($options);
    }
  }
  return $content;
}
// show admin messages to plugin user
add_action('admin_notices', 'wppb_showAdminMessages');
function wppb_showAdminMessages() {
  // http://wptheming.com/2011/08/admin-notices-in-wordpress/
  global $pagenow;
  if (current_user_can('manage_options')) { // user has privilege
    if ($pagenow == 'options-general.php') {
			if ($_GET['page'] == WPPB_SLUG) { // on WP Premise Box settings page
        $options = wppb_getpluginoptions();
				if ($options != false) {
					$enabled = $options[WPPB_DEFAULT_ENABLED_NAME];
					$affiliate_url = $options[WPPB_DEFAULT_URL_NAME];
					if (!$enabled) {
						echo '<div id="message" class="error">' . WPPB_PLUGIN_NAME . ' ' . __('is currently disabled.', WPPB_LOCAL) . '</div>';
					}
					if (($affiliate_url === WPPB_DEFAULT_URL) || ($affiliate_url === false)) {
						echo '<div id="message" class="updated">' . __('WARNING: Affiliate URL missing. Please enter it below, or pass it to the shortcode or function, otherwise the plugin won\'t do anything.', WPPB_LOCAL) . '</div>';
					}
        }
			}
    } // end page check
  } // end privilege check
} // end admin msgs function
// add admin CSS if we are on the plugin options page
add_action('admin_head', 'insert_wppb_admin_css');
function insert_wppb_admin_css() {
  global $pagenow;
  if (current_user_can('manage_options')) { // user has privilege
    if ($pagenow == 'options-general.php') {
      if ($_GET['page'] == WPPB_SLUG) { // we are on settings page
        wppb_admin_styles();
      }
    }
  }
}
// http://bavotasan.com/2009/a-settings-link-for-your-wordpress-plugins/
// Add settings link on plugin page
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wp_premise_box_plugin_settings_link' );
function wp_premise_box_plugin_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=wp-premise-box">' . __('Settings', WPPB_LOCAL) . '</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
// http://wpengineer.com/1295/meta-links-for-wordpress-plugins/
add_filter('plugin_row_meta', 'wppb_meta_links', 10, 2);
function wppb_meta_links($links, $file) {
  $plugin = plugin_basename(__FILE__);
  // create link
  if ($file == $plugin) {
    $links = array_merge($links,
      array(
        '<a href="http://wordpress.org/support/plugin/' . WPPB_SLUG . '">' . __('Support', WPPB_LOCAL) . '</a>',
        '<a href="http://wordpress.org/extend/plugins/' . WPPB_SLUG . '/">' . __('Documentation', WPPB_LOCAL) . '</a>',
        '<a href="http://wordpress.org/plugins/' . WPPB_SLUG . '/faq/">' . __('FAQ', WPPB_LOCAL) . '</a>'
    ));
  }
  return $links;
}
// enqueue/register the admin CSS file
function wppb_admin_styles() {
  wp_enqueue_style('wppb_admin_style');
}
function register_wppb_admin_style() {
  wp_register_style( 'wppb_admin_style',
    plugins_url(plugin_basename(dirname(__FILE__)) . '/css/admin.css'),
    array(),
    WPPB_VERSION,
    'all' );
}
// enqueue/register the plugin CSS file
function wp_premise_box_styles() {
  wp_enqueue_style('wp_premise_box_style');
}
function register_wp_premise_box_style() {
  wp_register_style('wp_premise_box_style', 
    plugins_url(plugin_basename(dirname(__FILE__)) . '/css/wp-premise-box.css'), 
    array(), 
    WPPB_VERSION, 
    'all' );
}
// enqueue/register the admin JS file
add_action('admin_enqueue_scripts', 'wppb_ed_buttons');
function wppb_ed_buttons($hook) {
  if (($hook == 'post-new.php') || ($hook == 'post.php')) {
    wp_enqueue_script('wppb_add_editor_button');
  }
}
function register_wppb_admin_script() {
  wp_register_script('wppb_add_editor_button',
    plugins_url(plugin_basename(dirname(__FILE__)) . '/js/editor_button.js'), 
    array('quicktags'), 
    WPPB_VERSION, 
    true);
}
// when plugin is activated, create options array and populate with defaults
register_activation_hook(__FILE__, 'wppb_activate');
function wppb_activate() {
  $options = wppb_getpluginoptions();
  update_option(WPPB_OPTION, $options);
}
// generic function that returns plugin options from DB
// if option does not exist, returns plugin defaults
function wppb_getpluginoptions() {
  return get_option(WPPB_OPTION, array(WPPB_DEFAULT_ENABLED_NAME => WPPB_DEFAULT_ENABLED, WPPB_DEFAULT_URL_NAME => WPPB_DEFAULT_URL, WPPB_DEFAULT_ROUNDED_NAME => WPPB_ROUNDED, WPPB_DEFAULT_NOFOLLOW_NAME => WPPB_NOFOLLOW, WPPB_DEFAULT_IMAGE_NAME => WPPB_DEFAULT_IMAGE, WPPB_DEFAULT_AUTO_INSERT_NAME => WPPB_DEFAULT_AUTO_INSERT, WPPB_DEFAULT_NEWWINDOW_NAME => WPPB_DEFAULT_NEWWINDOW));
}
// function to return shortcode defaults
function wppb_shortcode_defaults() {
  return array(
    WPPB_DEFAULT_URL_NAME => WPPB_DEFAULT_URL, 
    WPPB_DEFAULT_ROUNDED_NAME => WPPB_ROUNDED, 
    WPPB_DEFAULT_NOFOLLOW_NAME => WPPB_NOFOLLOW, 
    WPPB_DEFAULT_IMAGE_NAME => WPPB_DEFAULT_IMAGE, 
    WPPB_DEFAULT_NEWWINDOW_NAME => WPPB_DEFAULT_NEWWINDOW, 
    WPPB_DEFAULT_SHOW_NAME => WPPB_DEFAULT_SHOW
   );
}
// function to return parameter status (required or not)
function wppb_required_parameters() {
  return array(
    'true',
    'false',
    'false',
    'false',
    'false',
    'false'
  );
}
?>