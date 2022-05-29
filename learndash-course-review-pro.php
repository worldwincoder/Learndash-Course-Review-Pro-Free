<?php
/**
 * LearnDash Course Review Pro Free
 *
 * @package       LCRP
 * @author        WorldWin Coder Pvt Ltd
 * @license       gplv2-or-later
 * @version       1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:   LearnDash Course Review Pro Free
 * Plugin URI:    https://worldwincoder.com/product/learndash-course-review-pro-free/
 * Description:   LearnDash Course Review Pro (Free) Plugin has been developed exclusively for LearnDash by WorldWin Coder Pvt. Ltd. It enables you to review courses, quizzes, and lessons.
 * Version:       1.0.0
 * Author:        WorldWin Coder Pvt Ltd
 * Author URI:    https://worldwincoder.com/
 * Text Domain:   learndash-course-review-pro
 * Domain Path:   /languages/
 * License:       GPLv2 or later
 * License URI:   https://www.gnu.org/licenses/gpl-2.0.html
 *
 * You should have received a copy of the GNU General Public License
 * along with LearnDash Course Review Pro. If not, see <https://www.gnu.org/licenses/gpl-2.0.html/>.
 */

// Exit if accessed directly.

    if ( ! defined( 'ABSPATH' ) ) exit;

    global $lcrp_db_version;
    $lcrp_db_version = '1.0.0';
    define('LearnDash_Course_Review_Pro', '1.0.0' );
    define('LCRP_PLUGIN_BASENAME', plugin_basename( __DIR__ ) );
    define('LCRP_PLUGIN_BASENAME_FILE', plugin_basename( __FILE__ ) );
    define('LCRP_BASENAME', basename( __FILE__ ) );



    /**
     * Create Table on plugin activation
     *
     */
    if ( !function_exists( 'lcrp_activate_plugin') ):
 
        function lcrp_activate_plugin() {
            if (!class_exists( 'SFWD_LMS' ) ) {
                add_action('admin_notices', 'lcrp_admin_notice');
                deactivate_plugins( LCRP_PLUGIN_BASENAME_FILE );
                return;
            }else{
                global $wpdb;
                $charset_collate = $wpdb->get_charset_collate();
                $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->base_prefix}lcrp_course_review` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `author_id` int(11) NOT NULL,
                    `review_details` text NOT NULL,
                    `review_rating` int(11) NOT NULL,
                    `review_ld_item_id` varchar(255) NOT NULL,
                    `status` VARCHAR(20) NOT NULL DEFAULT 'approved',
                    `date` date NOT NULL DEFAULT current_timestamp(),
                    PRIMARY KEY (`id`)
                  )  ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
                require_once ABSPATH . 'wp-admin/includes/upgrade.php';
                dbDelta( $sql );
                $wpdb->show_errors();
            }
        }        
    endif;  
    register_activation_hook(__FILE__, 'lcrp_activate_plugin');

    /**
     * Plugin activation notice.
     *
     */
    if ( !function_exists( 'lcrp_admin_notice') ):
        function lcrp_admin_notice() {
            echo '<div class="error"><p>LearnDash Course Review Pro Free Plugin not activated. Please activate LearnDash plugin!</p></div>';
        }
    endif;

    

    /**
     * Load language files to enable plugin translation
     *
     */
    if ( !function_exists( 'lcrp_load_textdomain') ):
        function lcrp_load_textdomain() {
            load_plugin_textdomain( 'learndash-course-review-pro', false, basename( dirname( __FILE__ ) ) . '/languages' );
        }
    endif;    
    add_action( 'plugins_loaded',
        'lcrp_load_textdomain'
    );
    



    /**
     * Enqueue dashicons on front-end
     *
     */
    if ( !function_exists( 'load_dashicons_front_end') ):
        function load_dashicons_front_end() {
            wp_enqueue_style( 'dashicons' );
        }
    endif;
    add_action( 'wp_enqueue_scripts',
        'load_dashicons_front_end'
    );
    


    /**
     * 
     * Added admin menu
     *
     */
    if ( !function_exists( 'lcrp_admin_menu') ):
        function lcrp_admin_menu(){

            if (!class_exists( 'SFWD_LMS' ) ) {
                add_action('admin_notices', 'lcrp_admin_notice');
                deactivate_plugins( LCRP_PLUGIN_BASENAME_FILE );
                return;
            }else{
                require_once plugin_dir_path( __FILE__ ) . 'include/class-lcrp-admin-menu.php';
                $menu_page = new Lcrp_Admin_Menu();
            }        
        }
    endif;
    add_action('admin_menu',
        'lcrp_admin_menu'
    );


    /**
     * It ask the review from student
     * It also display all previous review and rating
     *
     */
    if ( !function_exists( 'lcrp_review_form_display') ):
        function lcrp_review_form_display(){
            require_once plugin_dir_path( __FILE__ ) . 'include/class-lcrp-get-all-review-rating.php';
            $menu_page_new = new Lcrp_Review_Form();
        }
    endif;
    add_action('learndash-course-after',
        'lcrp_review_form_display'
    );



    /**
     *
     * enqueue css file
     */
    if ( !function_exists( 'lcrp_public_style') ):
        function lcrp_public_style() {
            wp_register_style('lcrp_public_style',
                plugins_url('assets/public/css/lcrp-public.css', __FILE__),
                time(), true
            );
        }
    endif;    
    add_action( 'wp_enqueue_scripts', 
        'lcrp_public_style' 
    );  



    /**
     *
     * enqueue js file
     * localize for ajax url
     *
     */
    if ( !function_exists( 'lcrp_public_script') ):
        function lcrp_public_script() {
            wp_register_script('lcrp_public_script',
                plugins_url('assets/public/js/lcrp-public.js', __FILE__),
                array('jquery'),time(), true
            );  
            wp_localize_script( 'lcrp_public_script',
                'LCRP_Course_Review_Form_Ajax',
                array( 'ajaxurl' => admin_url( 'admin-ajax.php' ))
            );  
        }
    endif;      
    add_action( 'wp_enqueue_scripts', 
        'lcrp_public_script' 
    ); 



    /**
     * enqueue js file
     * localize for ajax url
     */
    if ( !function_exists( 'lcrp_admin_script') ):
        function lcrp_admin_script() {

            ?>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.0/css/jquery.dataTables.css">
            <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.0/js/jquery.dataTables.js"></script>

            <?php
            wp_register_style('lcrp_admin_styles', plugins_url('/assets/admin/css/lcrp-admin.css', __FILE__));
            wp_register_script('lcrp_admin_scripts', plugins_url('/assets/admin/js/lcrp-admin.js', __FILE__), array('jquery'),'1.1', true);
        }
    endif;      
    add_action( 'admin_enqueue_scripts', 
        'lcrp_admin_script' 
    );  


    /**
     *
     * Save review & rating 
     *
     */
    if ( !function_exists( 'lcrp_review_form_submitted') ):
        function lcrp_review_form_submitted(){
            require_once plugin_dir_path( __FILE__ ) . 'include/class-lcrp-form-submitted-data.php';
            $menu_page_new_test = new Lcrp_Review_Form_Data();
            $menu_page_new_test->lcrp_ajax_form_data($_POST);              
        }
    endif;      

    add_action("wp_ajax_lcrp_review_form_submitted",
        "lcrp_review_form_submitted"
    );
    add_action("wp_ajax_nopriv_lcrp_review_form_submitted",
        "lcrp_review_form_submitted"
    );



    /**
     *
     * Added Link to get pro plugin
     *
     */

    if ( !function_exists( 'lcrp_add_plugin_link') ):
        function lcrp_add_plugin_link( $plugin_actions, $plugin_file ) {
            $new_actions = array();
            if ( basename( plugin_dir_path( __FILE__ ) ) . '/learndash-course-review-pro.php' === $plugin_file ) {
                $lcrp_get_pro_link = 'https://worldwincoder.com/product/learndash-course-review-pro/';
                $new_actions['lcrp_get_pro'] = sprintf( __( '<a href="%s" target="_blank">Get Pro</a>', 'learndash-course-review-pro' ), esc_url($lcrp_get_pro_link) );
            }            
            return array_merge( $new_actions, $plugin_actions );
        }
    endif;
    add_filter( 'plugin_action_links', 'lcrp_add_plugin_link', 10, 2 );
?>