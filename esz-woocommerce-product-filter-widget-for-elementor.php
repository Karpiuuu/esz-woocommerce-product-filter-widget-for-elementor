<?php
/*
Plugin Name: WooCommerce Product Filter Widget for Elementor
Description: WooCommerce Product Filter plugin Provide you product list with many filter option such as category, tag attributes prices and much more.
Author: Eshuzu
Author URI: https://eshuzu.com/
Version: 1.0.2
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: esz-wc-product-filter-widget-elementor
*/
if(!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

final class Esz_Woocommerce_Product_Filter_Widget_For_Elementor {
    /**
     * Plugin Version
     *
     * @since 1.0.0
     *
     * @var string The plugin version.
     */
    const VERSION = '1.0.2';
    /**
     * Minimum Elementor Version
     *
     * @since 1.0.0
     *
     * @var string Minimum Elementor version required to run the plugin.
     */
    const MINIMUM_ELEMENTOR_VERSION = '2.5.11';
    /**
     * Minimum PHP Version
     *
     * @since 1.0.0
     *
     * @var string Minimum PHP version required to run the plugin.
     */
    const MINIMUM_PHP_VERSION = '5.4';
    /**
     * Instance
     *
     * @since 1.0.0
     *
     * @access private
     * @static
     *
     * The single instance of the class.
     */
    protected static $instance = null;

    public static function get_instance() {
        if(!isset(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    /**
     * Constructor
     *
     * @since 1.0.0
     *
     * @access public
     */
    protected function __construct() {
        // Check if Elementor installed and activated
        if(!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
            return;
        }
        if(!$this->is_woocommerce_activated()) {
            add_action('admin_notices', [$this, 'admin_notice_missing_woocommerce_plugin']);
            return;
        }
        // Load Text Domain
        $this->i18n();

        // Check for required PHP version
        if(version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_php_version']);
            return;
        }
        if($this->is_woocommerce_activated()) {
            require_once('widgets/product-filter.php');
            require_once('inc/controller/Eszlwcf_Product_Filter_Query_Controller.php');
            require_once('inc/controller/Eszlwcf_Custom_Function.php');
            require_once('inc/controller/Eszlwcf_Ajax_Handler.php');
            // Admin Interface
            if(is_admin()) {
                require_once('inc/admin/Eszlwcf_Admin_Interfaces.php');
            }
        }
        // Register Widget Styles
        add_action('elementor/frontend/after_enqueue_styles', [$this, 'widget_styles']);
        // Register Widget Script
        add_action('elementor/frontend/after_register_scripts', [$this, 'widget_scripts']);
        if($this->is_woocommerce_activated()) {
            // Register Widget
            add_action('elementor/widgets/widgets_registered', [$this, 'register_widgets']);
            // Register Admin Script
            add_action('admin_enqueue_scripts', [$this, 'admin_scripts']);
            $eszlwcf_product_filter_query_controller = new Eszlwcf_Product_Filter_Query_Controller();
            $eszlwcf_custom_function = new EszLwcf_Custom_Function();
        }
    }

    public function i18n() {
        load_plugin_textdomain('esz-wc-product-filter-widget-elementor', false, basename(dirname(__FILE__)) . '/languages');
    }

    /**
     * Register Widget
     *
     * Warning when the site doesn't have Elementor installed or activated.
     *
     * @since 1.0.2
     *
     * @access public
     */
    public function register_widgets() {
        \Elementor\Plugin::instance()->widgets_manager->register(new \Elementor\Eszlwcf_Product_Filter());
    }

    public function widget_styles() {
        /*Below FontAwesome Css is just enqueued for use icon without widget control*/
        wp_enqueue_style('elementor-icons-fa-regular');
        wp_enqueue_style('elementor-icons-fa-solid');
        wp_enqueue_style('slick', plugins_url('assets/library/slick.css', __FILE__), array(), self::VERSION);
        wp_enqueue_style('jquery-ui', plugins_url('assets/css/jquery-ui.css', __FILE__), array(), self::VERSION);
        wp_enqueue_style('eszlwcf-custom', plugins_url('assets/css/app.css', __FILE__), array(), self::VERSION);
    }

    public function widget_scripts() {
        wp_enqueue_script('slick', plugins_url('assets/library/slick.min.js', __FILE__), array('jquery'), self::VERSION, true);
        wp_enqueue_script('eszlwcf-custom', plugins_url('assets/js/app.js', __FILE__), array('jquery-ui-slider'), self::VERSION, true);
        wp_localize_script('eszlwcf-custom', 'EszLwcfAjaxData',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
            )
        );
    }

    public function admin_scripts() {
        wp_enqueue_script('eszlwcf-admin', plugins_url('assets/admin/js/admin.js', __FILE__), array('jquery'), self::VERSION);
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have Elementor installed or activated.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function admin_notice_missing_main_plugin() {
        if(isset($_GET['activate']))
            unset($_GET['activate']);
        $message = sprintf(
        /* 1: Plugin name 2: Elementor */
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'esz-wc-product-filter-widget-elementor'),
            '<strong>' . esc_html__('Live WooCommerce Product Filter Widget for Elementor', 'esz-wc-product-filter-widget-elementor') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'esz-wc-product-filter-widget-elementor') . '</strong>'
        );
        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have Elementor installed or activated.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function admin_notice_missing_woocommerce_plugin() {
        if(isset($_GET['activate']))
            unset($_GET['activate']);
        $message = sprintf(
        /* 1: Plugin name 2: WooCommerce */
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'esz-wc-product-filter-widget-elementor'),
            '<strong>' . esc_html__('Live WooCommerce Product Filter Widget for Elementor', 'esz-wc-product-filter-widget-elementor') . '</strong>',
            '<strong>' . esc_html__('WooCommerce', 'esz-wc-product-filter-widget-elementor') . '</strong>'
        );
        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required Elementor version.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function admin_notice_minimum_elementor_version() {
        if(isset($_GET['activate']))
            unset($_GET['activate']);
        $message = sprintf(
        /* 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'esz-wc-product-filter-widget-elementor'),
            '<strong>' . esc_html__('Live WooCommerce Product Filter Widget for Elementor', 'esz-wc-product-filter-widget-elementor') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'esz-wc-product-filter-widget-elementor') . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );
        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required PHP version.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function admin_notice_minimum_php_version() {
        if(isset($_GET['activate']))
            unset($_GET['activate']);
        $message = sprintf(
        /* 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'esz-wc-product-filter-widget-elementor'),
            '<strong>' . esc_html__('Live WooCommerce Product Filter Widget for Elementor', 'esz-wc-product-filter-widget-elementor') . '</strong>',
            '<strong>' . esc_html__('PHP', 'esz-wc-product-filter-widget-elementor') . '</strong>',
            self::MINIMUM_PHP_VERSION
        );
        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    public function is_woocommerce_activated() {
        if(class_exists('woocommerce')) {
            return true;
        } else {
            return false;
        }
    }
}

add_action('init', 'eszlwcf_elementor_init');
function eszlwcf_elementor_init() {
    Esz_Woocommerce_Product_Filter_Widget_For_Elementor::get_instance();
}