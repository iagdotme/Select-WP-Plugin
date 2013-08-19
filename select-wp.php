<?php
/*
Plugin Name: Select WP
Description: Custom WordPress functions and cleanup
Author: Ian Anderson Gray
Version: 0.1
Author URI: http://selectperformers.com/
*/

/* Disable the Admin Bar. */
// From: http://yoast.com/disable-wp-admin-bar/
add_filter( 'show_admin_bar', '__return_false' );

function sp_hide_admin_bar_settings()
    {
        ?><style type="text/css">.show-admin-bar {display: none;}</style><?php
    }

function sp_disable_admin_bar() {
    add_filter( 'show_admin_bar', '__return_false' );
    add_action( 'admin_print_scripts-profile.php', 
         'sp_hide_admin_bar_settings' );
}
add_action( 'init', 'sp_disable_admin_bar' , 9 );


/* ------------------------------------------------------------------ */



// Admin footer modification
// http://wp.tutsplus.com/tutorials/customizing-wordpress-for-your-clients/
function remove_footer_admin () 
{
    echo '<span id="footer-thankyou">Developed by <a href="http://selectperformers.com/" target="_blank">Select Performers Internet Solutions</a></span>';
}
add_filter('admin_footer_text', 'remove_footer_admin');

/* ------------------------------------------------------------------ */




// Remove Dashboard Widgets
// http://digwp.com/2010/10/customize-wordpress-dashboard/

function disable_default_dashboard_widgets() {

// disable default dashboard widgets
remove_meta_box('dashboard_right_now', 'dashboard', 'core');
remove_meta_box('dashboard_recent_comments', 'dashboard', 'core');
remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');
remove_meta_box('dashboard_plugins', 'dashboard', 'core');
remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core');

remove_meta_box('dashboard_quick_press', 'dashboard', 'core');
remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core');
remove_meta_box('dashboard_primary', 'dashboard', 'core');
remove_meta_box('dashboard_secondary', 'dashboard', 'core');
remove_meta_box('rg_forms_dashboard', 'dashboard', 'normal;'); 
remove_meta_box('blc_dashboard_widget', 'dashboard', 'normal;'); 
remove_meta_box('powerpress_dashboard_news', 'dashboard', 'normal;');
// disable Simple:Press dashboard widget
remove_meta_box('sf_announce', 'dashboard', 'normal');
}
add_action('admin_menu', 'disable_default_dashboard_widgets');




// Remove help and screen context:
// Remove Help and Screen Options
// http://wordpress.stackexchange.com/questions/73561/how-to-remove-all-widgets-from-dashboard
add_filter( 'contextual_help', 'wpse_25034_remove_dashboard_help_tab', 999, 3 );
add_filter( 'screen_options_show_screen', 'wpse_25034_remove_help_tab' );

function wpse_25034_remove_dashboard_help_tab( $old_help, $screen_id, $screen )
{
if( 'dashboard' != $screen->base )
return $old_help;

$screen->remove_help_tabs();
return $old_help;
}

function wpse_25034_remove_help_tab( $visible )
{
global $current_screen;
if( 'dashboard' == $current_screen->base )
return false;
return $visible;
}



/* ------------------------------------------------------------------ */
// Change Howdy
// http://www.wpbeginner.com/wp-tutorials/how-to-change-the-howdy-text-in-wordpress-3-3-admin-bar/
add_action( 'admin_bar_menu', 'wp_admin_bar_my_custom_account_menu', 11 );

function wp_admin_bar_my_custom_account_menu( $wp_admin_bar ) {
$user_id = get_current_user_id();
$current_user = wp_get_current_user();
$profile_url = get_edit_profile_url( $user_id );

if ( 0 != $user_id ) {
/* Add the "My Account" menu */
$avatar = get_avatar( $user_id, 28 );
$howdy = sprintf( __('Welcome, %1$s'), $current_user->display_name );
$class = empty( $avatar ) ? '' : 'with-avatar';

$wp_admin_bar->add_menu( array(
'id' => 'my-account',
'parent' => 'top-secondary',
'title' => $howdy . $avatar,
'href' => $profile_url,
'meta' => array(
'class' => $class,
),
) );

}
}




/* ------------------------------------------------------------------ */
// Remove Mandrill Dashboard Widget
// http://wordpress.org/support/topic/dashboard-widget?replies=3
function sp_remove_wpmandrill_dashboard() {
if ( class_exists( 'wpMandrill' ) ) {
remove_action( 'wp_dashboard_setup', array( 'wpMandrill' , 'addDashboardWidgets' ) );
}
}
add_action( 'admin_init', 'sp_remove_wpmandrill_dashboard' );


/* ------------------------------------------------------------------ */
// Custom Login Image
// http://wp.tutsplus.com/tutorials/customizing-wordpress-for-your-clients/

function my_custom_login_logo()
{
    echo '<style  type="text/css"> h1 a {  background-image:url(/assets/img/spLogo.png)  !important; } </style>';
}
add_action('login_head',  'my_custom_login_logo');



/* ------------------------------------------------------------------ */
// Change Login Logo Link
// http://wp.tutsplus.com/tutorials/customizing-wordpress-for-your-clients/

 
function change_wp_login_url() 
{
    return "http://selectperformers.com/";  // OR ECHO YOUR OWN URL
}
add_filter('login_headerurl', 'change_wp_login_url');
 
// CUSTOM ADMIN LOGIN LOGO & ALT TEXT
 
function change_wp_login_title() 
{
    return "Select Performers"; // OR ECHO YOUR OWN ALT TEXT
}
add_filter('login_headertitle', 'change_wp_login_title');





//hook onto dashboard and redirect all non admin to front site
add_action("admin_init","redirect_nonadmin_fromdash");
function redirect_nonadmin_fromdash()
	{
		if(!current_user_can('editor') AND !current_user_can('administrator') AND !current_user_can('author'))
			{
				// Edit Line Below For Your Own Redirect
				header( 'Location: /' ) ;
			}
	}



function override_mce_options($initArray)
   {
        $opts = '*[*]';
        $initArray['valid_elements'] = $opts;
        $initArray['extended_valid_elements'] = $opts;
        return $initArray;
    }
    add_filter('tiny_mce_before_init', 'override_mce_options');




// Remove Clutter by hiding widgets using CSS
add_action('admin_head', 'wp_remove_clutter');

function wp_remove_clutter() {
  echo '<style>
    #w3tc-dashboard-widgets {display:none;} 
  </style>';
  


// Add Custom Admin Feature
function sp_custom_login()
    {
        $current_user = wp_get_current_user();
        $displayName = $current_user->display_name; 
        $logout = wp_logout_url("/");
		echo '<style>
		      .adminEdit {position:fixed;bottom:10px;left:10px;}
              .adminEdit p { background:#dedede; opacity:0.8; padding:10px; border:#ababab 1px solid; font-size:0.9em; border-radius:5px;}
			  </style';
	    echo '<script type="javascript">';
		echo 'jQuery(document).ready(function ($) {';
		echo '$(\'#admin-menu-show\').click(function() {$(\'.adminEdit\').toggle(\'slow\');});';
		echo '});';
		echo '</script>';
        if ( current_user_can('edit_post'))
		    {
				edit_post_link("<i class=\"icon-edit\"></i> Edit Page","<div class=\"adminEdit\"><p><i class=\"icon-user\"></i> $displayName logged in |  <a href=\"$logout\" title=\"Log Out\"><i class=\"icon-signout\"></i> Log Out</a> | <a href=\"/wp-admin/\" title=\"Go to Dashboard\"><i class=\"icon-cog\"></i> Dashboard</a> | ","</p></div>");
	          ?><div id="admin-menu-show" title="Show Admin Bar"><i class="icon-cog"></i></div><?php 
            }
        elseif(is_user_logged_in())
		    {
                echo "<div class=\"adminEdit\"><p><i class=\"icon-user\"></i> $displayName logged in | <a href=\"$logout\" title=\"Log Out\"><i class=\"icon-signout\"></i> Log Out</a>";
                ?><div id="admin-menu-show" title="Show Admin Bar"><i class="icon-cog"></i></div><?php
            }
    }
add_action('wp_footer', 'sp_custom_login');




// Add new image sizes
// http://tommaitland.net/2013/01/add-custom-image-sizes-to-wordpress-media-uploader/
/*
  function custom_image_sizes() {
  add_theme_support('post-thumbnails');
	add_image_size('larger',       800, 800, false);
	add_image_size('medium-large', 500, 500, false);
	add_image_size('thumb-small',  100, 100, true);
	add_image_size('thumb-mini',   70,  70,  true);	
	add_image_size('thumb-micro',  50,  50,  true);
}
add_action('after_setup_theme', 'custom_image_sizes');
}
*/


// Add confirmation to publish
// https://gist.github.com/plasticmind/4337952
/* = Add a "molly guard" to the publish button */
 
add_action( 'admin_print_footer_scripts', 'sr_publish_molly_guard' );
function sr_publish_molly_guard() {
echo <<<EOT
	<script>
	jQuery(document).ready(function($){
		$('#publishing-action input[name="publish"]').click(function() {
			if(confirm('Are you sure you want to publish this?')) {
				return true;
			} else {
				$('#publishing-action .spinner').hide();
				$('#publishing-action img').hide();
				$(this).removeClass('button-primary-disabled');
				return false;
			}
		});
	});
	</script>
EOT;
}




?>