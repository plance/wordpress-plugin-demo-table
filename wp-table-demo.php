<?php defined('ABSPATH') || exit;
/*
Plugin Name: WP Table Demo
Description: Table Demo
Version: 1.0.0
Author: Pavel
Author URI: //plance.top
*/

define('PLG_TABLE__PATH', plugin_dir_path(__FILE__));
define('PLG_TABLE__URL', plugin_dir_url(__FILE__));


include_once __DIR__ . '/includes/helpers.php';
include_once __DIR__ . '/app/controller/data.php';
include_once __DIR__ . '/app/view/index.php';
include_once __DIR__ . '/includes/validate.php';


/** Flahs init */
Plg_Table_Helpers::flashInit();


add_action('plugins_loaded', function()
{
	if(is_admin())
	{
		add_action('admin_menu', function()
		{
			$ControllerData = new Plg_Table_Controller_Data();
			$hook = add_menu_page(
				'Plugin Table',
				'Plugin Table',
				'manage_options',
				'plg-table',
				array($ControllerData, 'view')
			);
			add_action('load-'.$hook, array($ControllerData, 'action'));

		}, 10);
		
		if(class_exists('WP_List_Table') == false)
		{
			require_once (ABSPATH.'wp-admin/includes/class-wp-list-table.php');
		}
	}
});


register_activation_hook(__FILE__, 'plg_table_activation');
function plg_table_activation()
{
	global $wpdb;
	
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	
	dbDelta("CREATE TABLE IF NOT EXISTS `".$wpdb -> prefix."plg_table_demo` (
		`id` INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
		`field_one` VARCHAR(255) NOT NULL,
		`field_two` VARCHAR(255) NOT NULL,
		`date_create` INT(10) UNSIGNED NOT NULL
	) {$wpdb -> get_charset_collate()};");
	
	return true;
};

register_uninstall_hook(__FILE__, 'plg_table_uninstall');
function plg_table_uninstall()
{
	global $wpdb;
	
	$wpdb -> query("DROP TABLE IF EXISTS `" . $wpdb -> prefix . "plg_table_demo`");

	return true;
}