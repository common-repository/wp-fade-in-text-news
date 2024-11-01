<?php
/*
Plugin Name: WP fade in text news
Plugin URI: http://www.gopiplus.com/work/2011/04/22/wordpress-plugin-wp-fadein-text-news/
Description: Everybody loves fading in and out; this plugin will create the fade-in and out effect in the text. It is an excellent way to transition between announcements.
Author: Gopi Ramasamy
Version: 12.1
Author URI: http://www.gopiplus.com/work/2011/04/22/wordpress-plugin-wp-fadein-text-news/
Donate link: http://www.gopiplus.com/work/2011/04/22/wordpress-plugin-wp-fadein-text-news/
Tags: fadein, fade-in, fade in, news, plugin, widget, wordpress
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wp-fade-in-text-news
Domain Path: /languages
*/

global $wpdb, $wp_version;
define("WP_FadeIn_TABLE", $wpdb->prefix . "FadeInText_plugin");
define('FADEIN_FAV', 'http://www.gopiplus.com/work/2011/04/22/wordpress-plugin-wp-fadein-text-news/');

if ( ! defined( 'FADEIN_BASENAME' ) )
	define( 'FADEIN_BASENAME', plugin_basename( __FILE__ ) );
	
if ( ! defined( 'FADEIN_PLUGIN_NAME' ) )
	define( 'FADEIN_PLUGIN_NAME', trim( dirname( FADEIN_BASENAME ), '/' ) );
	
if ( ! defined( 'FADEIN_PLUGIN_URL' ) )
	define( 'FADEIN_PLUGIN_URL', WP_PLUGIN_URL . '/' . FADEIN_PLUGIN_NAME );
	
if ( ! defined( 'FADEIN_ADMIN_URL' ) )
	define( 'FADEIN_ADMIN_URL', get_option('siteurl') . '/wp-admin/options-general.php?page=wp-fade-in-text-news' );

function FadeIn() 
{
	global $wpdb;
	$FadeIn_Arr = "";
	$FadeIn_FadeOut = get_option('FadeIn_FadeOut');
	$FadeIn_FadeIn = get_option('FadeIn_FadeIn');
	$FadeIn_Fade = get_option('FadeIn_Fade');
	$FadeIn_FadeStep = get_option('FadeIn_FadeStep');
	$FadeIn_FadeWait = get_option('FadeIn_FadeWait');
	$FadeIn_bFadeOutt = get_option('FadeIn_bFadeOutt');
	$FadeIn_group = get_option('FadeIn_group');
	
	if(!is_numeric($FadeIn_FadeOut)){ $FadeIn_FadeOut = 255; } 
	if(!is_numeric($FadeIn_FadeIn)){ $FadeIn_FadeIn = 0; } 
	if(!is_numeric($FadeIn_Fade)){ $FadeIn_Fade = 0; } 
	if(!is_numeric($FadeIn_FadeStep)){ $FadeIn_FadeStep = 3; } 
	if(!is_numeric($FadeIn_FadeWait)){ $FadeIn_FadeWait = 3000; } 
	
	$sSql = "select FadeIn_text,FadeIn_link from ".WP_FadeIn_TABLE." where FadeIn_status='YES'";
	$sSql = $sSql . " and (`FadeIn_date` >= NOW() or `FadeIn_date` = '0000-00-00')";
	if($FadeIn_group <> "")
	{
		$sSql = $sSql . " and FadeIn_group= %s ";
		$sSql = $wpdb->prepare($sSql, $FadeIn_group);
	}
	$sSql = $sSql . "ORDER BY FadeIn_order";
	
	$data = $wpdb->get_results($sSql);
	
	if ( ! empty($sSql) ) 
	{
		$FadeIn_Count = 0;
		foreach ( $data as $data ) 
		{
			$FadeIn_text =  $data->FadeIn_text;
			//$FadeIn_text = mysql_real_escape_string(trim($FadeIn_text));
			$FadeIn_link = $data->FadeIn_link;
			$FadeIn_Arr = $FadeIn_Arr . "FadeIn_Links[$FadeIn_Count] = '$FadeIn_link';FadeIn_Titles[$FadeIn_Count] = '$FadeIn_text';";
			if($FadeIn_Count == 0)
			{
				$FadeIn_First = $FadeIn_text;
			}
			$FadeIn_Count = $FadeIn_Count + 1;
		}
		?>
		<script type="text/javascript" language="javascript">
		function FadeIn_SetFadeLinks() 
		{
			<?php echo $FadeIn_Arr ?>
		}
	
		var FadeIn_FadeOut = <?php echo $FadeIn_FadeOut; ?>;
		var FadeIn_FadeIn = <?php echo $FadeIn_FadeIn; ?>;
		var FadeIn_Fade = <?php echo $FadeIn_Fade; ?>;
		var FadeIn_FadeStep = <?php echo $FadeIn_FadeStep; ?>;
		var FadeIn_FadeWait = <?php echo $FadeIn_FadeWait; ?>;
		var FadeIn_bFadeOutt = <?php echo $FadeIn_bFadeOutt; ?>;
	
		</script>
		<div id="FadeIn_CSS" style="padding:5px;">
		<a href="#" id="FadeIn_Link"><?php echo $FadeIn_text; ?></a>
		</div>
		<?php
	}
}

function FadeIn_add_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_script( 'wp-fade-in-text-news', FADEIN_PLUGIN_URL.'/wp-fade-in-text-news.js');
	}	
}

function FadeIn_install() 
{
	global $wpdb;
	if($wpdb->get_var("show tables like '". WP_FadeIn_TABLE . "'") != WP_FadeIn_TABLE) 
	{
		$wpdb->query("
			CREATE TABLE IF NOT EXISTS `". WP_FadeIn_TABLE . "` (
			  `FadeIn_id` int(11) NOT NULL auto_increment,
			  `FadeIn_text` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
			  `FadeIn_link` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
			  `FadeIn_order` int(11) NOT NULL default '0',
			  `FadeIn_status` char(3) NOT NULL default 'No',
			  `FadeIn_group` VARCHAR( 100 ) NOT NULL,
			  `FadeIn_date` datetime NOT NULL default '0000-00-00 00:00:00',
			  PRIMARY KEY  (`FadeIn_id`) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
			");
			
		$sSql = "SELECT COUNT(*) AS `count` FROM ". WP_FadeIn_TABLE;
		$result = '0';
		$result = $wpdb->get_var($sSql);
		if($result == '0')
		{
			$iIns = "INSERT INTO `". WP_FadeIn_TABLE . "` (`FadeIn_text`,`FadeIn_link`, `FadeIn_order`, `FadeIn_status`, `FadeIn_group`, `FadeIn_date`)"; 
			$sSql = $iIns . "VALUES ('Lorem Ipsum is simply dummy text of the printing and typesetting industry.','#', '1', 'YES', 'SAMPLE', '0000-00-00 00:00:00');";
			$wpdb->query($sSql);
			$sSql = $iIns . "VALUES ('Lorem Ipsum is simply dummy text of the printing and typesetting industry.','#', '2', 'YES', 'SAMPLE', '0000-00-00 00:00:00');";
			$wpdb->query($sSql);
			$sSql = $iIns . "VALUES ('Lorem Ipsum is simply dummy text of the printing and typesetting industry.','#', '3', 'YES', 'SAMPLE', '0000-00-00 00:00:00');";
			$wpdb->query($sSql);
			$sSql = $iIns . "VALUES ('Lorem Ipsum is simply dummy text of the printing and typesetting industry.','#', '4', 'YES', 'WIDGET', '0000-00-00 00:00:00');";
			$wpdb->query($sSql);
			$sSql = $iIns . "VALUES ('Lorem Ipsum is simply dummy text of the printing and typesetting industry.','#', '5', 'YES', 'WIDGET', '0000-00-00 00:00:00');";
			$wpdb->query($sSql);
		}
	}
	add_option('FadeIn_Title', "Text Fadein plugin");
	add_option('FadeIn_FadeOut', "255");
	add_option('FadeIn_FadeIn', "0");
	add_option('FadeIn_Fade', "0");
	add_option('FadeIn_FadeStep', "3");
	add_option('FadeIn_FadeWait', "3000");
	add_option('FadeIn_bFadeOutt', "true");
	add_option('FadeIn_group', "WIDGET");
}

function FadeIn_control() 
{
	echo '<p><b>';
	 _e('Fade in text news', 'wp-fade-in-text-news');
	echo '.</b> ';
	_e('Check official website for more information', 'wp-fade-in-text-news');
	?> <a target="_blank" href="<?php echo FADEIN_FAV; ?>"><?php _e('click here', 'wp-fade-in-text-news'); ?></a></p><?php
}

function FadeIn_widget($args) 
{
	extract($args);
	echo $before_widget . $before_title;
	echo get_option('FadeIn_Title');
	echo $after_title;
	FadeIn();
	echo $after_widget;
}

function FadeIn_admin_options() 
{
	global $wpdb;
	$current_page = isset($_GET['ac']) ? $_GET['ac'] : '';
	switch($current_page)
	{
		case 'edit':
			include('pages/content-management-edit.php');
			break;
		case 'add':
			include('pages/content-management-add.php');
			break;
		case 'set':
			include('pages/widget-setting.php');
			break;
		default:
			include('pages/content-management-show.php');
			break;
	}
}

add_shortcode( 'fadein-text', 'FadeIn_Show_shortcode' );
function FadeIn_Show_shortcode( $atts ) 
{
	global $wpdb;
	$JaFade = "";
	$FadeIn_Arr = "";
	$group = "";
	
	$FadeIn_FadeOut = get_option('FadeIn_FadeOut');
	$FadeIn_FadeIn = get_option('FadeIn_FadeIn');
	$FadeIn_Fade = get_option('FadeIn_Fade');
	$FadeIn_FadeStep = get_option('FadeIn_FadeStep');
	$FadeIn_FadeWait = get_option('FadeIn_FadeWait');
	$FadeIn_bFadeOutt = get_option('FadeIn_bFadeOutt');
	
	if(!is_numeric($FadeIn_FadeOut)){ $FadeIn_FadeOut = 255; } 
	if(!is_numeric($FadeIn_FadeIn)){ $FadeIn_FadeIn = 0; } 
	if(!is_numeric($FadeIn_Fade)){ $FadeIn_Fade = 0; } 
	if(!is_numeric($FadeIn_FadeStep)){ $FadeIn_FadeStep = 3; } 
	if(!is_numeric($FadeIn_FadeWait)){ $FadeIn_FadeWait = 3000; } 

	// Old short code
	//[FADEIN_TEXT_NEWS GROUP="widget"]
	//$var = $matches[1];
	//extract( shortcode_atts( array('group' => $group,), $var ) );
	//list($group,$value) = split('=', $var);
	//$tblgroup = str_replace('"', '', $value);
	
	// New short code
	// [fadein-text group="widget"]
	$tblgroup = "";
	if ( is_array( $atts ) )
	{
		$tblgroup = $atts['group'];
	}
	
	$sSql = "select FadeIn_text,FadeIn_link from ".WP_FadeIn_TABLE." where FadeIn_status='YES'";
	$sSql = $sSql . " and (`FadeIn_date` >= NOW() or `FadeIn_date` = '0000-00-00')";
	if($tblgroup <> "")
	{
		$sSql = $sSql . " and FadeIn_group = %s ";
		$sSql = $wpdb->prepare($sSql, $tblgroup);
	}
	
	$sSql = $sSql . "ORDER BY FadeIn_order";
	
	$data = $wpdb->get_results($sSql);

	$FadeIn_Count = 0;
	if ( ! empty($data) ) 
	{
		foreach ( $data as $data )  
		{
			$FadeIn_text =  $data->FadeIn_text;
			//$FadeIn_text = mysql_real_escape_string(trim($FadeIn_text));
			$FadeIn_link = $data->FadeIn_link;
			$FadeIn_Arr = $FadeIn_Arr . "FadeIn_Links[$FadeIn_Count] = '".$FadeIn_link."';FadeIn_Titles[$FadeIn_Count] = '".$FadeIn_text."';";
			if($FadeIn_Count == 0)
			{
				$FadeIn_First = $FadeIn_text;
			}
			$FadeIn_Count = $FadeIn_Count + 1;
		}
		
		$JaFade = $JaFade . "<script type='text/javascript' language='javascript'>function FadeIn_SetFadeLinks() { $FadeIn_Arr;}";
		
		$JaFade = $JaFade . 'var FadeIn_FadeOut = '.$FadeIn_FadeOut.';';
		$JaFade = $JaFade . 'var FadeIn_FadeIn = '.$FadeIn_FadeIn.';';
		$JaFade = $JaFade . 'var FadeIn_Fade = '.$FadeIn_Fade.';';
		$JaFade = $JaFade . 'var FadeIn_FadeStep = '.$FadeIn_FadeStep.';';
		$JaFade = $JaFade . 'var FadeIn_FadeWait = '.$FadeIn_FadeWait.';';
		$JaFade = $JaFade . 'var FadeIn_bFadeOutt = '.$FadeIn_bFadeOutt.';';
		
		$JaFade = $JaFade . '</script>';
		$JaFade = $JaFade . '<div id="FadeIn_CSS">';
		$JaFade = $JaFade . '<a href="#" id="FadeIn_Link">'.$FadeIn_First.'</a>';
		$JaFade = $JaFade . '</div>';
	}
	else
	{
		//$JaFade = __('No record found for this short code', 'wp-fade-in-text-news');
	}
	return $JaFade;
}

function FadeIn_add_to_menu() 
{
	if (is_admin()) 
	{
		add_options_page(__('Fade in text news', 'wp-fade-in-text-news'), 
							__('Fade in text news', 'wp-fade-in-text-news'), 'manage_options', 
									'wp-fade-in-text-news', 'FadeIn_admin_options' );
	}
}

function FadeIn_init()
{
	if(function_exists('wp_register_sidebar_widget')) 
	{
		wp_register_sidebar_widget('fade-in-text-news', __('Fade in text news', 'wp-fade-in-text-news'), 'FadeIn_widget');
	}
	
	if(function_exists('wp_register_widget_control')) 
	{
		wp_register_widget_control('fade-in-text-news', array(__('Fade in text news', 'wp-fade-in-text-news'), 'widgets'), 'FadeIn_control');
	} 
}

function FadeIn_deactivation() 
{
	delete_option('FadeIn_FadeOut');
	delete_option('FadeIn_FadeIn');
	delete_option('FadeIn_Fade');
	delete_option('FadeIn_FadeStep');
	delete_option('FadeIn_FadeWait');
	delete_option('FadeIn_bFadeOutt');
}

function FadeIn_textdomain() 
{
	  load_plugin_textdomain( 'wp-fade-in-text-news', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

function FadeIn_adminscripts() 
{
	if( !empty( $_GET['page'] ) ) 
	{
		switch ( $_GET['page'] ) 
		{
			case 'wp-fade-in-text-news':
				wp_register_script( 'FadeIn-adminscripts', plugins_url( 'pages/setting.js', __FILE__ ), '', '', true );
				wp_enqueue_script( 'FadeIn-adminscripts' );
				$FadeIn_select_params = array(
					'FadeIn_text'   => __( 'Please enter the text.', 'FadeIn-select', 'wp-fade-in-text-news' ),
					'FadeIn_link'   => __( 'Please enter the link.', 'FadeIn-select', 'wp-fade-in-text-news' ),
					'FadeIn_status' => __( 'Please select the display status.', 'FadeIn-select', 'wp-fade-in-text-news' ),
					'FadeIn_group'  => __( 'Please select the group name. this field is used to group the news.', 'FadeIn-select', 'wp-fade-in-text-news' ),
					'FadeIn_status' => __( 'Please select the display dtatus.', 'FadeIn-select', 'wp-fade-in-text-news' ),
					'FadeIn_order'  => __( 'Please enter the display order, only number.', 'FadeIn-select', 'wp-fade-in-text-news' ),
					'FadeIn_delete'	=> __( 'Do you want to delete this record?', 'FadeIn-select', 'wp-fade-in-text-news' ),
				);
				wp_localize_script( 'FadeIn-adminscripts', 'FadeIn_adminscripts', $FadeIn_select_params );
				break;
		}
	}
}

add_action('plugins_loaded', 'FadeIn_textdomain');
add_action('admin_menu', 'FadeIn_add_to_menu');
add_action('wp_enqueue_scripts', 'FadeIn_add_javascript_files');
add_action("plugins_loaded", "FadeIn_init");
register_activation_hook(__FILE__, 'FadeIn_install');
register_deactivation_hook(__FILE__, 'FadeIn_deactivation');
add_action('admin_menu', 'FadeIn_add_to_menu');
add_action( 'admin_enqueue_scripts', 'FadeIn_adminscripts' );
?>