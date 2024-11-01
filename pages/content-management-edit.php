<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<div class="wrap">
<?php
$did = isset($_GET['did']) ? $_GET['did'] : '0';
if(!is_numeric($did)) { die('<p>Are you sure you want to do this?</p>'); }

// First check if ID exist with requested ID
$sSql = $wpdb->prepare(
	"SELECT COUNT(*) AS `count` FROM ".WP_FadeIn_TABLE."
	WHERE `FadeIn_id` = %d",
	array($did)
);
$result = '0';
$result = $wpdb->get_var($sSql);

if ($result != '1')
{
	?><div class="error fade"><p><strong><?php _e('Oops, selected details doesnt exist', 'wp-fade-in-text-news'); ?></strong></p></div><?php
}
else
{
	$FadeIn_errors = array();
	$FadeIn_success = '';
	$FadeIn_error_found = FALSE;
	
	$sSql = $wpdb->prepare("
		SELECT *
		FROM `".WP_FadeIn_TABLE."`
		WHERE `FadeIn_id` = %d
		LIMIT 1
		",
		array($did)
	);
	$data = array();
	$data = $wpdb->get_row($sSql, ARRAY_A);
	
	// Preset the form fields
	$form = array(
		'FadeIn_text' => $data['FadeIn_text'],
		'FadeIn_status' => $data['FadeIn_status'],
		'FadeIn_group' => $data['FadeIn_group'],
		'FadeIn_link' => $data['FadeIn_link'],
		'FadeIn_order' => $data['FadeIn_order'],
		'FadeIn_date' => $data['FadeIn_date']
	);
}
// Form submitted, check the data
if (isset($_POST['FadeIn_form_submit']) && $_POST['FadeIn_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('FadeIn_form_edit');
	
	$form['FadeIn_text'] = isset($_POST['FadeIn_text']) ? wp_filter_post_kses($_POST['FadeIn_text']) : '';
	if ($form['FadeIn_text'] == '')
	{
		$FadeIn_errors[] = __('Please enter the popup message.', 'wp-fade-in-text-news');
		$FadeIn_error_found = TRUE;
	}
	
	$form['FadeIn_link'] = isset($_POST['FadeIn_link']) ? esc_url_raw($_POST['FadeIn_link']) : '';
	
	$form['FadeIn_order'] = isset($_POST['FadeIn_order']) ? $_POST['FadeIn_order'] : '';
	if(!is_numeric($form['FadeIn_order'])) { $form['FadeIn_order'] = 1; }
	
	$form['FadeIn_status'] = isset($_POST['FadeIn_status']) ? $_POST['FadeIn_status'] : '';
	if($form['FadeIn_status'] != "YES" && $form['FadeIn_status'] != "NO")
	{
		$form['FadeIn_status'] = "YES";
	} 
		
	$form['FadeIn_group'] = isset($_POST['FadeIn_group']) ? sanitize_text_field($_POST['FadeIn_group']) : '';
	
	$form['FadeIn_date'] = isset($_POST['FadeIn_date']) ? $_POST['FadeIn_date'] : '';
	if (!preg_match("/\d{4}\-\d{2}-\d{2}/", $form['FadeIn_date'])) 
	{
		$FadeIn_errors[] = __('Please enter the expiration date in this format YYYY-MM-DD.', 'wp-fade-in-text-news');
		$FadeIn_error_found = TRUE;
	}

	//	No errors found, we can add this Group to the table
	if ($FadeIn_error_found == FALSE)
	{	
		$sSql = $wpdb->prepare(
				"UPDATE `".WP_FadeIn_TABLE."`
				SET `FadeIn_text` = %s,
				`FadeIn_status` = %s,
				`FadeIn_link` = %s,
				`FadeIn_order` = %d,
				`FadeIn_group` = %s,
				`FadeIn_date` = %s
				WHERE FadeIn_id = %d
				LIMIT 1",
				array($form['FadeIn_text'], $form['FadeIn_status'], $form['FadeIn_link'], $form['FadeIn_order'], $form['FadeIn_group'], $form['FadeIn_date'], $did)
			);
		$wpdb->query($sSql);
		
		$FadeIn_success = __('Details was successfully updated.', 'wp-fade-in-text-news');
	}
}

if ($FadeIn_error_found == TRUE && isset($FadeIn_errors[0]) == TRUE)
{
?>
  <div class="error fade">
    <p><strong><?php echo $FadeIn_errors[0]; ?></strong></p>
  </div>
  <?php
}
if ($FadeIn_error_found == FALSE && strlen($FadeIn_success) > 0)
{
?>
  <div class="updated fade">
    <p><strong><?php echo $FadeIn_success; ?> <a href="<?php echo FADEIN_ADMIN_URL; ?>">Click here</a> to view the details</strong></p>
  </div>
  <?php
}
?>
<script language="javascript" type="text/javascript" src="<?php echo FADEIN_PLUGIN_URL; ?>/pages/noenter.js"></script>
<div class="form-wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2><?php _e('Fade in text news', 'wp-fade-in-text-news'); ?></h2>
	<form name="FadeIn_form" method="post" action="#" onsubmit="return FadeIn_submit()"  >
      <h3><?php _e('Edit news', 'wp-fade-in-text-news'); ?></h3>
	  <label for="tag-image"><?php _e('Enter the news/message', 'wp-fade-in-text-news'); ?></label>
      <textarea name="FadeIn_text" id="FadeIn_text" cols="100" rows="5"><?php echo esc_html(stripslashes($form['FadeIn_text'])); ?></textarea>
      <p><?php _e('We can enter HTML content in this textarea', 'wp-fade-in-text-news'); ?></p>
	  <label for="tag-link"><?php _e('Enter target link', 'wp-fade-in-text-news'); ?></label>
      <input name="FadeIn_link" type="text" id="FadeIn_link" value="<?php echo $form["FadeIn_link"]; ?>" size="102" maxlength="1024" />
      <p><?php _e('When someone clicks on the content, where do you want to send them', 'wp-fade-in-text-news'); ?></p>
      <label for="tag-select-gallery-group"><?php _e('Select fadein group', 'wp-fade-in-text-news'); ?></label>
      <select name="FadeIn_group" id="FadeIn_group">
	  <option value=''>Select</option>
	  <?php
		$sSql = "SELECT distinct(FadeIn_group) as FadeIn_group FROM `".WP_FadeIn_TABLE."` order by FadeIn_group";
		$myDistinctData = array();
		$arrDistinctDatas = array();
		$selected = "";
		$myDistinctData = $wpdb->get_results($sSql, ARRAY_A);
		$i = 0;
		foreach ($myDistinctData as $DistinctData)
		{
			$arrDistinctData[$i]["FadeIn_group"] = strtoupper($DistinctData['FadeIn_group']);
			$i = $i+1;
		}
		for($j=$i; $j<$i+5; $j++)
		{
			$arrDistinctData[$j]["FadeIn_group"] = "GROUP" . $j;
		}
		$arrDistinctData[$j+1]["FadeIn_group"] = "WIDGET";
		$arrDistinctData[$j+2]["FadeIn_group"] = "SAMPLE";
		$arrDistinctDatas = array_unique($arrDistinctData, SORT_REGULAR);
		foreach ($arrDistinctDatas as $arrDistinct)
		{
			if(strtoupper($form['FadeIn_group']) == strtoupper($arrDistinct["FadeIn_group"]) ) 
			{ 
				$selected = "selected='selected'"; 
			}
			?>
			<option value='<?php echo $arrDistinct["FadeIn_group"]; ?>' <?php echo $selected; ?>><?php echo strtoupper($arrDistinct["FadeIn_group"]); ?></option>
			<?php
			$selected = "";
		}
		?>
      </select>
      <p><?php _e('This is to group the message. Select your group from the list', 'wp-fade-in-text-news'); ?></p>
      <label for="tag-display-status"><?php _e('Display status', 'wp-fade-in-text-news'); ?></label>
      <select name="FadeIn_status" id="FadeIn_status">
        <option value=''>Select</option>
		<option value='YES' <?php if($form['FadeIn_status']=='YES') { echo 'selected="selected"' ; } ?>>Yes</option>
        <option value='NO' <?php if($form['FadeIn_status']=='NO') { echo 'selected="selected"' ; } ?>>No</option>
      </select>
      <p><?php _e('Do you want to show this message?', 'wp-fade-in-text-news'); ?></p>
	  <label for="tag-link"><?php _e('Display order', 'wp-fade-in-text-news'); ?></label>
      <input name="FadeIn_order" type="text" id="FadeIn_order" value="<?php echo $form["FadeIn_order"]; ?>" maxlength="2" />
      <p><?php _e('Please enter news display order in this box. Only number', 'wp-fade-in-text-news'); ?></p>
	  
	  <label for="tag-date"><?php _e('Expiration date', 'wp-fade-in-text-news'); ?></label>
      <input name="FadeIn_date" type="text" id="FadeIn_date" value="<?php echo substr($form['FadeIn_date'],0,10); ?>" maxlength="10" />
      <p><?php _e('Please enter the expiration date in this format YYYY-MM-DD', 'wp-fade-in-text-news'); ?></p>
	  
      <input name="FadeIn_id" id="FadeIn_id" type="hidden" value="">
      <input type="hidden" name="FadeIn_form_submit" value="yes"/>
      <p class="submit">
        <input name="publish" lang="publish" class="button-primary" value="<?php _e('Submit', 'wp-fade-in-text-news'); ?>" type="submit" />
        <input name="publish" lang="publish" class="button-primary" onclick="_FadeIn_redirect()" value="<?php _e('Cancel', 'wp-fade-in-text-news'); ?>" type="button" />
        <input name="Help" lang="publish" class="button-primary" onclick="_FadeIn_help()" value="<?php _e('Help', 'wp-fade-in-text-news'); ?>" type="button" />
      </p>
	  <?php wp_nonce_field('FadeIn_form_edit'); ?>
    </form>
</div>
</div>