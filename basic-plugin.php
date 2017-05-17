<?php
/*
Plugin Name: Change Color
Plugin URI: https://akismet.com/
Description: Used by millions, Akismet is quite possibly the best way in the world to <strong>protect your blog from spam</strong>. It keeps your site protected even while you sleep. To get started: activate the Akismet plugin and then go to your Akismet Settings page to set up your API key.
Version: 3.3
Author: Automattic
Author URI: https://automattic.com/wordpress-plugins/
License: GPLv2 or later
Text Domain: akismet
*/

// admin head
function admin_head() {
  if($_POST) {
    $sidebar_bg_color = $_POST['sidebar_bg_color'];

    if (trim($sidebar_bg_color)) {
      update_option( 'sidebar_bg_color',  $sidebar_bg_color);
      add_action( 'admin_head', 'admin_head' );
    }
  }
  $defaultBgColor = get_option( 'sidebar_bg_color' );
	echo '<style> #adminmenu, #adminmenu .wp-submenu, #adminmenuback, #adminmenuwrap { background: '.$defaultBgColor.'; }</style>';
}
add_action( 'admin_head', 'admin_head' );


// init
add_action( 'admin_init', 'add_plugin_capabilities');

// Add Admin Menu / Page
add_action( 'admin_menu', 'create_plugin_menu' );
add_option( 'sidebar_bg_color', '#000', '', 'yes' );
// add_option('default_bg_color','#aaa','','yes');

function add_plugin_capabilities() {
  $role = get_role( 'administrator' );
  $role->add_cap( 'manage_change_color_options' );
}

function create_plugin_menu() {
  add_menu_page('Change Color Options', 'Change Color', 'manage_change_color_options', 'change-color-options', 'change_color_options_content');
	// add_options_page( 'Change Color Options', 'Change Color', 'change_color_options', 'change-color-options', 'change_color_options_content' );
}

function change_color_options_content() {
	if ( !current_user_can( 'manage_change_color_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

  $errorMsg = '';

  //
  if($_POST) {
    $sidebar_bg_color = $_POST['sidebar_bg_color'];

    if (!trim($sidebar_bg_color)) {
      $errorMsg = 'Sidebar BG color is required.';
    }
  }

  $defaultBgColor = get_option( 'sidebar_bg_color' );
    // $defaultBgColor = get_option( 'default_bg_color' );
  echo <<<EOT
  <p>{$errorMsg}</p>
  <form action="admin.php?page=change-color-options" method="post">
    <div>
      <label>Sidebar BG color: <input type="text" name="sidebar_bg_color" value="{$defaultBgColor}"></label>
    </div>
    <div>
      <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
    </div>
  </form>
EOT;

}

// menu location
