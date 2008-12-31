<?php
/**
 * @package Pathless_Category_Links
 * @author Another Coder
 * @version 1.0
 */
/*
Plugin Name: Pathless Category Links
Plugin URI: http://www.anothercoder.com/wordpress/pathless-category-permalinks-plugin
Description: Updates category links to remove the /category/ or any other folder you specify, putting your category links in the root directory, e.g. http://www.anothercoder.org/category_name
Author: Another Coder
Version: 1.0
Author URI: http://www.anothercoder.com/
*/

/**
 * Retrieve category link URL without /category/
 *
 * @since 1.0
 *
 * @param string $cat_link Category link.
 * @return string
 */
if ( !function_exists('ac_pathless_category_links') ) :
function ac_pathless_category_links( $cat_link ) {
	// Only replace the /category/ portion, we don't want to replace custom category structure names
	$cat_link = str_ireplace(get_option("settings_basedir"), "/", $cat_link);

	// return the new category link
	return $cat_link;
}
endif;

/**
 * Checks if the requested URL has a /category/ path and performs a 301 redirect
 *
 * @since 1.0
 *
 */
if ( !function_exists('ac_pathless_category_links_init') ) :
function ac_pathless_category_links_init() {
	// Check if we need to do a 301 redirect
	if(get_option("settings_redirect") == "1" && preg_match("/" . str_replace("/", "\/", get_option("settings_basedir")) . "/", $_SERVER['REQUEST_URI'])) {
		// Redirect
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: " . str_ireplace(get_option("settings_basedir"), "/", $_SERVER['REQUEST_URI']));
		exit();
	}
	
	// Add the filter handler for category links
	add_filter('category_link', 'ac_pathless_category_links'); // execute the category link renaming at the very end
}
endif;

/**
 * Activates the plugin, creating the necessary options
 *
 * @since 1.0
 *
 */
if ( !function_exists('ac_pathless_category_links_activate') ) :
function ac_pathless_category_links_activate() {
	// Add options
	add_option("settings_redirect", "1"); // indicates if we should perform 301 redirects, 1 = yes, 0 = no
	if(strlen(get_option('category_base')) > 0) {
		add_option("settings_basedir", "/" . get_option('category_base') . "/"); // the string to remove from the URLs (e.g. the category folder)
	} else {
		add_option("settings_basedir", "/category/"); // the string to remove from the URLs (e.g. the category folder)
	}
}
endif;

/**
 * Deactivates the plugin, removing the options
 *
 * @since 1.0
 *
 */
if ( !function_exists('ac_pathless_category_links_deactivate') ) :
function ac_pathless_category_links_deactivate() {
	// Remove options
	delete_option("settings_redirect"); // indicates if we should perform 301 redirects, 1 = yes, 0 = no
	delete_option("settings_basedir"); // the string to remove from the URLs (e.g. the /category/ folder)
}
endif;

/**
 * Updates existing settings
 *
 * @since 1.0
 * @param int $redirect Indicates if we should perform 301 redirects, 1 = yes, 0 = no
 * @param string $basedir The string to remove from the URLs (e.g. the /category/ folder)
 */
if ( !function_exists('ac_pathless_category_links_update') ) :
function ac_pathless_category_links_update($redirect, $basedir) {
	// Update options
	update_option("settings_redirect", $redirect); // indicates if we should perform 301 redirects, 1 = yes, 0 = no
	update_option("settings_basedir", $basedir); // the string to remove from the URLs (e.g. the category folder)
}
endif;

/**
 * Generates the option menu
 *
 * @since 1.0
 *
 */
if ( !function_exists('ac_pathless_category_links_menu') ) :
function ac_pathless_category_links_menu() {
	// Add the menu option
	add_options_page("Pathless Category Links", "Pathless Category Links", "manage_options", __FILE__, "ac_pathless_category_links_admin");
}
endif;

/**
 * Generates the admin page
 *
 * @since 1.0
 *
 */
if ( !function_exists('ac_pathless_category_links_admin') ) :
function ac_pathless_category_links_admin() {
	$errorMessage = NULL;
	$bSubmitted = false;
	if($_REQUEST["clr_submit"]) {
		$errorMessage = ac_pathless_category_links_adminsubmit();
		$bSubmitted = true;
	}
	if($_REQUEST["clr_submit_reset"]) {
		ac_pathless_category_links_adminreset();
		$bSubmitted = true;
	}
	
?><h2>Pathless Category Links Settings</h2>
<div class="metabox-holder" id="poststuff">
	<div class="inner-sidebar">
    	<div style="position: relative;" class="meta-box-sortabless ui-sortable" id="side-sortables">
        	<div class="postbox" id="sm_pnres">
            	<h3 class="hndle"><span>About this Plugin:</span></h3>
                <div class="inside">
                	<a href="http://www.anothercoder.com/wordpress/category-links-reloaded" class="sm_button sm_pluginHome">Plugin Homepage</a>
                </div>
			</div> <!-- end postbox -->
        </div> <!-- end side-sortables -->
	</div> <!-- end inner-siderbar -->
    <form method="post">
        <div class="has-sidebar sm-padded">
            <div class="has-sidebar-content" id="post-body-content">
                <div class="meta-box-sortabless">
                    <div class="postbox" id="sm_rebuild">
                        <h3 class="hndle"><span>Settings</span></h3>
                        <div class="inside">
                            <ul>
                                <?php if ( $bSubmitted ) : ?>
                                <li>
                                    <?php if (strlen($errorMessage) == 0) { ?>
                                    <font color="#999933">Settings successfully saved.</font>
                                    <?php } else { ?>
                                    <font color="#FF0000"><?= $errorMessage ?></font>
                                    <?php } ?>
                                </li>
                                <?php endif; ?>
                                <li>
                                    <label for="clr_redirect">
                                        <input type="checkbox" name="clr_redirect" id="clr_redirect"<?php if( get_option("settings_redirect") == "1" ) { echo "checked=checked"; }?> />
                                        301 Redirect old links that contain the category folder name
                                    </label>
                                </li>
                                <li>
                                    <label for="clr_basedir">
                                        Category folder name:
                                        <input type="text" id="clr_basedir" name="clr_basedir" value="<?= htmlspecialchars(get_option("settings_basedir")) ?>" />
                                    </label>
                                </li>
                            </ul>
                        </div> <!-- end inside -->
                    </div> <!-- end sm_rebuild -->
                </div> <!-- end meta-box-sortabless -->
                <div>
                    <p class="submit">
                        <input type="submit" value="Update Settings" name="clr_submit" id="clr_submit" />
                        <input type="submit" class="sm_warning" value="Reset Settings" id="clr_submit_reset" name="clr_submit_reset" onclick="return confirm(&quot;Reset the settings to the plugin originals?&quot;);" />
                    </p>
                </div>
            </div> <!-- end post-body-content -->
        </div> <!-- end has-siderbar -->
    </form>
</div> <!-- end poststuff -->
<?
}
endif;

/**
 * Handles the admin page submission
 *
 * @since 1.0
 *
 * @return string
 */
if ( !function_exists('ac_pathless_category_links_adminsubmit') ) :
function ac_pathless_category_links_adminsubmit() {
	// Validate the inputs
	$clr_redirect = 0;
	if(strtoupper($_REQUEST["clr_redirect"]) == "checked") { $clr_redirect = 1; }
	
	$clr_basedir = $_REQUEST["clr_basedir"];
	if(!$clr_basedir || strlen($clr_basedir) == 0) { return "You have to specify a Category folder name"; }
	
	if(strlen($clr_basedir) < 3 || substr($clr_basedir,0,1) != "/" || substr($clr_basedir,strlen($clr_basedir) - 1, 1) != "/") { return "The Category folder name must start with &quot;/&quot; and end with &quot;/&quot;"; }
	
	// Update the settings
	ac_pathless_category_links_update($clr_redirect, $clr_basedir);
	return "";
}
endif;

/**
 * Resets the settings
 *
 * @since 1.0
 *
 */
if ( !function_exists('ac_pathless_category_links_adminreset') ) :
function ac_pathless_category_links_adminreset() {
	// Remove existing settings
	ac_pathless_category_links_deactivate();
	// Add back the default settings
	ac_pathless_category_links_activate();
}
endif;

// Add the handler for the 301 redirect
add_action('init', 'ac_pathless_category_links_init', 10, 0);

// Add activation handlers
register_activation_hook(__FILE__,'ac_pathless_category_links_activate');
register_deactivation_hook(__FILE__,'ac_pathless_category_links_deactivate');

// Add menu
add_action('admin_menu','ac_pathless_category_links_menu');
?>