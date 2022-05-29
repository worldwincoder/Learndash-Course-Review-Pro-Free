(function( $ ) {
    'use strict';
    $(function() {
        jQuery("#star-star1").attr('checked', true);
        $(document).on('click', '.lcrp-btn-submit-feedback', function(e){
            var review_title    	= $('#lcrp-review-heading').val();
            var review_description  = $('#lcrp-review-description').val();
            if (review_title !="" && review_description !="") {
                e.preventDefault();
                $(this).hide('fast');
                $('.lcrp-form-error-response-result span').html('');
                $('.lcrp-form-error-response-result span').removeClass('show');
                var form_data = $('#lcrp-course-review-form').serialize() + '&action=lcrp_review_form_submitted';
                jQuery.ajax({
                    type     : "post",
                    dataType : "json",
                    url      : LCRP_Course_Review_Form_Ajax.ajaxurl,
                    data     :form_data,
                    success: function(data){
                        $('.lcrp-form-error-response-result span').addClass('show');
                        $('.lcrp-form-error-response-result span').html(data.data);
                        $('.lcrp-btn-submit-feedback').show('fast');
                    }
                });
            }else{
                $('.lcrp-form-error-response-result span').addClass('show');
                $('.lcrp-form-error-response-result span').html('Error!! All Fields are required.');
            }
            setTimeout(function() {
                $('.lcrp-form-error-response-result span').removeClass('show');
                $('.lcrp-form-error-response-result span').html('');
            }, 9999);
        });
        
        $(document).on('click', '.lcrp-ask-review', function(e){
            jQuery('.lcrp-course-review-form-wrapper').show();
            var prev_rating = jQuery('#user_previous_rating').val();
            jQuery("#star-star"+prev_rating).attr('checked', true);

        });
        $(document).on('click', '.lcrp-btn-cancel', function(e){
            jQuery('.lcrp-course-review-form-wrapper').hide();
        });
        

    });
})( jQuery );