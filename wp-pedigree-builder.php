<?php
/**
 * @package WP Pedigree Builder
 * @author Arvind Shah
 * @version 1.1
 */
/*
Plugin Name: WP Pedigree Builder
Plugin URI: http://www.esscotti.com/wp-pedigree-builder/
Description: Pedigree chart plugin
Author: Arvind Shah
Version: 1.1
Author URI: http://www.esscotti.com/

Copyright (c) 2010,2011 Arvind Shah
WP Pedigree Builder is released under the GNU General Public
License (GPL) http://www.gnu.org/licenses/gpl.txt

*/

require_once('class.tree_database.php');
require_once('class.tree_options.php');
require_once('class.tree.php');

class wppb {	
	
	// Render the chart
	function render_chart($root='') {
	
		if (!empty($_GET['root'])) {
			$root = $_GET['root'];
		}

		if (!is_numeric($root)) {
			// find post by post title and assigns the post id to root
			$root = tree::get_id_by_name($root, $the_tree);
		}

		$the_tree = tree::get_tree($root);
			
		$out = '';

//		$plugloc = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
		$plugloc = ABSPATH . 'wp-content/plugins'.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
		$template = file_get_contents($plugloc . 'templates/pedigree-chart.html');

		$member = $the_tree[$root];

		// Level 0
		$template = str_replace('[::POSTID::]',$the_tree[0]->postid, $template);
		$permalink = get_permalink($the_tree[0]->postid);
		$name = '<a href="'.$permalink.'">'.$the_tree[0]->name.'</a>';
		$template = str_replace('[::NAME::]',	$name, $template);
		$template = str_replace('[::DOB::]',	$the_tree[0]->dob, $template);
		$template = str_replace('[::DOD::]', 	$the_tree[0]->dod, $template);
		$template = str_replace('[::GENDER::]',$the_tree[0]->gender, $template);
		$template = str_replace('[::MOTHER::]',$the_tree[0]->mother, $template);
		$template = str_replace('[::FATHER::]',$the_tree[0]->father, $template);
		
		$image = '<img width="110" height="70" src="'.$the_tree[0]->data['thumb1'].'">';
		$template = str_replace('[::THUMBIMG::]',	$image, $template);


		// Level 1
		$permalink = get_permalink($the_tree['f']->postid);
		$name = '<a href="'.$permalink.'">'.$the_tree['f']->name.'</a>';
		$template = str_replace('[::NAME_F::]',	$name, $template);
		$image = '<img width="110" height="70" src="'.$the_tree['f']->data['thumb1'].'">';
		$template = str_replace('[::THUMBIMG_F::]',	$image, $template);

		$permalink = get_permalink($the_tree['m']->postid);
		$name = '<a href="'.$permalink.'">'.$the_tree['m']->name.'</a>';
		$template = str_replace('[::NAME_M::]',	$name, $template);
		$image = '<img width="110" height="70" src="'.$the_tree['m']->data['thumb1'].'">';
		$template = str_replace('[::THUMBIMG_M::]',	$image, $template);

		// Level 2
		$permalink = get_permalink($the_tree['ff']->postid);
		$name = '<a href="'.$permalink.'">'.$the_tree['ff']->name.'</a>';
		$template = str_replace('[::NAME_FF::]',	$name, $template);
		$image = '<img width="110" height="70" src="'.$the_tree['ff']->data['thumb1'].'">';
		$template = str_replace('[::THUMBIMG_FF::]',	$image, $template);

		$permalink = get_permalink($the_tree['fm']->postid);
		$name = '<a href="'.$permalink.'">'.$the_tree['fm']->name.'</a>';
		$template = str_replace('[::NAME_FM::]',	$name, $template);
		$image = '<img width="110" height="70" src="'.$the_tree['fm']->data['thumb1'].'">';
		$template = str_replace('[::THUMBIMG_FM::]',	$image, $template);

		$permalink = get_permalink($the_tree['mf']->postid);
		$name = '<a href="'.$permalink.'">'.$the_tree['mf']->name.'</a>';
		$template = str_replace('[::NAME_MF::]',	$name, $template);
		$image = '<img width="110" height="70" src="'.$the_tree['mf']->data['thumb1'].'">';
		$template = str_replace('[::THUMBIMG_MF::]',	$image, $template);

		$permalink = get_permalink($the_tree['mm']->postid);
		$name = '<a href="'.$permalink.'">'.$the_tree['mm']->name.'</a>';
		$template = str_replace('[::NAME_MM::]',	$name, $template);
		$image = '<img width="110" height="70" src="'.$the_tree['mm']->data['thumb1'].'">';
		$template = str_replace('[::THUMBIMG_MM::]',	$image, $template);

		// Level 3
		$name = '<a href="'.get_permalink($the_tree['fff']->postid).'">'.$the_tree['fff']->name.'</a>';
		$template = str_replace('[::NAME_FFF::]',	$name, $template);

		$name = '<a href="'.get_permalink($the_tree['ffm']->postid).'">'.$the_tree['ffm']->name.'</a>';
		$template = str_replace('[::NAME_FFM::]',	$name, $template);

		$name = '<a href="'.get_permalink($the_tree['fmf']->postid).'">'.$the_tree['fmf']->name.'</a>';
		$template = str_replace('[::NAME_FMF::]',	$name, $template);

		$name = '<a href="'.get_permalink($the_tree['fmm']->postid).'">'.$the_tree['fmm']->name.'</a>';
		$template = str_replace('[::NAME_FMM::]',	$name, $template);

		$name = '<a href="'.get_permalink($the_tree['mff']->postid).'">'.$the_tree['mff']->name.'</a>';
		$template = str_replace('[::NAME_MFF::]',	$name, $template);

		$name = '<a href="'.get_permalink($the_tree['mfm']->postid).'">'.$the_tree['mfm']->name.'</a>';
		$template = str_replace('[::NAME_MFM::]',	$name, $template);

		$name = '<a href="'.get_permalink($the_tree['mmf']->postid).'">'.$the_tree['mmf']->name.'</a>';
		$template = str_replace('[::NAME_MMF::]',	$name, $template);

		$name = '<a href="'.get_permalink($the_tree['mmm']->postid).'">'.$the_tree['mmm']->name.'</a>';
		$template = str_replace('[::NAME_MMM::]',	$name, $template);

		$out .= $template;
	
		if (tree_options::get_option('showcreditlink') == 'true') {
			$out .= '<p style="text-align:left"><small>powered by <a target="_blank" href="http://www.esscotti.com/wp-pedigree-builder">WP Pedigree Builder</a></small></p>'."\n";
		}

		return $out;
	}

	// Render the list
	function render_list($category='') {
	
		if (!empty($_GET['category'])) {
			$category = $_GET['category'];
		} else { 
			if (empty($category)) {
				$category = tree_options::get_option('tree_category_key');
			}
		}

		$out = '';
		$posts = get_posts('category_name='.$category.'&numberposts=-1&orderby=title&order=asc');
		$plugloc = ABSPATH . 'wp-content/plugins'.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));

		if (!is_array($posts) || count($posts) == 0) {
			$template = file_get_contents($plugloc . 'templates/pedigree-list-empty.html');
			$out .= $template;	
		} else {
			$template = file_get_contents($plugloc . 'templates/pedigree-listitem.html');
			foreach ($posts as $post) {
				$post = get_post($post->ID);
				$listitem = str_replace('[::ID::]',$post->ID, $template);
				$permalink = get_permalink($post->ID);
				$listitem = str_replace('[::URL::]',$permalink, $listitem);
				$listitem = str_replace('[::NAME::]',$post->post_title, $listitem);
				$out .= $listitem;
			}
		}
		
		return $out;
	}

	// Render the form that goes onto the edit post/page screen...
	function edit_post_form() {
		global $post;
		echo '<div id="wppb_div" class="postbox">';
		echo '<h3>'.self::plugin_name.'</h3>';
		wp_nonce_field('save_wppb_info','wppb_nonce');
		echo '<div class="inside">';
		echo '<table>';
	
		if (($member = $this->database->get_member($post->ID)) != null) {
			$gender 	= $member->gender;
			$mother 	= $member->mother_postid;
			$father 	= $member->father_postid;
			$dob 		= $member->dob;
			$dod 		= $member->dod;
			$extra	= $member->data;
		} else {
			$gender 	= '';
			$moher 	= '';
			$father 	= '';
			$dob		= '';
			$dod 		= '';
			$extra	= array();
		}

		$posts 	= get_posts('category_name='.tree_options::get_option('tree_category_key').'&numberposts=-1&orderby=title&order=asc');
		$females = $posts;
		$males = $posts;
	?>
		<tr><td>Gender:</td><td> 
	    <select name="gender" id="gender">
	    <option value="" <?php if (empty($gender)) echo "selected=\"selected\""; ?>></option>
	    <option value="m" <?php if ($gender == "m") echo "selected=\"selected\""; ?>>Male</option>
	    <option value="f" <?php if ($gender == "f") echo "selected=\"selected\""; ?>>Female</option>
		</select></td></tr>
	    <tr><td>Born:</td><td><input class="dob" type="text" name="born" value="<?php echo $dob; ?>" id="born" size="80" /></td></tr>
	    <tr><td>Died:</td><td><input class="dod" type="text" name="died" value="<?php echo $dod; ?>" id="died" size="80" /></td></tr>
	    <tr><td>Mother:</td><td>
	    <select style="width:200px" name="mother" id="mother">
	    <option value="" <?php if (empty($mother)) echo "selected=\"selected\""; ?>> </option>
	<?php
		foreach ($females as $f) {
			echo '<option value="'.$f->ID.'" ';
			if ($f->ID == $mother) echo "selected=\"selected\"";
			echo '>'.$f->post_title.'</option>';
		}
	?>
		</select>
		</td></tr>
	
	    <tr><td>Father:</td><td>
	    <select style="width:200px" name="father" id="father">
	    <option value="" <?php if (empty($father)) echo "selected=\"selected\""; ?>> </option>
	<?php
		foreach ($males as $f) {
			echo '<option value="'.$f->ID.'" ';
			if ($f->ID == $father) echo "selected=\"selected\"";
			echo '>'.$f->post_title.'</option>';
		}
	?>
		</select>
		</td></tr>
	
		 <tr><td>Thumbnail:</td><td>
				<input id="thumb1" type="text" name="thumb1" size="36" value="<?php echo $extra['thumb1']; ?>" />
				<input id="thumb1_button" type="button" value="Upload Image" />
		 </td></tr>

	    </table>
	    
	<script>
		var $j = jQuery.noConflict();
		$j(function() {
			$j(".dob").datepicker({
				yearRange: "-100:+1",
				changeMonth: true,
				changeYear: true,
			   dateFormat: 'yy-mm-dd',
   			defaultDate: '<?php echo $dob; ?>'
			});
			$j(".dod").datepicker({
				yearRange: "-100:+1",
				changeMonth: true,
				changeYear: true,
			   dateFormat: 'yy-mm-dd',
   			defaultDate: '<?php echo $dod; ?>'
			});
			$j('#thumb1_button').click(function() {
				formfield = $j('#thumb1').attr('name');
				tb_show('', 'media-upload.php?type=image&TB_iframe=true');
				window.send_to_editor = function(html) {
					imgurl = $j('img',html).attr('src');
					$j('#thumb1').val(imgurl);
					tb_remove();
				}
				return false;
			});
		});
	</script>

	    </div>
	    </div>
	    <?php
	}



	// Function to deal with saving a post/page...
	function save_post_action($id) {
		global $wpdb;

		if (!empty($_POST)) {
//			if (check_admin_referer('save_wppb_info','wppb_nonce')) {
				if (($pid = wp_is_post_revision($id)) !== false) {
					$id = $pid;
				}
		
				$data = array();
				$data['postid'] = $id;
				$data['dob'] = stripslashes(strip_tags($_POST['born']));
				$data['dod'] = stripslashes(strip_tags($_POST['died']));
				$data['mother_postid'] = stripslashes(strip_tags($_POST['mother']));
				$data['father_postid'] = stripslashes(strip_tags($_POST['father']));
				$data['gender'] = stripslashes(strip_tags($_POST['gender']));
		
				$extra = array();
				$extra['thumb1'] = $_POST['thumb1'];
				$data['data'] = $extra;

				$this->database->save_member($id, $data);

//			} else {
//				echo "Nonce check failed!";
//			}
		}
	}



	
	// Function to deal with showing biodata on posts
	function add_post_header($content) {
		global $post;
	
		$category = tree_options::get_option('tree_category_key');
		$cats = get_the_category();	// get array of category objects that apply to this post
	
		$is_member = false;
		foreach ($cats as $cat) {
			if ($cat->name == $category) {
				$is_member = true;
				break;
			}	
		}
		if ($is_member) {
			// This post is a member post so do the work...

			if (($member = $this->database->get_member($post->ID)) != null) {

				$plugloc = ABSPATH . 'wp-content/plugins'.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
				$template = file_get_contents($plugloc . 'templates/post-header.html');
				$template = str_replace('[::POSTID::]',$post->ID, $template);
				$template = str_replace('[::NAME::]',	$post->post_title, $template);
				
				
				if (!empty($member->dob) && $member->dob != '0000-00-00') {
					$timestamp = strtotime($member->dob);
					$dateformat = tree_options::get_option('wppb_date_format');
					$dob = date_i18n($dateformat, $timestamp);
					$dob = '<span class="hdr">Born:</span> '.$dob;
				}
				if (!empty($member->dod) && $member->dod != '0000-00-00') {
					$timestamp = strtotime($member->dod);
					$dateformat = tree_options::get_option('wppb_date_format');
					$dod = date_i18n($dateformat, $timestamp);
					$dod = '<span class="hdr">Died:</span> '.$dod; 
				}
				
				$template = str_replace('[::DOB::]',	$dob, $template);
				$template = str_replace('[::DOD::]', 	$dod, $template);
				if ($member->gender == 'f') {
					$str = 'Female';
				} else if ($member->gender == 'm') {
					$str = 'Male';
				} else {
					$str = '';
				}
				$template = str_replace('[::GENDER::]',$str, $template);
				if (!empty($member->father_postid)) {
					$father = $this->database->get_member($member->father_postid);
					$template = str_replace('[::FATHER::]',$father->name, $template);
				} else {
					$template = str_replace('[::FATHER::]','not specified', $template);
				}					
				if (!empty($member->mother_postid)) {
					$mother = $this->database->get_member($member->mother_postid);
					$template = str_replace('[::MOTHER::]',$mother->name, $template);
				} else {
					$template = str_replace('[::MOTHER::]','not specifed', $template);
				}					
				
				$link = tree_options::get_option('chart_page_link');
				if (strpos($link, '?') === false) {
					$link .= '?root='.$post->ID;
				} else {
					$link .= '&root='.$post->ID;
				}
				$template = str_replace('[::PEDIGREE-CHART-LINK::]', $link, $template);
				
				$content = $template.$content;
			}
		}
			
		return $content;
	}

	function get_first_image ($postID) {					
		$args = array(
		'numberposts' => 1,
		'order'=> 'ASC',
		'post_mime_type' => 'image',
		'post_parent' => $postID,
		'post_status' => null,
		'post_type' => 'attachment'
		);
		
		$attachments = get_children( $args );		
		//print_r($attachments);
	
		if ($attachments) {
			foreach($attachments as $attachment) {
//				$image_attributes = wp_get_attachment_image_src( $attachment->ID, 'thumbnail' )  ? wp_get_attachment_image_src( $attachment->ID, 'thumbnail' ) : wp_get_attachment_image_src( $attachment->ID, 'full' );
				$image = wp_get_attachment_image_src($attachment->ID, 'full');
				return $image[0];
//				return wp_get_attachment_thumb_url($attachment->ID);
			}
		}
		return '';
	}

	
	function chart_shortcode($atts, $content=NULL) {
		$root = $atts['root'];
		$output = $this->render_chart($root);	
		return $output;
	}
	function list_shortcode($atts, $content=NULL) {
		$cat = $atts['category'];
		$output = $this->render_list($cat);	
		return $output;
	}
		
	/* Add javascripts and style sheets */
	function add_header_code() {
		$plugloc = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
		wp_enqueue_style('wppb-style', $plugloc.'css/styles.css');
		
		wp_enqueue_script('datepickerScript', $plugloc . 'js/jquery-ui-datepicker.min.js', array('jquery','jquery-ui-core'));		
		wp_enqueue_style('datepickerStyle', $plugloc . 'js/smoothness/jquery-ui-smoothness.css');
	}

	var $options;
	var $database;
	
	const plugin_name = 'WP Pedigree Builder';
	
   function __construct() {
		$this->options = new tree_options();
		$this->database = new tree_database();

		// Add (or not) filter that renders the post header..		
		if (tree_options::get_option('hide_posts_header') !== 'true') {
			add_filter('the_content', array($this, 'add_post_header'));
		}
		
		// Set up page and post short codes...
		add_shortcode('pedigree-chart', array($this, 'chart_shortcode'));

		// Set up page and post short codes...
		add_shortcode('pedigree-list', array($this, 'list_shortcode'));

		// Add admin menu option...
		add_action('admin_menu', array($this->options, 'options_page'));

		// Add javascripts and style sheets...	
		add_action('init', array($this, 'add_header_code'));

		// Add actions for saving posts...
		add_action('edit_post', array($this, 'save_post_action'));
		add_action('save_post', array($this, 'save_post_action'));
		add_action('publish_post', array($this, 'save_post_action'));
		
		add_action('edit_page_form', array($this, 'edit_post_form'));
		add_action('edit_form_advanced', array($this, 'edit_post_form'));
		add_action('simple_edit_form', array($this, 'edit_post_form'));
		
		register_activation_hook(__FILE__, array($this->database, 'install_plugin'));
		
   }

}

$wppb = new wppb();

?>