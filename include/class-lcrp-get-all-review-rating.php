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
class Lcrp_Review_Form{

    private $lcrp_table_name ='lcrp_course_review';
    
    public function __construct() {
        $this->show_all_course_reviews();        
    }
    /**
     * Display the list table page
     *
     * @return Void
     */
    public function show_all_course_reviews(){
        wp_enqueue_style('lcrp_public_style');
        wp_enqueue_script( 'jquery' ); 
        wp_enqueue_script('lcrp_public_script');
        $this->show_form_for_review();
    }
    public function show_form_for_review(){

        $logged_in_user_course_list = learndash_get_user_course_access_list(get_current_user_id());
        if(in_array(get_the_ID(), $logged_in_user_course_list)){
            require_once 'class-lcrp-form-template.php';
        }
        $this->display_all_rating();
    }
    public function display_all_rating(){

        $logged_in_user_course_list = get_current_user_id();
        $course_id = get_the_ID();

        global $wpdb;
        $table_name = $wpdb->prefix. $this->lcrp_table_name;
        $get_all_reviews = $wpdb->get_results("SELECT AVG(review_rating), id, author_id, review_details,review_rating FROM $table_name WHERE review_ld_item_id = $course_id"); 
    
        echo $lcrp_review_data = '<div class="lcrp-all-rating-top-wrapper">
            <div class="lcrp-review-rating">    
                <div class="lcrp-review-rating-top-wrapper">
                    <span class="lcrp-heading">'.esc_html__('Course Reviews & Rating', 'learndash-course-review-pro' ).'</span>
                    <p class="lcrp-rate-cont">'.$this->lcrp_count_all_course_reviews($course_id).'</p>                    
                </div>
                <div class="lcrp-ask-btn-wrapper">
                    <span class="lcrp-ask-review">'.esc_html__('Write a review', 'learndash-course-review-pro' ).'</span>
                </div>
            </div>
            <hr style="border:1px solid #f1f1f1;margin: 15px 0px;">';
        $this->lcrp_all_rating_display($course_id);
        
    }
    public function lcrp_get_single_course_avg_rating($course_id)
    {

        global $wpdb;
        $table_name = $wpdb->prefix.$this->lcrp_table_name;
        $single_avg_review_rating = $wpdb->get_var("SELECT AVG(review_rating) FROM $table_name WHERE review_ld_item_id = $course_id");
        return number_format($single_avg_review_rating, 2);
    }
    public function lcrp_count_all_course_reviews($course_id)
    {

        global $wpdb;
        $table_name = $wpdb->prefix.$this->lcrp_table_name;
        $single_course_avg_review_rating = $wpdb->get_var("SELECT COUNT(id) FROM $table_name WHERE review_ld_item_id = $course_id");

        $result_review_count = $this->lcrp_get_single_course_avg_rating($course_id);

        if ($single_course_avg_review_rating == 1) {

            $result_all_reviews = $result_review_count.esc_html__(' average based on 1 review', 'learndash-course-review-pro' );
            $result_all_reviews .= $this->get_rating_count($result_review_count);
        }elseif ($single_course_avg_review_rating > 1) {

            $result_all_reviews = $result_review_count.esc_html__(' average based on '.$single_course_avg_review_rating.' review', 'learndash-course-review-pro' );
            $result_all_reviews .= $this->get_rating_count($result_review_count);
        }else{
            $result_all_reviews = esc_html__('No review yet', 'learndash-course-review-pro' );
        }

        return $result_all_reviews;
    }
    public function lcrp_all_rating_display($course_id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix.$this->lcrp_table_name;

        $five_star_rating_count = $wpdb->get_var("SELECT COUNT(id) FROM $table_name WHERE review_ld_item_id = $course_id AND review_rating =5");
        $four_star_rating_count = $wpdb->get_var("SELECT COUNT(id) FROM $table_name WHERE review_ld_item_id = $course_id AND review_rating =4");
        $three_star_rating_count = $wpdb->get_var("SELECT COUNT(id) FROM $table_name WHERE review_ld_item_id = $course_id AND review_rating =3");
        $two_star_rating_count = $wpdb->get_var("SELECT COUNT(id) FROM $table_name WHERE review_ld_item_id = $course_id AND review_rating =2");
        $one_star_rating_count = $wpdb->get_var("SELECT COUNT(id) FROM $table_name WHERE review_ld_item_id = $course_id AND review_rating =1");
        $all_rating_display_result = '<div class="lcrp-all-rating-wrapper">';
            $all_rating_display_result .=
                '<div class="side">
                    <div>5 star</div>
                </div>
                <div class="middle">
                    <div class="bar-container">
                        <div class="bar-5" style="width:';
                            if ($five_star_rating_count) {
                                $all_rating_display_result .='100%;';
                            }else{
                                $all_rating_display_result .='0%;';
                            }
                                $all_rating_display_result .='">
                        </div>
                    </div>
                </div>
                <div class="side right">
                    <div>';
                        $all_rating_display_result .= $five_star_rating_count;
                        $all_rating_display_result .=
                    '</div>
                </div>';

                $all_rating_display_result .=
                '<div class="side">
                    <div>4 star</div>
                </div>
                <div class="middle">
                    <div class="bar-container">
                        <div class="bar-4" style="width:';
                            if ($four_star_rating_count) {
                                $all_rating_display_result .='80%;';
                            }else{
                                $all_rating_display_result .='0%;';
                            }
                                $all_rating_display_result .='">
                        </div>
                    </div>
                </div>
                <div class="side right">
                    <div>';
                        $all_rating_display_result .= $four_star_rating_count;
                        $all_rating_display_result .=
                    '</div>
                </div>';

                $all_rating_display_result .=
                '<div class="side">
                    <div>3 star</div>
                </div>
                <div class="middle">
                    <div class="bar-container">
                        <div class="bar-3" style="width:';
                            if ($three_star_rating_count) {
                                $all_rating_display_result .='80%;';
                            }else{
                                $all_rating_display_result .='0%;';
                            }
                                $all_rating_display_result .='">
                        </div>
                    </div>
                </div>
                <div class="side right">
                    <div>';
                        $all_rating_display_result .= $three_star_rating_count.'                        
                    </div>
                </div>';

                $all_rating_display_result .=
                    '<div class="side">
                        <div>2 star</div>
                    </div>
                    <div class="middle">
                        <div class="bar-container">
                            <div class="bar-2" style="width:';
                                if ($two_star_rating_count) {
                                    $all_rating_display_result .='80%;';
                                }else{
                                    $all_rating_display_result .='0%;';
                                }
                                    $all_rating_display_result .='">
                            </div>
                        </div>
                    </div>
                    <div class="side right">
                        <div>';
                            $all_rating_display_result .= $two_star_rating_count.'                        
                        </div>
                    </div>';

                $all_rating_display_result .=
                '<div class="side">
                    <div>1 star</div>
                </div>
                <div class="middle">
                    <div class="bar-container">
                        <div class="bar-1" style="width:';
                            if ($one_star_rating_count) {
                                $all_rating_display_result .='20%;';
                            }else{
                                $all_rating_display_result .='0%;';
                            }
                                $all_rating_display_result .='">
                        </div>
                    </div>
                </div>
                <div class="side right">
                    <div>';
                        $all_rating_display_result .= $one_star_rating_count.'
                    </div>
                </div>';                
            $all_rating_display_result .= '</div><hr style="border:1px solid #f1f1f1;margin: 15px 0px;"></div>';
        echo $all_rating_display_result;
        $this->lcrp_all_reviews_display($course_id);
    }
    public function lcrp_all_reviews_display($course_id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix.$this->lcrp_table_name;
        $result_array = $wpdb->get_results("SELECT id, author_id, review_details,review_rating,date FROM $table_name WHERE review_ld_item_id = $course_id and status = 'approved'");

        $all_reviews_array = apply_filters( 'lcrp_review_data_array',$result_array);

        $all_reviews_data_html = '<div class="lcrp-row-wrapper">';

        if (!empty($all_reviews_array)){

            foreach ($all_reviews_array as $key => $all_reviews_data) {

                $review_details_array = unserialize($all_reviews_data->review_details);                
                $review_heading = esc_html__(stripslashes($review_details_array['review-heading']), 'learndash-course-review-pro' );
                $review_description = esc_html__(stripslashes($review_details_array['review-description']), 'learndash-course-review-pro' ); 
                $user_obj = get_user_by('id', $all_reviews_data->author_id);
                $all_reviews_data_html .='<div class="lcrp-top-wrapper">';
                    $all_reviews_data_html .='<div class="lcrp-top-wrapper-avatar">';
                    $all_reviews_data_html .='  <img alt="" width="60px" src="'.get_avatar_url( $all_reviews_data->author_id).'" class="lcrp-avatar" loading="lazy">';
                    $all_reviews_data_html .=' <h4 class="lcrp-reviewed-student-name">'.$user_obj->data->display_name.'</h4>';
                    $all_reviews_data_html .=' </div>';
                    $all_reviews_data_html .='<div class="lcrp-top-wrapper-details">';
                    $all_reviews_data_html .='      <div class="lcrp-all-list-review-title">'.$review_heading.'</div>';
                    $all_reviews_data_html .='    <div class="lcrp-all-list-review-rating">'.$this->get_rating_count($all_reviews_data->review_rating).'</div>';
                    $all_reviews_data_html .='    <div class="lcrp-all-list-reviews">'.$review_description.'</div>';
                    $all_reviews_data_html .='</div>';
                    $all_reviews_data_html .='<div class="lcrp-review-date"><span class="lcrp-review-date"> Posted on: '.$all_reviews_data->date.'</span>';
                    $all_reviews_data_html .='<p class="lcrp-verified">Verified<span class=" lcrp-icon dashicons dashicons-yes-alt"></span></p>';
                    $all_reviews_data_html .='</div>';
                $all_reviews_data_html .='</div>';
            }
        }else{

            $all_reviews_data_html .='<span class="">'.esc_html__('No review yet', 'learndash-course-review-pro' ).'</span>';
        }
        $all_reviews_data_html .= '</div>';
        echo $all_reviews_data_html;
    }
    public function get_rating_count(int $rating_count)
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
}
?>