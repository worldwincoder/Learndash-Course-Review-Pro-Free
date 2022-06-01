<?php 
/**
 * All In One Course Review
 *
 * @package       AIOCR
 * @author        WorldWin Coder Pvt Ltd
 * @license       gplv2-or-later
 * @version       1.0.0
 *
 * */
if ( ! defined( 'ABSPATH' ) ) exit;

class AIOCR_Admin_Menu{

    protected $menu_slug = AIOCR;
    private $tableName = 'aiocr_course_review';
 
    public function __construct() {
        add_action( 'admin_enqueue_scripts',
            array($this, 'aiocr_admin_script')
        );  

        add_action('admin_menu',
            array($this, 'aiocr_admin_menu')
        );
    }

    public function aiocr_admin_script() {

        ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.0/css/jquery.dataTables.css">
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.0/js/jquery.dataTables.js"></script>

        <?php
        $aiocr_plugin_path = trailingslashit( WP_PLUGIN_URL . '/' . basename( dirname( __DIR__ ) ) );
        wp_enqueue_style('aiocr_admin_styles');
        wp_enqueue_script('aiocr_admin_scripts');

        wp_register_style('aiocr_admin_styles', 

            $aiocr_plugin_path.'assets/admin/css/aiocr-admin.css',
        );
        wp_register_script('aiocr_admin_scripts', 

            $aiocr_plugin_path.'assets/admin/js/aiocr-admin.js',
            array('jquery'),time(), true
        );
    }

    public function aiocr_admin_menu() {

        if (!class_exists( 'SFWD_LMS' ) ) {
            add_action('admin_notices', 
                array($this, 'aiocr_admin_notice')
            );
            deactivate_plugins( AIOCR_PLUGIN_BASENAME_FILE );
            return;
        }else{

            /**
             * Added 'LD Course Review Pro' page to admin menu
             *
             */

            add_menu_page(
                esc_html__( "All In One Course Review", $this->menu_slug ),
                'All In One Course Review',
                'manage_options',
                'aiocr_all_review',
                array($this,'aiocr_all_reviews_page'),'dashicons-star-empty',66
            );

            /**
             * Added 'All Reviews' sub-page to admin menu
             *
             */

            add_submenu_page(
                'aiocr_all_review',
                esc_html__( "All Reviews", $this->menu_slug ),
                'All Reviews',
                'manage_options',
                'aiocr_all_review',
                array($this,'aiocr_all_reviews_page') 
            );

            /**
             * Added 'Pending Review' sub-page to admin menu
             *
             */

            add_submenu_page(
                'aiocr_all_review',
                esc_html__( "Pending Reviews", $this->menu_slug ),
                'Pending Reviews',
                'manage_options',
                'aiocr_pending_review',
                array($this,'aiocr_all_pending_reviews') 
            );

            /**
             * Added 'Trash Reviews' sub-page to admin menu
             *
             */
            add_submenu_page(
                'aiocr_all_review',
                esc_html__( "Trash Reviews", $this->menu_slug ),
                'Trash Reviews',
                'manage_options',
                'aiocr_trash_review',
                array($this,'aiocr_all_trash_reviews') 
            );

            /**
             * Added 'Settings' sub-page to admin menu
             *
             */
            add_submenu_page(
                'aiocr_all_review',
                esc_html__( "Settings", $this->menu_slug ),
                'Settings',
                'manage_options',
                'aiocr_settings',
                array($this,'aiocr_admin_settings_fun') 
            );
        }
    }

    public function aiocr_admin_notice() {
        echo '<div class="error"><p>'.esc_html__( "All In One Course Review Plugin not activated. Please activate LearnDash plugin!", $this->menu_slug ).'</p></div>';
    }

    /**
     * All review listing,
     *
     */
    public function  aiocr_all_reviews_page(){

        echo plugins_url();

        wp_enqueue_style('aiocr_admin_styles');
        wp_enqueue_script( 'jquery' ); 
        wp_enqueue_script('aiocr_admin_scripts');
        
        global $wpdb;
        $tableName = $wpdb->prefix.$this->tableName;


        $sql = ("SELECT id, review_details,review_rating,date, review_ld_item_id  FROM ".$tableName." ORDER BY id ASC");
        $feed_back_data_array = $wpdb->get_results($sql);
        $table_result = '<div class="aiocr-admin-table-wrapper"><table id="aiocr-admin-review-table">';
            $table_result .= '<thead>';
                $table_result .= '<tr>';
                $table_result .= '  <th>'.esc_html__("ID", AIOCR ).'</th>';
                $table_result .= '  <th>'.esc_html__("Review Heading", AIOCR ).'</th>';
                $table_result .= '  <th>'.esc_html__("Review Description", AIOCR ).'</th>';
                $table_result .= '  <th>'.esc_html__("Rating", AIOCR ).'</th>';
                $table_result .= '  <th>'.esc_html__("Date", AIOCR ).'</th>';
                $table_result .= '  <th>'.esc_html__("Course", AIOCR ). '</th>';
                $table_result .= '</tr>';
            $table_result .= '</thead>';
            $table_result .= '<tbody>';
        if (empty($feed_back_data_array)) {

            $table_result .= '<tr><td colspan="6"> '.esc_html__( 'No Feedback & Review Found', AIOCR ).'</td></tr>';
        }else{

            foreach ($feed_back_data_array as $key => $value) {

                $review_details_array = unserialize($value->review_details);
                $review_heading = esc_html__($review_details_array['review-heading'], AIOCR ); 
                $review_description = esc_html__($review_details_array['review-description'], AIOCR );
                $table_data_array  =  '<tr>';
                $table_data_array .=  '<td>'.esc_html__( $value->id, AIOCR ).'</td>';
                $table_data_array .=  '<td>'.esc_html( stripslashes($review_heading), AIOCR ).'</td>';
                $table_data_array .=  '<td>'.esc_html( stripslashes($review_description), AIOCR ).'</td>';
                $table_data_array .=  '<td>'.$value->review_rating.' | '.$this->admin_get_rating_count($value->review_rating).'</td>';
                $table_data_array .=  '<td>'.esc_html__( $value->date, AIOCR ).'</td>';
                $table_data_array .=  '<td><a class="aiocr-admin-view-course" target="_blank" href="'.get_post_permalink($value->review_ld_item_id,false,false).'" > <span class="dashicons dashicons-visibility"></span></a></td>';
                $table_data_array .=  '</tr>';
                $table_result .= $table_data_array;
            }           
        }
        $table_result .= '</tbody></table></div>';

        echo $table_result;
    }

    public function aiocr_all_pending_reviews(){ 

        $aiocr_get_pro_link = 'https://worldwincoder.com/product/learndash-course-review-pro/';
        echo '<h2><a href="'.$aiocr_get_pro_link.'" target="_blank">Upgrade to All In One Course Review</a></h2>';
    }

    public function aiocr_all_trash_reviews(){
        $aiocr_get_pro_link = 'https://worldwincoder.com/product/learndash-course-review-pro/';
        echo '<h2><a href="'.$aiocr_get_pro_link.'" target="_blank">Upgrade to All In One Course Review</a></h2>';
    }

    public function aiocr_admin_settings_fun(){

        $aiocr_get_pro_link = 'https://worldwincoder.com/product/learndash-course-review-pro/';
        echo '<h2><a href="'.$aiocr_get_pro_link.'" target="_blank">Upgrade to All In One Course Review</a></h2>';
    }

    /**
     * This will return count of star
     *
     */
    public function admin_get_rating_count(int $rating_count)
    {
        if ($rating_count == 1) {

            $result = ' <span class=" aiocr-icon dashicons dashicons-star-filled"></span><span class=" aiocr-icon dashicons dashicons-star-empty"></span><span class=" aiocr-icon dashicons dashicons-star-empty"></span><span class=" aiocr-icon dashicons dashicons-star-empty"></span><span class=" aiocr-icon dashicons dashicons-star-empty"></span>';
        }elseif ($rating_count == 2) {

            $result = ' <span class=" aiocr-icon dashicons dashicons-star-filled"></span><span class=" aiocr-icon dashicons dashicons-star-filled"></span><span class=" aiocr-icon dashicons dashicons-star-empty"></span><span class=" aiocr-icon dashicons dashicons-star-empty"></span><span class=" aiocr-icon dashicons dashicons-star-empty"></span>';
        }elseif ($rating_count == 3) {

            $result = ' <span class=" aiocr-icon dashicons dashicons-star-filled"></span><span class=" aiocr-icon dashicons dashicons-star-filled"></span><span class=" aiocr-icon dashicons dashicons-star-filled"></span><span class=" aiocr-icon dashicons dashicons-star-empty"></span><span class=" aiocr-icon dashicons dashicons-star-empty"></span>';
        }elseif ($rating_count == 4) {

            $result = ' <span class=" aiocr-icon dashicons dashicons-star-filled"></span><span class=" aiocr-icon dashicons dashicons-star-filled"></span><span class=" aiocr-icon dashicons dashicons-star-filled"></span><span class=" aiocr-icon dashicons dashicons-star-filled"></span><span class=" aiocr-icon dashicons dashicons-star-empty"></span>';
        }elseif ($rating_count == 5) {

            $result = ' <span class=" aiocr-icon dashicons dashicons-star-filled"></span><span class=" aiocr-icon dashicons dashicons-star-filled"></span><span class=" aiocr-icon dashicons dashicons-star-filled"></span><span class=" aiocr-icon dashicons dashicons-star-filled"></span><span class=" aiocr-icon dashicons dashicons-star-filled"></span>';
        }else{

            $result = '<span class=" aiocr-icon dashicons dashicons-star-empty"></span><span class=" aiocr-icon dashicons dashicons-star-empty"></span><span class=" aiocr-icon dashicons dashicons-star-empty"></span><span class=" aiocr-icon dashicons dashicons-star-empty"></span><span class=" aiocr-icon dashicons dashicons-star-empty"></span>';
        }
        return $result;
    }
}
$aiocrAdminMenu = new AIOCR_Admin_Menu(); ?>