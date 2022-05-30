<?php 

//Exit if accessed directly
if( !defined( 'ABSPATH' ) ){
    return;
}
$logged_in_user_course_list = learndash_get_user_course_access_list(get_current_user_id());

if(in_array(get_the_ID(), $logged_in_user_course_list)){

    global $wpdb;
    $logged_in_user_id = get_current_user_id();
    $course_id = get_the_ID();

    $table_name = $wpdb->prefix.'lcrp_course_review'; 

    $current_user_review_array = $wpdb->get_results("SELECT id, review_details,review_rating FROM $table_name WHERE review_ld_item_id = $course_id AND author_id =$logged_in_user_id");
    
    if (empty($current_user_review_array)) {
    }else{
        $current_user_review_data = $current_user_review_array['0'];
        $current_user_review = unserialize($current_user_review_data->review_details);
        $review_heading = esc_html__(stripslashes($current_user_review['review-heading']), 'learndash-course-review-pro-free' ); 
        $review_description = esc_html__(stripslashes($current_user_review['review-description']), 'learndash-course-review-pro-free' ); 
        $current_user_rating = $current_user_review_data->review_rating;
    }
}
/**
 * 
 * 
 *   Before Review Form
 * 
*/
do_action( 'lcrp_before_review_form');
$form_field_array ='
    <div class="lcrp-course-review-form-wrapper">
        <form action="#" id="lcrp-course-review-form">
            <div class="form-div-cointainer">
                '.wp_nonce_field( 'lcrp_review_form_display', 'lcrp_generate_nonce' ).'
                <input type ="hidden" id="lcrp-current-course-id" name="lcrp-current-course-id" value="'.get_the_ID().'">
                <h1 class="lcrp-feedback-form-title">Write a Review</h1>
                <hr>    
                <label for="lcrp-lcrp-review-title">Review Heading*</label>
                <input type="text" placeholder="Enter Review Heading*" value="'.$review_heading.'" name="lcrp-review-heading" id="lcrp-review-heading" minlength="5" maxlength="25" required>

                <label for="lcrp-review-description">Review Description</label>
                <textarea placeholder="Enter Review Description" maxlength="120" minlength="15" name="lcrp-review-description" id="lcrp-review-description" required rows="4" cols="50">'.$review_description.'</textarea>
                <input type="hidden" id="user_previous_rating" name="user_previous_rating" value="'.$current_user_rating.'" >
                <div class="lcrp-rating">
                    <input type="radio" id="star-star5" name="lcrp-rating" value="5" required /><label for="star-star5" title="Excellent">5</label>
                    <input type="radio" id="star-star4" name="lcrp-rating" value="4" required /><label for="star-star4" title="Very Good">4</label>
                    <input type="radio" id="star-star3" name="lcrp-rating" value="3" required /><label for="star-star3" title="Good">3</label>
                    <input type="radio" id="star-star2" name="lcrp-rating" value="2" required /><label for="star-star2" title="Bad">2</label>
                    <input type="radio" id="star-star1" name="lcrp-rating" value="1" required /><label for="star-star1" title="Very Bad">1</label>
                </div>
                <div class="clearfix"></div>
                <div class="lcrp-btn-wrapper">
                    <button type="submit" class="lcrp-btn-submit-feedback clearfix">Submit Review</button>
                    <button type="button" class="lcrp-btn-cancel clearfix">Close</button>
                </div>

                
            </div>
            <div class="lcrp-form-error-response-result">
                <span></span>
            </div>
        </form>
    </div>';
echo $form_field_array; ?>