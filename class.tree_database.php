<?php
/*
Copyright (c) 2010,2011 Arvind Shah
WP Pedigree Builder is released under the GNU General Public
License (GPL) http://www.gnu.org/licenses/gpl.txt
*/

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

$wppb_db_version = "1.0";

class tree_database {

	function save_member($id, $data) {
		global $wpdb;

		$member = $this->get_member($id);

		$data['data'] = $this->serialencode($data['data']);

		if (!empty($member)) {
			// row exists so update it...			
			if ($wpdb->update($this->get_tablename(), $data, array('postid' => $id), null, array( '%d' )) === false) {
			}
		} else {
			// row didn't exist so insert a new one...
			$wpdb->insert($this->get_tablename(), $data, array('%s','%s','%d','%d','%s'));
		}
	}
			
	function get_member($id) {
		global $wpdb;
		
		if (!empty($id)) {
			$query = "select id,postid,mother_postid,father_postid,dob,dod,gender,data,father,mother,name from ".$this->get_tablename()." where postid=".$wpdb->escape($id);
			
			$row = $wpdb->get_row($query);
			if (!empty($row->data)) {
				$row->name = get_the_title($id);
				$row->data = $this->serialdecode($row->data);
			} else {

			}
			return $row;
		} else {
			return null;
		}
	}

	public function serialencode ($var = array(), $recur = FALSE) {
		if ($recur) {
			foreach ($var as $k => $v) {
				if ( is_array($v) ) {
					$var[$k] = $this->serialencode($v, 1);
				} else {
					$var[$k] = base64_encode($v);
				}
			}
			return $var;
		} else {
			return serialize($this->serialencode($var, 1));
		}
	}
       
	public function serialdecode ($var = FALSE, $recur = FALSE) {
		if ($recur) {
			foreach ($var as $k => $v) {
				if ( is_array($v) ) {
					$var[$k] = $this->serialdecode($v, 1);
				} else {
					$var[$k] = stripslashes(base64_decode($v));
				}
			}
			return $var;
		} else {
			return $this->serialdecode(unserialize($var), 1);
		}
	}	

	function get_tablename() {
		global $wpdb;
		return $wpdb->prefix . "wppb_members"; 
	}
	function install_plugin () {
		global $wpdb;

		$table_name = $this->get_tablename(); 

		$sql = "CREATE TABLE ".$table_name." (
		  id int(10) unsigned NOT NULL AUTO_INCREMENT,
		  postid int(10) unsigned NOT NULL,
		  mother_postid int(10) unsigned NOT NULL,
		  father_postid int(10) unsigned NOT NULL,
		  dob date NOT NULL,
		  dod date NOT NULL,
		  gender varchar(45) NOT NULL,
		  data text NOT NULL,
		  father int(10) unsigned NOT NULL,
		  mother int(10) unsigned NOT NULL,
		  name varchar(255),
		  UNIQUE KEY (id)
		);";
		
		dbDelta($sql);

		add_option("wppb_db_version", $wppb_db_version);
	}

}


?>