<?php 
/**
 * All In One Course Review
 *
 * @package       AIOCR
 * @author        Vishavjeet
 * @license       gplv2-or-later
 * @version       1.0.0
 * */
if ( ! defined( 'ABSPATH' ) ) exit;


class AIOCR_Plugin_Activate{

	private $tableName = 'aiocr_course_review';
    public function __construct() {

    	global $wpdb;
		$charset = $wpdb->get_charset_collate();
		$table_Name = $wpdb->prefix.$this->tableName;
		$sqlQuery = " CREATE TABLE IF NOT EXISTS $table_Name (
			id INT(5) NOT Null auto_increment,
			first_name varchar (30) NOT Null,
			last_name varchar (30) NOT Null,
			email varchar (30) NOT Null,
			phoneNumber BIGINT(15) NOT Null,
			primary key (id),
			UNIQUE(email)
		)$charset;";
		require_once (ABSPATH.'wp-admin/includes/upgrade.php');
		dbDelta($sqlQuery);
    }
}
$create_table = new AIOCR_Plugin_Activate();?>