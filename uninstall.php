<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option('FadeIn_Title');
delete_option('FadeIn_FadeOut');
delete_option('FadeIn_FadeIn');
delete_option('FadeIn_Fade');
delete_option('FadeIn_FadeStep');
delete_option('FadeIn_FadeWait');
delete_option('FadeIn_bFadeOutt');
delete_option('FadeIn_group');
 
// for site options in Multisite
delete_site_option('FadeIn_Title');
delete_site_option('FadeIn_FadeOut');
delete_site_option('FadeIn_FadeIn');
delete_site_option('FadeIn_Fade');
delete_site_option('FadeIn_FadeStep');
delete_site_option('FadeIn_FadeWait');
delete_site_option('FadeIn_bFadeOutt');
delete_site_option('FadeIn_group');

global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}FadeInText_plugin");