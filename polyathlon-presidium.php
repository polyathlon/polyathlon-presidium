<?php

/*
* Plugin Name: Polyathlon Presidium
* Plugin URI: http://base.rsu.edu.ru
* Description: WordPress multipurpose plugin to showcase polyathlon presidium!
* Author: Vladislav Antoshkin
* Author URI: http://base.rsu.edu.ru
* License: GPLv2 or later
* Version: 1.0.0
*/


//Load configs
require_once( dirname(__FILE__).'/presi-config.php');
require_once( PRESI_CLASSES_DIR_PATH.'/presi-ajax-action.php');
require_once( PRESI_CLASSES_DIR_PATH.'/PRESIHelper.php');
require_once( PRESI_CLASSES_DIR_PATH.'/PRESIDBInitializer.php');

//Register activation & deactivation hooks
register_activation_hook( __FILE__, 'presi_activation_hook');
register_uninstall_hook( __FILE__, 'presi_uninstall_hook');
register_deactivation_hook( __FILE__, 'presi_deactivation_hook');

//Register action hooks
add_action('init', 'presi_init_action');
add_action('admin_enqueue_scripts', 'presi_admin_enqueue_scripts_action' );
add_action('wp_enqueue_scripts', 'presi_wp_enqueue_scripts_action' );
add_action('admin_menu', 'presi_admin_menu_action');
add_action('admin_head', 'presi_admin_head_action');
add_action('admin_footer', 'presi_admin_footer_action');
add_action('upgrader_process_complete', 'presi_update_complete_action', 10, 2);
add_action('plugins_loaded', 'presi_plugins_loaded_action');

//Register filter hooks

//Register presi shortcode handlers
add_shortcode('presi_presidium', 'presi_shortcode_handler');
add_shortcode('presi', 'presi_shortcode_handler');

//Register Ajax actions
add_action( 'wp_ajax_presi_get_portfolio', 'wp_ajax_presi_get_portfolio');
add_action( 'wp_ajax_presi_save_portfolio', 'wp_ajax_presi_save_portfolio');
add_action( 'wp_ajax_presi_get_options', 'wp_ajax_presi_get_options');
add_action( 'wp_ajax_presi_save_options', 'wp_ajax_presi_save_options');
add_action( 'wp_ajax_presi_get_positions', 'wp_ajax_presi_get_positions');
add_action( 'wp_ajax_presi_get_positions_list', 'wp_ajax_presi_get_positions_list');
add_action( 'wp_ajax_presi_save_positions', 'wp_ajax_presi_save_positions');

//Global vars
$presi_portfolios;

function presi_update_complete_action( $upgrader_object, $options ) {
    $our_plugin = plugin_basename( __FILE__ );
    if( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {
        foreach( $options['plugins'] as $plugin ) {
            if( $plugin == $our_plugin ) {
                set_transient( 'presidium_updated', 1 );
            }
        }
    }
}

function presi_plugins_loaded_action()
{
    if (get_transient('presidium_updated')) {
        $dbInitializer = new PRESIDBInitializer();
        $dbInitializer->checkForChanges();

        delete_transient('presidium_updated');
    }
}

//Registered activation hook
function presi_activation_hook(){
    $dbInitializer = new PRESIDBInitializer();
    if($dbInitializer->needsConfiguration()){
        $dbInitializer->configure();
    }
    $dbInitializer->checkForChanges();
}

function presi_uninstall_hook(){
    delete_option(PRESI_BANNERS_CONTENT);
    delete_option(PRESI_BANNERS_LAST_LOADED_AT);
}

function presi_deactivation_hook(){
}

//Registered hook actions
function presi_init_action() {
    global $wp_version;
    if ( version_compare( $wp_version, '5.0.0', '>=' ) ) {
        wp_register_script(
            'presi-shortcode-block-script',
            PRESI_JS_URL . '/presi-shortcode-block.js',
            array('wp-blocks', 'wp-element')
        );

        wp_register_style(
            'presi-shortcode-block-style',
            PRESI_CSS_URL . '/presi-admin-editor-block.css',
            array('wp-edit-blocks'),
            filemtime(plugin_dir_path(__FILE__) . 'css/presi-admin-editor-block.css')
        );

        register_block_type('polyathlon-presidium/presi-shortcode-block', array(
            'editor_script' => 'presi-shortcode-block-script',
            'editor_style' => 'presi-shortcode-block-style',
        ));
    }
    ob_start();
}

function presi_admin_enqueue_scripts_action($hook) {
    if (stripos($hook, PRESI_PLUGIN_SLAG) !== false) {
        presi_enqueue_admin_scripts();
        presi_enqueue_admin_csss();
    }
}

function presi_wp_enqueue_scripts_action(){
    presi_enqueue_front_scripts();
    presi_enqueue_front_csss();
}

function presi_admin_menu_action() {
    presi_setup_admin_menu_buttons();
}

function presi_admin_head_action(){
    presi_include_inline_scripts();
    presi_setup_media_buttons();
}

function presi_admin_footer_action() {
    presi_include_inline_htmls();
}

//Registered hook filters
function presi_mce_external_plugins_filter($pluginsArray){
    return presi_register_tinymce_plugin($pluginsArray);
}

function presi_mce_buttons_filter($buttons){
    return presi_register_tc_buttons($buttons);
}

//Shortcode Hanlders
function presi_shortcode_handler($attributes){
	ob_start();

    //Prepare render data
    global $presi_portfolios;
    $presi_portfolios = PRESIHelper::getPortfolios($attributes['id']);
    require_once(PRESI_FRONT_VIEWS_DIR_PATH."/presi-front.php");

    $result = ob_get_clean();
    return $result;
}

//Internal functionality
function presi_setup_admin_menu_buttons(){
    add_menu_page(PRESI_PLUGIN_NAME, PRESI_PLUGIN_NAME, 'edit_posts', PRESI_PLUGIN_SLAG, "presi_admin_portfolio_page", 'dashicons-portfolio', 76);
    add_submenu_page(PRESI_PLUGIN_SLAG, PRESI_SUBMENU_PORTFOLIOS_TITLE, PRESI_SUBMENU_PORTFOLIOS_TITLE, 'edit_posts', PRESI_PLUGIN_SLAG, 'presi_admin_portfolio_page');
    add_submenu_page(PRESI_PLUGIN_SLAG, PRESI_SUBMENU_POSITIONS_TITLE, PRESI_SUBMENU_POSITIONS_TITLE, 'edit_posts', PRESI_SUBMENU_POSITIONS_SLUG, 'presi_admin_position_page');
}

function presi_admin_page() {
  require_once(PRESI_ADMIN_VIEWS_DIR_PATH.'/presi-admin.php');
}

function presi_admin_portfolio_page(){
    global $presi_adminPageType;
    $presi_adminPageType = PRESITableType::PORTFOLIO;
    require_once(PRESI_ADMIN_VIEWS_DIR_PATH.'/presi-admin.php');
}

function presi_admin_position_page(){
    global $presi_adminPageType;
    $presi_adminPageType = PRESITableType::POSITION;
    require_once(PRESI_ADMIN_VIEWS_DIR_PATH.'/presi-admin.php');
}

function presi_setup_media_buttons(){
    global $typenow;
    // check user permissions
    if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
        return;
    }

    // verify the post type
    if( ! in_array( $typenow, array( 'post', 'page' ) ) )
        return;

    // check if WYSIWYG is enabled
    if ( get_user_option('rich_editing') == 'true') {
        add_filter("mce_external_plugins", "presi_mce_external_plugins_filter");
        add_filter('mce_buttons', 'presi_mce_buttons_filter');
    }
}

function presi_register_tinymce_plugin($pluginsArray) {
    $pluginsArray['presi_tc_buttons'] = PRESI_JS_URL."/presi-tc-buttons.js";
    return $pluginsArray;
}

function presi_register_tc_buttons($buttons) {
    array_push($buttons, "presi_insert_tc_button");
    return $buttons;
}

function presi_include_inline_scripts(){
?>
    <script type="text/javascript">

        jQuery(document).ready(function() {
        });
    </script>
<?php
}

function presi_include_inline_htmls(){
?>

<?php
}

function presi_enqueue_admin_scripts(){
    wp_enqueue_script("jquery");
    wp_enqueue_script("jquery-ui-core");
    wp_enqueue_script("jquery-ui-sortable");
    wp_enqueue_script("jquery-ui-autocomplete");

    //Enqueue JS files
    wp_enqueue_script( 'presi-helper-js', PRESI_JS_URL.'/presi-helper.js', array('jquery'), "", false );
    wp_enqueue_script( 'presi-main-admin-js', PRESI_JS_URL.'/presi-main-admin.js', array('jquery'), "", true );
    wp_enqueue_script( 'presi-ajax-admin-js', PRESI_JS_URL.'/presi-ajax-admin.js', array('jquery'), "", true );

    wp_register_script('presi-tooltipster', PRESI_JS_URL."/jquery/jquery.tooltipster.js", array('jquery'), "", true );
    wp_enqueue_script('presi-tooltipster');

    wp_register_script('presi-caret', PRESI_JS_URL."/jquery/jquery.caret.js", array('jquery'), "", true );
    wp_enqueue_script('presi-caret');

    wp_register_script('presi-tageditor', PRESI_JS_URL."/jquery/jquery.tageditor.js", array('jquery'), "", true );
    wp_enqueue_script('presi-tageditor');

    wp_enqueue_media();
    wp_enqueue_script('wp-color-picker');
}

function presi_enqueue_admin_csss(){
    //Enqueue CSS files

    wp_register_style('presi-main-admin-style', PRESI_CSS_URL.'/presi-main-admin.css');
    wp_enqueue_style('presi-main-admin-style');

    wp_register_style('presi-tc-buttons', PRESI_CSS_URL.'/presi-tc-buttons.css');
    wp_enqueue_style('presi-tc-buttons');

    wp_register_style('presi-tooltipster', PRESI_CSS_URL.'/tooltipster/tooltipster.css');
    wp_enqueue_style('presi-tooltipster');
    wp_register_style('presi-tooltipster-theme', PRESI_CSS_URL.'/tooltipster/themes/tooltipster-shadow.css');
    wp_enqueue_style('presi-tooltipster-theme');

    wp_register_style('presi-accordion', PRESI_CSS_URL.'/accordion/accordion.css');
    wp_enqueue_style('presi-accordion');

    wp_register_style('presi-tageditor', PRESI_CSS_URL.'/tageditor/tageditor.css');
    wp_enqueue_style('presi-tageditor');

    wp_enqueue_style( 'wp-color-picker' );

    wp_register_style('presi-font-awesome', PRESI_CSS_URL.'/fontawesome/font-awesome.css');
    wp_enqueue_style('presi-font-awesome');
}

function presi_enqueue_front_scripts(){
    //Enqueue JS files
    wp_enqueue_script( 'presi-main-front-js', PRESI_JS_URL.'/presi-main-front.js', array('jquery') );
    wp_enqueue_script( 'presi-helper-js', PRESI_JS_URL.'/presi-helper.js', array('jquery') );

    wp_enqueue_script( 'presi-modernizr', PRESI_JS_URL."/jquery/jquery.modernizr.js", array('jquery') );
    wp_enqueue_script( 'presi-tiled-layer', PRESI_JS_URL."/presi-tiled-layer.js", array('jquery') );
    wp_enqueue_script( 'presi-fs-viewer', PRESI_JS_URL.'/presi-fs-viewer.js', array('jquery') );
    wp_enqueue_script( 'presi-lg-viewer', PRESI_JS_URL.'/jquery/jquery.lightgallery.js', array('jquery') );
    wp_enqueue_script( 'presi-owl', PRESI_JS_URL.'/owl-carousel/owl.carousel.js', array('jquery') );
}

function presi_enqueue_front_csss(){
    //Enqueue CSS files
    wp_register_style('presi-main-front-style', PRESI_CSS_URL.'/presi-main-front.css');
    wp_enqueue_style('presi-main-front-style');

    wp_register_style('presi-tc-buttons', PRESI_CSS_URL.'/presi-tc-buttons.css');
    wp_enqueue_style('presi-tc-buttons');

    wp_register_style('presi-tiled-layer', PRESI_CSS_URL.'/presi-tiled-layer.css');
    wp_enqueue_style('presi-tiled-layer');

    wp_register_style('presi-fs-viewer', PRESI_CSS_URL.'/fsviewer/presi-fs-viewer.css');
    wp_enqueue_style('presi-fs-viewer');

    wp_register_style('presi-font-awesome', PRESI_CSS_URL.'/fontawesome/font-awesome.css');
    wp_enqueue_style('presi-font-awesome');

    wp_register_style('presi-lg-viewer', PRESI_CSS_URL.'/lightgallery/lightgallery.css');
    wp_enqueue_style('presi-lg-viewer');

    wp_register_style('presi-captions', PRESI_CSS_URL.'/presi-captions.css');
    wp_enqueue_style('presi-captions');

    wp_register_style('presi-captions', PRESI_CSS_URL.'/presi-captions.css');
    wp_enqueue_style('presi-captions');

    wp_register_style('presi-owl', PRESI_CSS_URL.'/owl-carousel/assets/owl.carousel.css');
    wp_enqueue_style('presi-owl');

    wp_register_style('presi-layout', PRESI_CSS_URL.'/owl-carousel/layout.css');
    wp_enqueue_style('presi-layout');
}
