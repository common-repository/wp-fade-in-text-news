<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
// Form submitted, check the data
if (isset($_POST['frm_FadeIn_display']) && $_POST['frm_FadeIn_display'] == 'yes')
{
	$did = isset($_GET['did']) ? $_GET['did'] : '0';
	if(!is_numeric($did)) { die('<p>Are you sure you want to do this?</p>'); }
	
	$FadeIn_success = '';
	$FadeIn_success_msg = FALSE;
	
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
		// Form submitted, check the action
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['did']) && $_GET['did'] != '')
		{
			//	Just security thingy that wordpress offers us
			check_admin_referer('FadeIn_form_show');
			
			//	Delete selected record from the table
			$sSql = $wpdb->prepare("DELETE FROM `".WP_FadeIn_TABLE."`
					WHERE `FadeIn_id` = %d
					LIMIT 1", $did);
			$wpdb->query($sSql);
			
			//	Set success message
			$FadeIn_success_msg = TRUE;
			$FadeIn_success = __('Selected record was successfully deleted.', 'wp-fade-in-text-news');
		}
	}
	
	if ($FadeIn_success_msg == TRUE)
	{
		?><div class="updated fade"><p><strong><?php echo $FadeIn_success; ?></strong></p></div><?php
	}
}
?>
<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-post"></div>
    <h2><?php _e('Fade in text news', 'wp-fade-in-text-news'); ?>
	<a class="add-new-h2" href="<?php echo FADEIN_ADMIN_URL; ?>&amp;ac=add"><?php _e('Add New', 'wp-fade-in-text-news'); ?></a></h2>
    <div class="tool-box">
	<?php
		$sSql = "SELECT * FROM `".WP_FadeIn_TABLE."` order by FadeIn_id desc";
		$myData = array();
		$myData = $wpdb->get_results($sSql, ARRAY_A);
		?>
		<form name="frm_FadeIn_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
			<th scope="col"><?php _e('Message/News', 'wp-fade-in-text-news'); ?></th>
            <th scope="col"><?php _e('Group/Type', 'wp-fade-in-text-news'); ?></th>
			<th scope="col"><?php _e('Display Status', 'wp-fade-in-text-news'); ?></th>
			<th scope="col"><?php _e('Order', 'wp-fade-in-text-news'); ?></th>
			<th scope="col"><?php _e('Expiration', 'wp-fade-in-text-news'); ?></th>
          </tr>
        </thead>
		<tfoot>
          <tr>
			<th scope="col"><?php _e('Message/News', 'wp-fade-in-text-news'); ?></th>
            <th scope="col"><?php _e('Group/Type', 'wp-fade-in-text-news'); ?></th>
			<th scope="col"><?php _e('Display Status', 'wp-fade-in-text-news'); ?></th>
			<th scope="col"><?php _e('Order', 'wp-fade-in-text-news'); ?></th>
			<th scope="col"><?php _e('Expiration', 'wp-fade-in-text-news'); ?></th>
          </tr>
        </tfoot>
		<tbody>
			<?php 
			$i = 0;
			$displayisthere = FALSE;
			if(count($myData) > 0)
			{
				foreach ($myData as $data)
				{
					if($data['FadeIn_status'] == 'YES') 
					{
						$displayisthere = TRUE; 
					}
					?>
					<tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
					  <td><?php echo stripslashes($data['FadeIn_text']); ?>
						<div class="row-actions">
							<span class="edit"><a title="Edit" href="<?php echo FADEIN_ADMIN_URL; ?>&amp;ac=edit&amp;did=<?php echo $data['FadeIn_id']; ?>">Edit</a> | </span>
							<span class="trash"><a onClick="javascript:_FadeIn_delete('<?php echo $data['FadeIn_id']; ?>')" href="javascript:void(0);">Delete</a></span> 
						</div>
					  </td>
						<td><?php echo esc_html(stripslashes($data['FadeIn_group'])); ?></td>
						<td><?php echo $data['FadeIn_status']; ?></td>
						<td><?php echo $data['FadeIn_order']; ?></td>
						<td><?php echo substr($data['FadeIn_date'],0,10); ?></td>
					</tr>
					<?php 
					$i = $i+1; 
				}
			}
			else
			{
				?><tr><td colspan="5" align="center"><?php _e('No records available', 'wp-fade-in-text-news'); ?></td></tr><?php 
			}
			?>
		</tbody>
        </table>
		<?php wp_nonce_field('FadeIn_form_show'); ?>
		<input type="hidden" name="frm_FadeIn_display" value="yes"/>
      </form>	
	  <div class="tablenav bottom">
		<a href="<?php echo FADEIN_ADMIN_URL; ?>&amp;ac=add"><input class="button action" type="button" value="<?php _e('Add New', 'wp-fade-in-text-news'); ?>" /></a>
		<a href="<?php echo FADEIN_ADMIN_URL; ?>&amp;ac=set"><input class="button action" type="button" value="<?php _e('Widget setting', 'wp-fade-in-text-news'); ?>" /></a>
		<a target="_blank" href="<?php echo FADEIN_FAV; ?>"><input class="button action" type="button" value="<?php _e('Help', 'wp-fade-in-text-news'); ?>" /></a>
		<a target="_blank" href="<?php echo FADEIN_FAV; ?>"><input class="button button-primary" type="button" value="<?php _e('Short Code', 'wp-fade-in-text-news'); ?>" /></a>
	  </div>
	</div>
</div>