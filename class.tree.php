<?php
/*
Copyright (c) 2010,2011 Arvind Shah
WP Pedigree Builder is released under the GNU General Public
License (GPL) http://www.gnu.org/licenses/gpl.txt
*/

require_once('class.tree_options.php');

class tree {

	static function get_id_by_name($name, $tree) {
		if (is_array($tree)) {
			foreach ($tree as $node) {
				if ($node->name == $name) {
					return $node->post_id;
				}
			}
		}
		return -1;
	}
	static function get_node_by_id($id, $tree) {
		if (is_array($tree)) {
			foreach ($tree as $node) {
				if ($node->post_id == $id) {
					return $node;
				}
			}
		}
		return false;
	}

	static function get_ancestors_recursive($the_tree, $member, $num, $key) {
		global $wppb;

		if ($num == 0) {
			return;
		}

		if (isset($member) && !empty($member)) {
			$father = $wppb->database->get_member($member->father_postid);
			if ($father) {
				$the_tree[$father->postid] = $father;
				$the_tree[$key.'f'] = $father;
				self::get_ancestors_recursive(&$the_tree, $father, $num-1, $key.'f');
			}

			$mother = $wppb->database->get_member($member->mother_postid);
			if ($mother) {
				$the_tree[$mother->postid] = $mother;
				$the_tree[$key.'m'] = $mother;
				self::get_ancestors_recursive(&$the_tree, $mother, $num-1, $key.'m');
			}
		}		
	}

	/* Load and return the tree from the database. */
	static function get_tree($root='') {
		global $wpdb, $wppb;
	
		if (empty($root)) {
			return array();
		}
		
		$the_tree = array();

		$the_tree[$root] = $wppb->database->get_member($root);
		$the_tree[0] = $the_tree[$root];

		self::get_ancestors_recursive(&$the_tree, $the_tree[$root], 3, '');
		
		return $the_tree;
	}	
	
	
}







?>