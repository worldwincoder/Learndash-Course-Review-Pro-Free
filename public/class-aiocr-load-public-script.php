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


class AIOCR_Load_Script{

    public function __construct() {

    	add_action( 'wp_enqueue_scripts',
            array( $this,'aiocr_public_script') 
        );
        add_action( 'wp_enqueue_scripts',
            array( $this,'aiocr_public_script') 
        );
    }

	public function aiocr_public_script() {

        wp_enqueue_style( 'dashicons' );

        $aiocr_plugin_path = trailingslashit( WP_PLUGIN_URL . '/' . basename( dirname( __DIR__ ) ) );

        wp_register_script('aiocr_public_script',
            $aiocr_plugin_path.'assets/public/js/aiocr-public.js',
            array('jquery'),time(), true
        );  
        wp_localize_script( 'aiocr_public_script',
            'AIOCR_Course_Review_Form_Ajax',
            array( 'ajaxurl' => admin_url( 'admin-ajax.php' ))
        );

        wp_register_style('aiocr_public_style',
            $aiocr_plugin_path.'assets/public/css/aiocr-public.css',
            time(), true
        );
	}
}
$aiocrLoadScript  = new AIOCR_Load_Script(); ?>