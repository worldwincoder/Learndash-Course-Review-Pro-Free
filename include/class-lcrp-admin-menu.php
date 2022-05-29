<?php 
/**
 * LearnDash Course Review Pro
 *
 * @package       LCRP
 * @author        WorldWin Coder Pvt Ltd
 * @license       gplv2-or-later
 * @version       1.0.0
 *
 * */
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Created Class for the admin
 *
 */
class Lcrp_Admin_Menu{

    public $menu_slug = 'learndash-course-review-pro';
    

    public function __construct() {

        /**
         * Added 'LD Course Review Pro' page to admin menu
         *
         */

        add_menu_page(
            esc_html__( "LD Course Review Pro", $this->menu_slug ),
            'LD Course Review Pro',
            'manage_options',
            'lcrp_setting',
            array($this,'lcrp_all_reviews_page'),'dashicons-star-empty',66
        );

        /**
         * Added 'All Reviews' sub-page to admin menu
         *
         */

        add_submenu_page(
            'lcrp_setting',
            esc_html__( "All Reviews", $this->menu_slug ),
            'All Reviews',
            'manage_options',
            'lcrp_setting',
            array($this,'lcrp_all_reviews_page') 
        );

        /**
         * Added 'Pending Review' sub-page to admin menu
         *
         */

        add_submenu_page(
            'lcrp_setting',
            esc_html__( "Pending Reviews", $this->menu_slug ),
            'Pending Reviews',
            'manage_options',
            'lcrp_pending_review',
            array($this,'lcrp_all_pending_reviews') 
        );

        /**
         * Added 'Trash Reviews' sub-page to admin menu
         *
         */
        add_submenu_page(
            'lcrp_setting',
            esc_html__( "Trash Reviews", $this->menu_slug ),
            'Trash Reviews',
            'manage_options',
            'lcrp_trash_review',
            array($this,'lcrp_all_trash_reviews') 
        );

        /**
         * Added 'Settings' sub-page to admin menu
         *
         */
        add_submenu_page(
            'lcrp_setting',
            esc_html__( "Settings", $this->menu_slug ),
            'Settings',
            'manage_options',
            'lcrp_admin_settings',
            array($this,'lcrp_admin_settings_fun') 
        );
    }

    /**
     * All review listing,
     *
     */
    public function  lcrp_all_reviews_page(){

        wp_enqueue_style('lcrp_admin_styles');
        wp_enqueue_script( 'jquery' ); 
        wp_enqueue_script('lcrp_admin_scripts');
        global $wpdb;
        $table_name = $wpdb->prefix. 'lcrp_course_review';


        $sql = ("SELECT id, review_details,review_rating,date, review_ld_item_id  FROM ".$table_name." ORDER BY id ASC");
        $feed_back_data_array = $wpdb->get_results($sql);
        $table_result = '<div class="lcrp-admin-table-wrapper"><table id="lcrp-admin-review-table">';
            $table_result .= '<thead>';
                $table_result .= '<tr>';
                $table_result .= '  <th>'.esc_html__("ID", "learndash-course-review-pro" ).'</th>';
                $table_result .= '  <th>'.esc_html__("Review Heading", "learndash-course-review-pro" ).'</th>';
                $table_result .= '  <th>'.esc_html__("Review Description", "learndash-course-review-pro" ).'</th>';
                $table_result .= '  <th>'.esc_html__("Rating", "learndash-course-review-pro" ).'</th>';
                $table_result .= '  <th>'.esc_html__("Date", "learndash-course-review-pro" ).'</th>';
                $table_result .= '  <th>'.esc_html__("Course", "learndash-course-review-pro" ). '</th>';
                $table_result .= '</tr>';
            $table_result .= '</thead>';
            $table_result .= '<tbody>';
        if (empty($feed_back_data_array)) {

            $table_result .= '<tr><td colspan="6"> '.esc_html__( 'No Feedback & Review Found', 'learndash-course-review-pro' ).'</td></tr>';
        }else{

            foreach ($feed_back_data_array as $key => $value) {

                $review_details_array = unserialize($value->review_details);
                $review_heading = esc_html__($review_details_array['review-heading'], 'learndash-course-review-pro' ); 
                $review_description = esc_html__($review_details_array['review-description'], 'learndash-course-review-pro' );
                $table_data_array  =  '<tr>';
                $table_data_array .=  '<td>'.esc_html__( $value->id, 'learndash-course-review-pro' ).'</td>';
                $table_data_array .=  '<td>'.esc_html( stripslashes($review_heading), 'learndash-course-review-pro' ).'</td>';
                $table_data_array .=  '<td>'.esc_html( stripslashes($review_description), 'learndash-course-review-pro' ).'</td>';
                $table_data_array .=  '<td>'.$value->review_rating.' | '.$this->admin_get_rating_count($value->review_rating).'</td>';
                $table_data_array .=  '<td>'.esc_html__( $value->date, 'learndash-course-review-pro' ).'</td>';
                $table_data_array .=  '<td><a class="lcrp-admin-view-course" target="_blank" href="'.get_post_permalink($value->review_ld_item_id,false,false).'" > <span class="dashicons dashicons-visibility"></span></a></td>';
                $table_data_array .=  '</tr>';
                $table_result .= $table_data_array;
            }           
        }
        $table_result .= '</tbody></table></div>';

        echo $table_result;
    }

    public function lcrp_all_pending_reviews(){ 

        $lcrp_get_pro_link = 'https://worldwincoder.com/product/learndash-course-review-pro/';
        echo '<h2><a href="'.$lcrp_get_pro_link.'" target="_blank">Upgrade to LearnDash Course Review Pro</a></h2>';
    }

    public function lcrp_all_trash_reviews(){
        $lcrp_get_pro_link = 'https://worldwincoder.com/product/learndash-course-review-pro/';
        echo '<h2><a href="'.$lcrp_get_pro_link.'" target="_blank">Upgrade to LearnDash Course Review Pro</a></h2>';
    }

    public function lcrp_admin_settings_fun(){

        $lcrp_get_pro_link = 'https://worldwincoder.com/product/learndash-course-review-pro/';
        echo '<h2><a href="'.$lcrp_get_pro_link.'" target="_blank">Upgrade to LearnDash Course Review Pro</a></h2>';
    }

    /**
     * This will return count of star
     *
     */
    public function admin_get_rating_count(int $rating_count)
    {
        if ($rating_count == 1) {

            $result = ' <span class=" lcrp-icon dashicons dashicons-star-filled"></span><span class=" lcrp-icon dashicons dashicons-star-empty"></span><span class=" lcrp-icon dashicons dashicons-star-empty"></span><span class=" lcrp-icon dashicons dashicons-star-empty"></span><span class=" lcrp-icon dashicons dashicons-star-empty"></span>';
        }elseif ($rating_count == 2) {

            $result = ' <span class=" lcrp-icon dashicons dashicons-star-filled"></span><span class=" lcrp-icon dashicons dashicons-star-filled"></span><span class=" lcrp-icon dashicons dashicons-star-empty"></span><span class=" lcrp-icon dashicons dashicons-star-empty"></span><span class=" lcrp-icon dashicons dashicons-star-empty"></span>';
        }elseif ($rating_count == 3) {

            $result = ' <span class=" lcrp-icon dashicons dashicons-star-filled"></span><span class=" lcrp-icon dashicons dashicons-star-filled"></span><span class=" lcrp-icon dashicons dashicons-star-filled"></span><span class=" lcrp-icon dashicons dashicons-star-empty"></span><span class=" lcrp-icon dashicons dashicons-star-empty"></span>';
        }elseif ($rating_count == 4) {

            $result = ' <span class=" lcrp-icon dashicons dashicons-star-filled"></span><span class=" lcrp-icon dashicons dashicons-star-filled"></span><span class=" lcrp-icon dashicons dashicons-star-filled"></span><span class=" lcrp-icon dashicons dashicons-star-filled"></span><span class=" lcrp-icon dashicons dashicons-star-empty"></span>';
        }elseif ($rating_count == 5) {

            $result = ' <span class=" lcrp-icon dashicons dashicons-star-filled"></span><span class=" lcrp-icon dashicons dashicons-star-filled"></span><span class=" lcrp-icon dashicons dashicons-star-filled"></span><span class=" lcrp-icon dashicons dashicons-star-filled"></span><span class=" lcrp-icon dashicons dashicons-star-filled"></span>';
        }else{

            $result = '<span class=" lcrp-icon dashicons dashicons-star-empty"></span><span class=" lcrp-icon dashicons dashicons-star-empty"></span><span class=" lcrp-icon dashicons dashicons-star-empty"></span><span class=" lcrp-icon dashicons dashicons-star-empty"></span><span class=" lcrp-icon dashicons dashicons-star-empty"></span>';
        }
        return $result;
    }
}?>