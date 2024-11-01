<?php
/*
Copyright (c) 2010,2011 Arvind Shah
WP Pedigree Builder is released under the GNU General Public
License (GPL) http://www.gnu.org/licenses/gpl.txt
*/

class tree_options {
	
	function options_page() {
		if (function_exists('add_options_page')) {
			add_options_page('WP Pedigree Builder', 'WP Pedigree Builder', 10, 'wp-pedigree-builder', array($this, 'options_subpanel'));
		}
	}
	function options_subpanel() {
		global $wp_version;
		
		if (isset($_POST['update_options'])) {
			if ( function_exists('check_admin_referer') ) {
				check_admin_referer('wppb-action_options');
			}
	
			if ($_POST['tree_category_key'] != "")  {
				update_option('tree_category_key', stripslashes(strip_tags($_POST['tree_category_key'])));
			}
			if ($_POST['chart_page_link'] != "")  {
				update_option('chart_page_link', stripslashes(strip_tags($_POST['chart_page_link'])));
			}
			if ($_POST['wppb_date_format'] != "")  {
				update_option('wppb_date_format', stripslashes(strip_tags($_POST['wppb_date_format'])));
			}
			update_option('hide_posts_header', 		($_POST['hide_posts_header']=='Y')?'true':'false');
		
			update_option('showcreditlink', 			($_POST['showcreditlink']=='Y')?'true':'false');
			echo '<div class="updated"><p>Options saved.</p></div>';
		}
	
	 ?>
		<div class="wrap">
		<h2>WP Pedigree Builder Options</h2>
	
		<a href="http://www.esscotti.com/wp-pedigree-builder/"><img width="150" height="50" alt="Visit WP Pedigree Builder home" align="right" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/wp-pedigree-builder/logo.jpg"/></a>
	
		<form name="ft_main" method="post">
	<?php
		if (function_exists('wp_nonce_field')) {
			wp_nonce_field('wppb-action_options');
		}
		$plugloc = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
	?>
		<h3>General settings</h3>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="tree_category_key">Name of category for members (default: "Pedigree")</label></th>
				<td><input name="tree_category_key" type="text" id="tree_category_key" value="<?php echo tree_options::get_option('tree_category_key'); ?>" size="40" /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="chart_page_link">Link to pedigree chart page</label></th>
				<td><input name="chart_page_link" type="text" id="chart_page_link" value="<?php echo tree_options::get_option('chart_page_link'); ?>" size="40" /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="hide_posts_header">Hide info box on posts pages</label></th>
				<td><input name="hide_posts_header" type="checkbox" id="hide_posts_header" value="Y" <?php echo (tree_options::get_option('hide_posts_header')=='true')?' checked':''; ?> /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="wppb_date_format">Date format on info box</label></th>
				<td><input name="wppb_date_format" type="text" id="wppb_date_format" value="<?php echo tree_options::get_option('wppb_date_format'); ?>" size="40" /></td>
			</tr>
		</table>
	
		<h3>Credit link</h3>
	<p>If you use this plugin then we would be very grateful for some appreciation. <b>Appreciation makes us happy.</b> If you don't want to link to us from the bottom of your chart then please consider these other options - <b>i)</b> send us an <a target="_blank" href="http://www.esscotti.com/wp-pedigree-builder-plugin">email</a> and let us know about your website (that would inspire us), <b>ii)</b> include a link to <a target="_blank" href="http://www.esscotti.com">www.esscotti.com</a> from some other location of your site (that would help us feed our children), <b>iii)</b> Give us a good rating at the <a target="_blank" href="http://wordpress.org/extend/plugins/wp-pedigree-builder/">Wordpress plugin site</a>.</p>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="showcreditlink">Show powered-by link</label></th>
				<td><input name="showcreditlink" type="checkbox" id="showcreditlink" value="Y" <?php echo (tree_options::get_option('showcreditlink')=='true')?' checked':''; ?> /></td>
			</tr>
		</table>
	
		
		<p class="submit">
		<input type="hidden" name="action" value="update" />
		<input type="submit" name="update_options" class="button" value="<?php _e('Save Changes', 'Localization name') ?> &raquo;" />
		</p>
	
		</form>
		</div>
	<?php
	}


	static function get_option($option) {
		$value = get_option($option);

		if ($value !== false) { 
			return $value;
		}
		// Option did not exist in database so return default values...
		switch ($option) {
		case "chart_page_link":
			return '/pedigree-chart/';	// Default link to where the family tree sits
		case "wppb_date_format":
			return 'd/m/Y';		// Date format to use on public pages
		case "tree_category_key":
			return 'Pedigree';	// Default category for posts included in the tree
		case "hide_posts_header":
			return 'false';		// Whether or not to show table with bio data on posts page
		} 
		return '';
	}
		
}

?>