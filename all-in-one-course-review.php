<?php
/**
 * All In One Course Review
 *
 * @package       AIOCR
 * @author        WorldWin Coder Pvt Ltd
 * @license       gplv2-or-later
 * @version       1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:   All In One Course Review
 * Plugin URI:    https://worldwincoder.com/product/all-in-one-course-review/
 * Description:   All In One Course Review Plugin has been developed especially for WordPress Learning Management System. It enables you to review courses, quizzes, and lessons.
 * Version:       1.0.0
 * Author:        WorldWin Coder Pvt Ltd
 * Author URI:    https://worldwincoder.com/
 * Text Domain:   all-in-one-course-review
 * Domain Path:   /languages/
 * License:       GPLv2 or later
 * License URI:   https://www.gnu.org/licenses/gpl-2.0.html
 *
 * You should have received a copy of the GNU General Public License
 * along with All In One Course Review. If not, see <https://www.gnu.org/licenses/gpl-2.0.html/>.
 */

// Exit if accessed directly.

if ( ! defined( 'ABSPATH' ) ) exit;

global $aiocr_db_version;
$aiocr_db_version = '1.0.0';
define('All_In_One_Course_Review', '1.0.0' );
define('AIOCR', 'all-in-one-course-review' );
define('AIOCR_PLUGIN_BASENAME', plugin_basename( __DIR__ ) );
define('AIOCR_PLUGIN_BASENAME_FILE', plugin_basename( __FILE__ ) );
define('AIOCR_BASENAME', basename( __FILE__ ) );
define('AIOCR_PLUGIN', __FILE__ );
define('AIOCR_PLUGIN_DIR', untrailingslashit( dirname( AIOCR_PLUGIN ) ) );


/**
 * Create Table on plugin activation
 *
 */

if (!function_exists('aiocr_activate_plugin')) :
    function aiocr_activate_plugin(){
        require_once AIOCR_PLUGIN_DIR. '/include/class-aiocr-plugin-activate.php';
    }
endif;
register_activation_hook(__FILE__,'aiocr_activate_plugin');

require_once AIOCR_PLUGIN_DIR.'/include/class-aiocr-admin-menu.php';
require_once AIOCR_PLUGIN_DIR.'/include/class-aiocr-get-all-review-rating.php';
require_once AIOCR_PLUGIN_DIR.'/public/class-aiocr-form-ajax-data.php';
require_once AIOCR_PLUGIN_DIR.'/public/class-aiocr-load-public-script.php';


/**
 * Load language files to enable plugin translation
 *
 */
if ( !function_exists( 'aiocr_load_textdomain') ):
    function aiocr_load_textdomain() {
        load_plugin_textdomain( 'all-in-one-course-review', false, basename( dirname( __FILE__ ) ) . '/languages' );
    }
endif;    
add_action( 'plugins_loaded', 'aiocr_load_textdomain');

/**
 *
 * Added Link to get pro plugin
 *
 */

if ( !function_exists( 'aiocr_add_plugin_link') ):
    function aiocr_add_plugin_link( $plugin_actions, $plugin_file ) {
        $new_actions = array();
        if ( basename( plugin_dir_path( __FILE__ ) ) . '/all-in-one-course-review.php' === $plugin_file ) {
            $aiocr_get_pro_link = 'https://worldwincoder.com/product/all-in-one-course-review/';
            $new_actions['aiocr_get_pro'] = sprintf( __( '<a href="%s" target="_blank">Get Pro</a>', AIOCR ), esc_url($aiocr_get_pro_link) );
        }            
        return array_merge($plugin_actions  ,$new_actions,);
    }
endif;
add_filter( 'plugin_action_links', 'aiocr_add_plugin_link', 10, 2 );?>