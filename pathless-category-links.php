<?php
/**
 * @package Pathless_Category_Links
 * @author Another Coder
 * @version 1.1
 */
/*
Plugin Name: Pathless Category Links
Plugin URI: http://www.anothercoder.com/wordpress/pathless-category-permalinks-plugin
Description: Updates category links to remove the /category/ or any other folder you specify, putting your category links in the root directory, e.g. http://www.anothercoder.org/category_name
Author: Another Coder
Version: 1.1
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
	$newCatLink = str_ireplace(get_option("settings_basedir"), "/", $cat_link);
	
	// We have to rewrite the pagination
	if(preg_match("/\/page\/([0-9]*)/", $newCatLink)) {
		if(get_option("settings_rewritepg") == "1") {
			$newCatLink = preg_replace("/\/page\/([0-9]*)/", "/?paged=$1", $cat_link);
		} else {
			// We can't modify this link (it contains paging information)
			$newCatLink = $cat_link;
		}
	}

	// return the new category link
	return $newCatLink;
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
		// Get the new location
		$newCatLink = str_ireplace(get_option("settings_basedir"), "/", $_SERVER['REQUEST_URI']);
		
		// We have to rewrite the pagination
		if(preg_match("/\/page\/([0-9]*)/", $newCatLink)) {
			if(get_option("settings_rewritepg") == "1")
			{
				$newCatLink = preg_replace("/\/page\/([0-9]*)/", "/?paged=$1", $newCatLink);
			} else {
				// We can't 301 redirect this page or it will give a 404
				$newCatLink = NULL;
			}
		}
		
		// Redirect
		if($newCatLink)
		{
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: " . $newCatLink);
			exit();
		}
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
	add_option("settings_rewritepg", "1"); // indicates if we should perform a modification to the category link if it contains page information, 1 = yes, 0 = no
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
	delete_option("settings_rewritepg"); // indicates if we should perform a modification to the category link if it contains page information, 1 = yes, 0 = no
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
function ac_pathless_category_links_update($redirect, $basedir, $rewritepg) {
	// Update options
	update_option("settings_redirect", $redirect); // indicates if we should perform 301 redirects, 1 = yes, 0 = no
	update_option("settings_basedir", $basedir); // the string to remove from the URLs (e.g. the category folder)
	update_option("settings_rewritepg", $rewritepg); // indicates if we should perform a modification to the category link if it contains page information, 1 = yes, 0 = no
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
        	<div class="postbox">
            	<h3 class="hndle"><span>About this Plugin:</span></h3>
                <div class="inside">
                	<a href="http://www.anothercoder.com/wordpress/category-links-reloaded">Plugin Homepage</a>
                    <br /><br />
                    <a href="http://www.anothercoder.com/wordpress/category-links-reloaded">Suggest a Feature</a>
                    <br /><br />
                    <a href="http://www.anothercoder.com/wordpress/category-links-reloaded">Report a Bug</a>
                </div>
			</div> <!-- end postbox -->
        </div> <!-- end side-sortables -->
	</div> <!-- end inner-siderbar -->
    <form method="post">
        <div class="has-sidebar sm-padded">
            <div class="has-sidebar-content" id="post-body-content">
                <div class="meta-box-sortabless">
                    <div class="postbox">
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
                                <li>
                                    <label for="clr_rewritepg">
                                        <input type="checkbox" name="clr_rewritepg" id="clr_rewritepg"<?php if( get_option("settings_rewritepg") == "1" ) { echo "checked=checked"; }?> />
                                        Update category links with pages
                                    </label>
                                    <br />
                                    <table style="margin-left:50px; border:1px dashed #ccc;">
                                        <tr>
                                            <td style="border-bottom:1px solid #ccc; padding:5px;">
                                                * <b>Highly Recommended</b>: If your category link contains a page, e.g. <i>/page/1</i>, the page will be moved to the QueryString, e.g. <i>/?paged=1</i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding:5px;">
                                                * If this is disabled, and your permalink contains a page, the link modification and 301 redirect will NOT be performed
                                            </td>
                                        </tr>
									</table>
                                </li>
                            </ul>
                        </div> <!-- end inside -->
                    </div> <!-- end postbox -->
                </div> <!-- end meta-box-sortabless -->
                <div>
                    <p class="submit">
                        <input type="submit" value="Update Settings" name="clr_submit" id="clr_submit" />
                        <input type="submit" value="Reset Settings" id="clr_submit_reset" name="clr_submit_reset" onclick="return confirm(&quot;Reset the settings to the plugin originals?&quot;);" />
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
	if(strtoupper($_REQUEST["clr_redirect"]) == "ON") { $clr_redirect = 1; }
	
	$clr_rewritepg = 0;
	if(strtoupper($_REQUEST["clr_rewritepg"]) == "ON") { $clr_rewritepg = 1; }
	
	$clr_basedir = $_REQUEST["clr_basedir"];
	if(!$clr_basedir || strlen($clr_basedir) == 0) { return "You have to specify a Category folder name"; }
	
	if(strlen($clr_basedir) < 3 || substr($clr_basedir,0,1) != "/" || substr($clr_basedir,strlen($clr_basedir) - 1, 1) != "/") { return "The Category folder name must start with &quot;/&quot; and end with &quot;/&quot;"; }
	
	// Update the settings
	ac_pathless_category_links_update($clr_redirect, $clr_basedir, $clr_rewritepg);
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