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

// Create Class for display review form on front-end
class Lcrp_Review_Form_Data{

    public $ajax_data;

    public function __construct() {        
    }
    /**
     * Display the list table page
     *
     * @return Void
     */
    public function lcrp_ajax_form_data($lcrp_ajax_data){
        if ( !wp_verify_nonce( $_REQUEST['lcrp_generate_nonce'], "lcrp_review_form_display")) {
            exit("No More Fun Please!!!!");
        }
        $course_id = $_POST['lcrp-current-course-id'];
        $lcrp_course_rating = $_POST['lcrp-rating'];

        if(isset($course_id) && is_numeric($course_id) && $course_id > 0 ){
            
            $review_title  = isset( $_POST['lcrp-review-heading'] ) ? sanitize_text_field( $_POST['lcrp-review-heading'] ) : '';
            
            if ($review_title !='') {

                $review_description = isset( $_POST['lcrp-review-description'] ) ? sanitize_text_field( $_POST['lcrp-review-description'] ) : '';

                if ($review_description !='') {
                    $loggediUserID = get_current_user_id();

                    if(isset($loggediUserID) && is_numeric($loggediUserID) && $loggediUserID > 0 ){
                        
                        if(isset($lcrp_course_rating)
                            && is_numeric($lcrp_course_rating) 
                            && $lcrp_course_rating > 0 ){

                            global $wpdb;
                            $table_name = $wpdb->prefix. 'lcrp_course_review';
                            $DataBaseResult = $wpdb->get_var("SELECT id FROM $table_name WHERE author_id = $loggediUserID AND review_ld_item_id = $course_id");

                            if (empty($DataBaseResult)) {
                                $wpdb->insert( $table_name, array(
                                    'author_id' => $loggediUserID,
                                    'review_details' => serialize(array('review-heading' => $review_title, 'review-description' => $review_description)),
                                    'review_rating' => $lcrp_course_rating, 
                                    'review_ld_item_id' => $course_id),
                                    array( '%d', '%s', '%d', '%d') 
                                );

                                /**
                                 * 
                                 * 
                                 *   After save review in the database 
                                 * 
                                */
                                $insert_id = $wpdb->insert_id;
                                do_action( 'lcrp_new_review_data', $insert_id );
                                if ($insert_id) {
                                    $result = esc_html__('Thank You for review', 'learndash-course-review-pro' );
                                }else{
                                    $result = esc_html__('Sytem Error !!!!', 'learndash-course-review-pro' );
                                }
                            }else{
                                global $wpdb;
                                $table_name  = $wpdb->prefix."lcrp_course_review";
                                $review_details = serialize(array('review-heading' => $review_title, 'review-description' => $review_description));


                                $update_review_rating =   $wpdb->query(
                                                    $wpdb->prepare( "UPDATE $table_name SET
                                                    review_details = %s,
                                                    review_rating =%s WHERE id = %d", 
                                                    $review_details,
                                                    $lcrp_course_rating,
                                                    $DataBaseResult
                                                ) 
                                            );
                                if ($update_review_rating) {
                                    /**
                                     * 
                                     * 
                                     *   Update Existing review
                                     * 
                                    */

                                    do_action( 'lcrp_update_review_data');


                                    $result = esc_html__('Thank You for review', 'learndash-course-review-pro' );
                                }else{
                                    $result = esc_html__('Sytem Error !!!!', 'learndash-course-review-pro' );
                                }
                            }
                        }else{
                            $result = esc_html__('Please give rating', 'learndash-course-review-pro' );
                        }
                    }else{
                        $result = esc_html__('No More Fun Please!!!!', 'learndash-course-review-pro' );
                    }
                }else{
                    $result = esc_html__('Review Description is empty.', 'learndash-course-review-pro' );
                }
            }else{
                $result = esc_html__('Review Heading is empty.', 'learndash-course-review-pro' );                    
            }
        }else{
            $result = esc_html__('No More Fun Please!!!!', 'learndash-course-review-pro' );                
        }
        wp_send_json_success( $result );
        wp_die(); 
    }
} 
?>