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

// Create Class for display review form on front-end
class AIOCR_Review_Form_Data{

    public function __construct() {
        
        add_action('wp_ajax_aiocr_review_form_submitted',
            array( $this,'aiocr_review_form_submitted')
        );
        add_action('wp_ajax_nopriv_aiocr_review_form_submitted',
            array( $this,'aiocr_review_form_submitted')
        );
    }
    /**
     * Display the list table page
     *
     * @return Void
     */
    public function aiocr_review_form_submitted(){

        if ( !wp_verify_nonce( $_REQUEST['aiocr_generate_nonce'], "aiocr_review_form_display")) {
            exit("No More Fun Please!!!!");
        }
        $course_id = $_POST['aiocr-current-course-id'];
        $aiocr_course_rating = $_POST['aiocr-rating'];

        if(isset($course_id) && is_numeric($course_id) && $course_id > 0 ){
            
            $review_title  = isset( $_POST['aiocr-review-heading'] ) ? sanitize_text_field( $_POST['aiocr-review-heading'] ) : '';
            
            if ($review_title !='') {

                $review_description = isset( $_POST['aiocr-review-description'] ) ? sanitize_text_field( $_POST['aiocr-review-description'] ) : '';

                if ($review_description !='') {
                    $loggediUserID = get_current_user_id();

                    if(isset($loggediUserID) && is_numeric($loggediUserID) && $loggediUserID > 0 ){
                        
                        if(isset($aiocr_course_rating)
                            && is_numeric($aiocr_course_rating) 
                            && $aiocr_course_rating > 0 ){

                            global $wpdb;
                            $table_name = $wpdb->prefix. 'aiocr_course_review';
                            $DataBaseResult = $wpdb->get_var("SELECT id FROM $table_name WHERE author_id = $loggediUserID AND review_ld_item_id = $course_id");

                            if (empty($DataBaseResult)) {
                                $wpdb->insert( $table_name, array(
                                    'author_id' => $loggediUserID,
                                    'review_details' => serialize(array('review-heading' => $review_title, 'review-description' => $review_description)),
                                    'review_rating' => $aiocr_course_rating, 
                                    'review_ld_item_id' => $course_id),
                                    array( '%d', '%s', '%d', '%d') 
                                );

                                /**
                                 * 
                                 *   After save review in the database 
                                 * 
                                */
                                $insert_id = $wpdb->insert_id;
                                do_action( 'aiocr_new_review_data', $insert_id );
                                if ($insert_id) {
                                    $result = esc_html__('Thank You for review', AIOCR );
                                }else{
                                    $result = esc_html__('Sytem Error, No Changes', AIOCR );
                                }
                            }else{
                                global $wpdb;
                                $table_name  = $wpdb->prefix."aiocr_course_review";
                                $review_details = serialize(array('review-heading' => $review_title, 'review-description' => $review_description));


                                $update_review_rating =   $wpdb->query(
                                                    $wpdb->prepare( "UPDATE $table_name SET
                                                    review_details = %s,
                                                    review_rating =%s WHERE id = %d", 
                                                    $review_details,
                                                    $aiocr_course_rating,
                                                    $DataBaseResult
                                                ) 
                                            );
                                if ($update_review_rating) {
                                    /**
                                     * 
                                     *   Update Existing review
                                     * 
                                    */
                                    do_action( 'aiocr_update_review_data');

                                    $result = esc_html__('Thank You for review', AIOCR );
                                }else{
                                    $result = esc_html__('Sytem Error, No Changes', AIOCR );
                                }
                            }
                        }else{
                            $result = esc_html__('Please give rating', AIOCR );
                        }
                    }else{
                        $result = esc_html__('No More Fun Please!!!!', AIOCR );
                    }
                }else{
                    $result = esc_html__('Review Description is empty.', AIOCR );
                }
            }else{
                $result = esc_html__('Review Heading is empty.', AIOCR );                    
            }
        }else{
            $result = esc_html__('No More Fun Please!!!!', AIOCR );                
        }
        wp_send_json_success( $result );
        wp_die(); 
    }
}
$AIOCR_ajax_data  = new AIOCR_Review_Form_Data(); ?>