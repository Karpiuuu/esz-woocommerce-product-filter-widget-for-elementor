<?php
namespace Elementor;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

class Eszlwcf_Product_Filter extends Widget_Base {
    protected $eszlwcf;
    protected $eszwcq;
    protected $tab_state;

    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);
        $this->eszlwcf = new \Eszlwcf_Custom_Function();
        $this->eszwcq = new \Eszlwcf_Product_Filter_Query_Controller();
        // Array String will be translated while uses
        $this->tab_state = array('normal' => 'Normal', 'hover' => "Hover");
    }

    /**
     * Get widget name.
     * @return string Widget name.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_name() {
        return 'eszlwcf-product-filter';
    }

    /**
     * Get widget title.
     * @return string Widget title.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_title() {
        return esc_html__('Product Filter', 'esz-wc-product-filter-widget-elementor');
    }

    /**
     * Get widget icon.
     * @return string Widget icon.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_icon() {
        return 'eicon-products-archive';
    }

    /**
     * Get widget categories.
     * @return array Widget categories.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_categories() {
        return ['basic'];
    }

    /**
     * Register widget controls.
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {
        $this->register_controls_content();
        $this->register_controls_style();
    }

    protected function register_controls_content() {
        /**
         *  This is Layout Section of Content
         *
         **/
        $this->register_controls_layout_section_content();
        /**
         *  This is Product Section of Content
         *
         **/
        $this->register_controls_product_section_content();
        /**
         *  This is Query Section of Content
         *
         **/
        $this->register_controls_query_section_content();
        /**
         *  This is Filter General Section of Content
         *
         **/
        $this->register_controls_filter_general_content();
        /**
         *  This is Filter Section of Content
         *
         **/
        $this->register_controls_filter_content();
        /**
         *  This is Quick View Modal Section of Content
         *
         **/
        $this->register_controls_quick_view_modal_content();
        /**
         *  This is Not Found Section of Content
         *
         **/
        $this->register_controls_not_found_content();
    }

    protected function register_controls_layout_section_content() {
        $this->start_controls_section(
            'layout_configuration',
            [
                'label' => esc_html__('layout', 'esz-wc-product-filter-widget-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'products_layout_skin',
            [
                'label' => esc_html__('Product Skin', 'esz-wc-product-filter-widget-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'classic',
                'options' => [
                    'classic' => esc_html__('classic', 'esz-wc-product-filter-widget-elementor'),
                    'default' => esc_html__('Default', 'esz-wc-product-filter-widget-elementor'),
                ],
            ]
        );
        $this->add_responsive_control(
            'products_column',
            [
                'label' => esc_html__('Product Column', 'esz-wc-product-filter-widget-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'devices' => ['desktop', 'tablet', 'mobile'],
                'desktop_default' => 3,
                'tablet_default' => 2,
                'mobile_default' => 1,
                'options' => [
                    '1' => esc_html__('1', 'esz-wc-product-filter-widget-elementor'),
                    '2' => esc_html__('2', 'esz-wc-product-filter-widget-elementor'),
                    '3' => esc_html__('3', 'esz-wc-product-filter-widget-elementor'),
                    '4' => esc_html__('4', 'esz-wc-product-filter-widget-elementor'),
                    '5' => esc_html__('5', 'esz-wc-product-filter-widget-elementor'),
                    '6' => esc_html__('6', 'esz-wc-product-filter-widget-elementor'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-products-block' => 'grid-template-columns:repeat({{VALUE}},1fr);',
                ],
            ]
        );
        $this->end_controls_section();
    }

    protected function register_controls_product_section_content() {
        $this->start_controls_section(
            'product_configuration',
            [
                'label' => esc_html__('Product', 'esz-wc-product-filter-widget-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'products_layout_skin' => 'classic'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Image_Size::get_type(),
            [
                'name' => 'product_thumbnail_size',
                'default' => 'medium_large',
            ]
        );
        $this->add_responsive_control(
            'product_thumbnail_ratio',
            [
                'label' => esc_html__('Thumbnail Ratio', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%', 'px'],
                'range' => [
                    '%' => [
                        'min' => 2,
                        'max' => 300,
                    ],
                    'px' => [
                        'min' => 10,
                        'max' => 500,
                    ],
                ],
                'default' => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-product-thumb' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'product_title_tag',
            [
                'label' => esc_html__('Title Tag', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'default' => 'h5',
            ]
        );
        $this->add_control(
            'display_product_button',
            [
                'label' => esc_html__('Display Button', 'esz-wc-product-filter-widget-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'esz-wc-product-filter-widget-elementor'),
                'label_off' => esc_html__('No', 'esz-wc-product-filter-widget-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'link_attach_with_title',
            [
                'label' => esc_html__('Post Link to Title', 'esz-wc-product-filter-widget-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'esz-wc-product-filter-widget-elementor'),
                'label_off' => esc_html__('No', 'esz-wc-product-filter-widget-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'open_in_new_window',
            [
                'label' => esc_html__('Open in new window', 'esz-wc-product-filter-widget-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'esz-wc-product-filter-widget-elementor'),
                'label_off' => esc_html__('No', 'esz-wc-product-filter-widget-elementor'),
                'return_value' => 'yes',
                'default' => 'label_off',
            ]
        );
        $this->end_controls_section();
    }

    protected function register_controls_query_section_content() {
        $this->start_controls_section(
            'query_configuration',
            [
                'label' => esc_html__('Query', 'esz-wc-product-filter-widget-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'posts_per_page',
            [
                'label' => esc_html__('Products Per Page', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::NUMBER,
                /** This filter is documented in wp-includes/formatting.php */
                'min' => 0,
                'step' => 1,
                'default' => 9,
                'description' => esc_html__('For all Post enter 0', 'esz-wc-product-filter-widget-elementor'),
            ]
        );
        $this->start_controls_tabs('posts_query_tabs');
        $this->start_controls_tab('products_query_include_by_tab',
            [
                'label' => esc_html__('Include By', 'esz-wc-product-filter-widget-elementor'),
            ]
        );
        $this->add_control(
            'include_by',
            [
                'label' => esc_html__('Include By', 'esz-wc-product-filter-widget-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => [
                    'term' => esc_html__('Term', 'esz-wc-product-filter-widget-elementor'),
                    'attribute' => esc_html__('Attributes', 'esz-wc-product-filter-widget-elementor'),
                ],
                'default' => [''],
            ]
        );
        if (!empty($this->eszlwcf->eszlwcf_filters_custom_data()['eszlwcf_registered_taxonomies'])):
            foreach ($this->eszlwcf->eszlwcf_filters_custom_data()['eszlwcf_registered_taxonomies'] as $key => $taxonomy):
                $this->add_control(
                    'include_select_' . $key,
                    [
                        'label' => sprintf(esc_html__('Select %s', 'esz-wc-product-filter-widget-elementor'), $taxonomy),
                        'type' => \Elementor\Controls_Manager::SELECT2,
                        'multiple' => true,
                        'options' => $this->eszlwcf->eszlwcf_filters_custom_data()['eszlwcf_registered_taxonomies_terms_names'][$key],
                        'default' => [],
                        'conditions' => [
                            'relation' => 'and',
                            'terms' => [
                                ['name' => 'include_by', 'operator' => 'contains', 'value' => 'term'],
                            ],
                        ],
                    ]
                );
            endforeach;
        endif;
        if (!empty($this->eszlwcf->eszlwcf_filters_custom_data()['eszlwcf_attributes_taxonomies_names'])):
            foreach ($this->eszlwcf->eszlwcf_filters_custom_data()['eszlwcf_attributes_taxonomies_names'] as $key => $taxonomy):
                $this->add_control(
                    'include_select_' . $key,
                    [
                        'label' => sprintf(esc_html__('Select %s', 'esz-wc-product-filter-widget-elementor'), $taxonomy),
                        'type' => \Elementor\Controls_Manager::SELECT2,
                        'multiple' => true,
                        'options' => $this->eszlwcf->eszlwcf_filters_custom_data()['eszlwcf_attributes_taxonomies_terms_names'][$key],
                        'default' => [],
                        'conditions' => [
                            'relation' => 'and',
                            'terms' => [
                                ['name' => 'include_by', 'operator' => 'contains', 'value' => 'attribute'],
                            ],
                        ],
                    ]
                );
            endforeach;
        endif;
        $this->end_controls_tab();;
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function register_controls_filter_general_content() {
        $this->start_controls_section(
            'filter_general_configuration',
            [
                'label' => esc_html__('Filter General', 'esz-wc-product-filter-widget-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'display_products_sorting',
            [
                'label' => esc_html__('Display Sorting', 'esz-wc-product-filter-widget-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'esz-wc-product-filter-widget-elementor'),
                'label_off' => esc_html__('No', 'esz-wc-product-filter-widget-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'filter_list_heading_tag',
            [
                'label' => esc_html__('Filter Heading Tag', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'h5',
                'options' => [
                    'h1' => esc_html__("H1", ''),
                    'h2' => esc_html__("H2", ''),
                    'h3' => esc_html__("H3", ''),
                    'h4' => esc_html__("H4", ''),
                    'h5' => esc_html__("H5", ''),
                    'h6' => esc_html__("H6", ''),
                    'p' => esc_html__("P", ''),
                    'span' => esc_html__("span", ''),
                ],
            ]
        );
        $this->add_control(
            'filter_list_position',
            [
                'label' => esc_html__('List Position', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'esz-wc-product-filter-widget-elementor'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'esz-wc-product-filter-widget-elementor'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'left',
                'prefix_class' => 'eszlwcf-product-filter-list-',
                'toggle' => false,
            ]
        );
        $this->add_responsive_control(
            'filter_list_width',
            [
                'label' => esc_html__('Filter List Width', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'devices' => ['desktop', 'tablet'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 250,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 90,
                    ],
                ],
                'default' => [
                    'size' => 28,
                    'unit' => '%',
                ],
                'selectors' => [
                    '[data-elementor-device-mode="desktop"] {{WRAPPER}} .eszlwcf-filter-frame, 
                    [data-elementor-device-mode="tablet"] {{WRAPPER}} .eszlwcf-filter-frame' => 'width: {{SIZE}}{{UNIT}};',
                    '[data-elementor-device-mode="desktop"] {{WRAPPER}} .eszlwcf-products-frame, 
                    [data-elementor-device-mode="tablet"] {{WRAPPER}} .eszlwcf-products-frame' => 'width: calc(100% - {{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->add_responsive_control(
            'filter_list_spacing',
            [
                'label' => esc_html__('Filter List Spacing', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '[data-elementor-device-mode="desktop"] {{WRAPPER}}.eszlwcf-product-filter-list-right .eszlwcf-filter-frame,
                    [data-elementor-device-mode="tablet"] {{WRAPPER}}.eszlwcf-product-filter-list-right .eszlwcf-filter-frame' => 'padding-left:  {{SIZE}}{{UNIT}};',
                    '[data-elementor-device-mode="desktop"] {{WRAPPER}}.eszlwcf-product-filter-list-left .eszlwcf-filter-frame,
                    [data-elementor-device-mode="tablet"] {{WRAPPER}}.eszlwcf-product-filter-list-left .eszlwcf-filter-frame' => 'padding-right:  {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $icons = array('loader' => 'Loader', 'cart' => 'Cart', 'option' => 'Option');
        $this->start_controls_tabs('filter_images_icons');
        foreach ($icons as $key => $value) {
            $default = array(
                'cart' => ['value' => 'fas fa-store', 'library' => 'fa-solid',],
                'option' => ['value' => 'fas fa-ellipsis-h', 'library' => 'fa-solid',],
            );
            $this->start_controls_tab('filter_icon_tab' . $key,
                [
                    'label' => sprintf(esc_html__('%s', 'esz-wc-product-filter-widget-elementor'), $value),
                ]
            );
            if ($key === 'loader'):
                $this->add_control(
                    'loader_image',
                    [
                        'label' => esc_html__('Choose Loader Image', 'esz-wc-product-filter-widget-elementor'),
                        'type' => Controls_Manager::MEDIA,
                        'description' => esc_html__('If there is no Image selected Default will display', 'esz-wc-product-filter-widget-elementor'),
                        'dynamic' => [
                            'active' => true,
                        ],
                        'default' => [
                            'url' => plugin_dir_url(__DIR__) . 'assets/img/loader.svg',
                        ],
                    ]
                );
            else:
                $this->add_control(
                    'filter_bar_icon_' . $key,
                    [
                        'label' => esc_html__('Icon', 'esz-wc-product-filter-widget-elementor'),
                        'type' => Controls_Manager::ICONS,
                        'fa4compatibility' => 'icon',
                        'default' => $default[$key],
                    ]
                );
            endif;
            $this->end_controls_tab();
        }
        $this->end_controls_tabs();
        $this->add_control(
            'load_more_button_text',
            [
                'label' => esc_html__('Load More Text', 'esz-wc-product-filter-widget-elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Load More', 'esz-wc-product-filter-widget-elementor'),
                'placeholder' => esc_html__('Type your title here', 'esz-wc-product-filter-widget-elementor'),
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'mobile_filter_button_text',
            [
                'label' => esc_html__('Mobile Filter Text', 'esz-wc-product-filter-widget-elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Filter', 'esz-wc-product-filter-widget-elementor'),
                'placeholder' => esc_html__('Type your title here', 'esz-wc-product-filter-widget-elementor'),
                'description' => esc_html__('This Text will display on mobile filter button and heading of of filter panel', 'esz-wc-product-filter-widget-elementor'),
            ]
        );
        $this->end_controls_section();
    }

    protected function register_controls_filter_content() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Filter', 'esz-wc-product-filter-widget-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'wc_filter_select_options',
            [
                'label' => esc_html__('Select Filter Attributes', 'esz-wc-product-filter-widget-elementor'),
                'label_block' => true,
                'type' => Controls_Manager::SELECT2,
                'default' => $this->eszlwcf->eszlwcf_get_filter_select_options(true),
                'multiple' => true,
                'options' => $this->eszlwcf->eszlwcf_get_filter_select_options(),
                'separator' => 'before',
            ]
        );
        $this->eszlwcf->eszlwcf_get_controls_key();
        if (!empty($this->eszlwcf->eszlwcf_get_controls_key())):
            $filter_pos = 0;
            foreach ($this->eszlwcf->eszlwcf_get_controls_key() as $key => $name):
                $options = $this->eszlwcf->eszlwcf_get_controls_default_filter_type($key, $name);
                $this->add_control(
                    'heading_' . $key . '_filter',
                    [
                        'label' => sprintf(esc_html__('%s', 'esz-wc-product-filter-widget-elementor'), $name),
                        'type' => Controls_Manager::HEADING,
                        'separator' => 'before',
                        'condition' => [
                            'wc_filter_select_options' => $key
                        ],
                    ]
                );

                $exclude_metas = $this->eszlwcf->eszlwcf_filters_custom_data()['eszlwcf_filter_product_metas'];
                if (!array_key_exists($key, $exclude_metas)):
                    $this->add_control(
                        $key . '_view',
                        [
                            'label' => esc_html__('Layout', 'esz-wc-product-filter-widget-elementor'),
                            'type' => Controls_Manager::CHOOSE,
                            'default' => ($options['default'] === 'image' || $options['default'] === 'color') ? 'flex' : 'block',
                            'options' => [
                                'block' => [
                                    'title' => esc_html__('Default', 'esz-wc-product-filter-widget-elementor'),
                                    'icon' => 'eicon-editor-list-ul',
                                ],
                                'flex' => [
                                    'title' => esc_html__('Inline', 'esz-wc-product-filter-widget-elementor'),
                                    'icon' => 'eicon-ellipsis-h',
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}}  .' . $key . '-filter-box  .eszlwcf-filter-filed-wrapper ' => 'display: {{VALUE}};',
                            ],
                            'condition' => [
                                $key . '_filter_type_select!' => ['select', 'search'],
                                'wc_filter_select_options' => $key
                            ],
                        ]
                    );
                endif;
                $this->add_control(
                    $key . '_filter_label',
                    [
                        'label' => esc_html__('Filter Label', 'plugin-domain'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => sprintf(esc_html__('%s', 'esz-wc-product-filter-widget-elementor'), $name),
                        'placeholder' => esc_html__('Add Filter Label', 'esz-wc-product-filter-widget-elementor'),
                        'condition' => [
                            'wc_filter_select_options' => $key
                        ],
                    ]
                );
                $this->add_control(
                    $key . '_filter_type_select',
                    [
                        'label' => esc_html__('Filter Type', 'esz-wc-product-filter-widget-elementor'),
                        'type' => Controls_Manager::SELECT,
                        'default' => $options['default'],
                        'options' => $options['options'],
                        'condition' => [
                            'wc_filter_select_options' => $key
                        ],
                    ]
                );
                $this->add_control(
                    $key . '_filter_label_toggle',
                    [
                        'label' => esc_html__('Show Label', 'esz-wc-product-filter-widget-elementor'),
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                        'label_on' => esc_html__('Yes', 'esz-wc-product-filter-widget-elementor'),
                        'label_off' => esc_html__('No', 'esz-wc-product-filter-widget-elementor'),
                        'return_value' => 'yes',
                        'default' => 'yes',
                        'condition' => [
                            'wc_filter_select_options' => $key,
                            $key . '_filter_type_select' => ['color', 'image'],
                        ],
                    ]
                );
                $this->add_control(
                    'product_filter_' . $key . '_order',
                    [
                        'label' => sprintf(esc_html__('%s Position', 'esz-wc-product-filter-widget-elementor'), $name),
                        'type' => \Elementor\Controls_Manager::NUMBER,
                        'min' => 1,
                        'max' => 10,
                        'step' => 1,
                        'default' => $filter_pos,
                        'selectors' => [
                            '{{WRAPPER}} .' . $key . '-filter-box ' => 'order: {{VALUE}};',
                        ],
                    ]
                );
                $filter_pos++;
            endforeach;
        endif;
        $this->end_controls_section();
    }

    protected function register_controls_quick_view_modal_content() {
        $this->start_controls_section(
            'quick_view_modal_section',
            [
                'label' => __('Quick View Modal', 'esz-wc-product-filter-widget-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Image_Size::get_type(),
            [
                'name' => 'product_quick_view_modal_thumbnail_size',
                'default' => 'medium_large',
            ]
        );
        $this->add_control(
            'quick_view_product_title_tag',
            [
                'label' => esc_html__('Title Tag', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'default' => 'h5',
            ]
        );
        $this->end_controls_section();
    }

    protected function register_controls_not_found_content() {
        $this->start_controls_section(
            'not_found_section',
            [
                'label' => __('Not Found Product', 'esz-wc-product-filter-widget-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'not_found_message',
            [
                'label' => esc_html__('Not Found Message', 'esz-wc-product-filter-widget-elementor'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__('Sorry, There are no products matching your criteria.', 'esz-wc-product-filter-widget-elementor'),
                'placeholder' => esc_html__('Type your title here', 'esz-wc-product-filter-widget-elementor'),
                'separator' => 'before',
            ]
        );
        $this->end_controls_section();
    }

    protected function register_controls_style() {
        /**
         *  This is Layout Section of Style
         *
         **/
        $this->register_controls_layout_style();

        /**
         *  This is Positions Section of Style
         *
         **/
        $this->register_controls_positions_style();

        /**
         *  This is Box Section of Style
         *
         **/
        $this->register_controls_product_box_style();

        /**
         *  This is Product Content Section of Style
         *
         **/
        $this->register_controls_product_content_style();

        /**
         *  This is Filter Box Section of Style
         *
         **/
        $this->register_controls_filter_box_style();

        /**
         *  This is Filter List Section of Style
         *
         **/
        $this->register_controls_filter_list_items_style();

        /**
         *  This is Load More Button Section of Style
         *
         **/
        $this->register_controls_load_more_button_style();

        /**
         *  This is Loader Section of Style
         *
         **/
        $this->register_controls_loader_style();

        /**
         *  This is Quick View Model of Style
         *
         **/
        $this->register_controls_quick_view_style();
    }

    protected function register_controls_layout_style() {
        $this->start_controls_section(
            'layout_style',
            [
                'label' => esc_html__('Layout', 'esz-wc-product-filter-widget-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'products_column_spacing_vertical',
            [
                'label' => esc_html__('Column Spacing', 'esz-wc-product-filter-widget-elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => 30,
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-products-block' => 'grid-column-gap: {{VALUE}}px;',
                ],
            ]
        );
        $this->add_control(
            'products_column_spacing_horizontal',
            [
                'label' => esc_html__('Row Spacing', 'esz-wc-product-filter-widget-elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => 30,
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-products-block' => 'grid-row-gap: {{VALUE}}px;',
                ],
            ]
        );
        $this->add_responsive_control(
            'product_content_align',
            [
                'label' => esc_html__('Alignment', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'esz-wc-product-filter-widget-elementor'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'esz-wc-product-filter-widget-elementor'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'esz-wc-product-filter-widget-elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-products-block' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
    }

    protected function register_controls_positions_style() {
        $this->start_controls_section(
            'positions_style',
            [
                'label' => esc_html__('Positions', 'esz-wc-product-filter-widget-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $position = 0;
        $order_positions = array(
            'img' => ['label' => "Image", 'class' => "eszlwcf-product-thumb"],
            'title' => ['label' => "Title", 'class' => "eszlwcf-product-title"],
            'meta' => ['label' => 'Meta', 'class' => "eszlwcf-product-meta"],
            'button' => ['label' => "Button", 'class' => "eszlwcf-product-button"]
        );
        foreach ($order_positions as $key => $val):
            $this->add_control(
                'product' . $key . '_order',
                [
                    'label' => sprintf(esc_html__('%s Position', 'esz-wc-product-filter-widget-elementor'), $val['label']),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 10,
                    'step' => 1,
                    'default' => $position,
                    'selectors' => [
                        '{{WRAPPER}} .' . $val['class'] => 'order: {{VALUE}};',
                    ],
                ]
            );
            $position++;
        endforeach;
        $this->end_controls_section();
    }

    protected function register_controls_product_box_style() {
        $this->start_controls_section(
            'section_design_box_style',
            [
                'label' => esc_html__('Product Box', 'esz-wc-product-filter-widget-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'heading_box_border',
            [
                'label' => esc_html__('Border', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'box_border_width',
                'selector' => '{{WRAPPER}} .eszlwcf-product',
            ]
        );
        $this->add_responsive_control(
            'box_border_radius',
            [
                'label' => esc_html__('Border Radius', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-product' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'heading_box_style',
            [
                'label' => esc_html__('Style', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->start_controls_tabs('bg_effects_tabs');
        $this->start_controls_tab('classic_style_normal',
            [
                'label' => esc_html__('Normal', 'esz-wc-product-filter-widget-elementor'),
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'box_bg_color',
                'label' => esc_html__('Background', 'esz-wc-product-filter-widget-elementor'),
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .eszlwcf-product',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .eszlwcf-product',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('classic_style_hover',
            [
                'label' => esc_html__('Hover', 'esz-wc-product-filter-widget-elementor'),
            ]
        );
        $this->add_control(
            'box_border_color_hover',
            [
                'label' => esc_html__('Border Color', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-product:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'box_bg_color_hover',
                'label' => esc_html__('Background', 'esz-wc-product-filter-widget-elementor'),
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .eszlwcf-product:hover',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow_hover',
                'selector' => '{{WRAPPER}} .eszlwcf-product:hover',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control(
            'heading_box_spacing',
            [
                'label' => esc_html__('Spacing', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'box_padding',
            [
                'label' => esc_html__('Padding', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__('Content Padding', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'allowed_dimensions' => 'horizontal',
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-product-title' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}}  {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .eszlwcf-product-meta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
    }

    protected function register_controls_product_content_style() {
        $this->start_controls_section(
            'product_general_style',
            [
                'label' => esc_html__('Product Content', 'esz-wc-product-filter-widget-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'products_layout_skin' => 'classic'
                ],
            ]
        );
        $this->add_control(
            'product_thumbnail_heading',
            [
                'label' => esc_html__('Thumbnail', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'product_thumbnail_bottom_space',
            [
                'label' => esc_html__('Spacing', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-product-thumb' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'product_title_heading',
            [
                'label' => esc_html__('Title', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'product_title_bottom_space',
            [
                'label' => esc_html__('Spacing', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-product-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'product_title_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
                ],
                'selector' => '{{WRAPPER}} .eszlwcf-product-title',
            ]
        );
        $this->start_controls_tabs('product_title_effects_tabs');
        foreach ($this->tab_state as $key => $tab):
            $state = ($key === 'hover') ? esc_attr(':hover') : '';
            $this->start_controls_tab('product_title_style' . $key,
                [
                    'label' => sprintf(esc_html__('%s', 'esz-wc-product-filter-widget-elementor'), $tab),
                ]
            );
            $this->add_control(
                'product_title_color' . $key,
                [
                    'label' => esc_html__('Color', 'esz-wc-product-filter-widget-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'global' => [
                        'default' => ($key === 'normal') ? Global_Colors::COLOR_PRIMARY : '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eszlwcf-product-title' . $state => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Text_Shadow::get_type(),
                [
                    'name' => 'product_title_shadow' . $key,
                    'selector' => '{{WRAPPER}} .eszlwcf-product-title' . $state,
                ]
            );
            $this->end_controls_tab();
        endforeach;
        $this->end_controls_tabs();
        $this->add_control(
            'heading_meta',
            [
                'label' => esc_html__('Pricing', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'pricing_bottom_space',
            [
                'label' => esc_html__('Spacing', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 5,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-product-meta' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs('product_price_tabs');
        $prices = array(
            'regular' => [
                'label' => 'Regular Price',
                'selector' => '{{WRAPPER}} .eszlwcf-product-price-regular:not(.sale-price) span, {{WRAPPER}} .eszlwcf-product-price-regular.sale-price del span',
                'default_typo' => Global_Typography::TYPOGRAPHY_TEXT,
                'default_color' => '#7e7e7e',
            ],
            'sale' => [
                'label' => 'Sale Price',
                'selector' => '{{WRAPPER}} .eszlwcf-product-price-regular.sale-price ins, {{WRAPPER}} .eszlwcf-product-price-regular.sale-price ins span',
                'default_typo' => Global_Typography::TYPOGRAPHY_ACCENT,
                'default_color' => '#008000',
            ]
        );
        foreach ($prices as $key => $tab):
            $this->start_controls_tab('product_price_tab' . $key,
                [
                    'label' => sprintf(esc_html__('%s', 'esz-wc-product-filter-widget-elementor'), $tab['label']),
                ]
            );
            $this->add_control(
                'product_price_meta_color' . $key,
                [
                    'label' => sprintf(esc_html__('%s Color', 'esz-wc-product-filter-widget-elementor'), $tab['label']),
                    'type' => Controls_Manager::COLOR,
                    'default' => $tab['default_color'],
                    'selectors' => [
                        $tab['selector'] => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_price_typography_' . $key,
                    'global' => [
                        'default' => $tab['default_typo'],
                    ],
                    'selector' => $tab['selector'],
                ]
            );
            $this->end_controls_tab();
        endforeach;
        $this->end_controls_tabs();
        $this->add_control(
            'heading_badge',
            [
                'label' => esc_html__('Badge', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'product_sale_badge_color',
            [
                'label' => esc_html__('Color', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-product-badge-sale' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'product_sale_badge_bg_color',
            [
                'label' => esc_html__('Badge Background Color', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-product-badge-sale' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tabs();
        $this->add_control(
            'product_button_heading',
            [
                'label' => esc_html__('Button', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'display_product_button' => 'yes'
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'product_button_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_ACCENT,
                ],
                'selector' => '{{WRAPPER}} .eszlwcf-product-button a',
                'condition' => [
                    'display_product_button' => 'yes'
                ],
            ]
        );
        $this->start_controls_tabs('product_button_effects_tabs');
        foreach ($this->tab_state as $key => $tab):
            $state = ($key === 'hover') ? esc_attr(':hover') : '';
            $this->start_controls_tab('product_button_style' . $key,
                [
                    'label' => sprintf(esc_html__('%s', 'esz-wc-product-filter-widget-elementor'), $tab),
                    'condition' => [
                        'display_product_button' => 'yes'
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'product_button_box_shadow' . $key,
                    'selector' => '{{WRAPPER}} .eszlwcf-product-button' . $state . 'a, .eszlwcf-product-button' . $state . 'i ',
                    'condition' => [
                        'display_product_button' => 'yes'
                    ],
                ]
            );
            $this->add_control(
                'product_button_color' . $key,
                [
                    'label' => esc_html__('Color', 'esz-wc-product-filter-widget-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eszlwcf-product-button' . $state . ' a, {{WRAPPER}} .eszlwcf-product-button' . $state . ' i' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .eszlwcf-product-button' . $state . ' a svg' => 'fill: {{VALUE}};',
                    ],
                    'default' => ($key === 'normal') ? '#fff' : '',
                    'condition' => [
                        'display_product_button' => 'yes'
                    ],
                ]
            );
            if ($key === 'hover'):
                $this->add_control(
                    'product_button_border_color' . $key,
                    [
                        'label' => esc_html__('Border Color', 'esz-wc-product-filter-widget-elementor'),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .eszlwcf-product-button' . $state . ' a' => 'border-color: {{VALUE}};',
                        ],
                        'condition' => [
                            'display_product_button' => 'yes'
                        ],
                    ]
                );
            endif;
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'product_button_background' . $key,
                    'label' => esc_html__('Background', 'plugin-domain'),
                    'types' => ['classic', 'gradient'],
                    'exclude' => ['image'],
                    'selector' => '{{WRAPPER}} .eszlwcf-product-button' . $state . ' a',
                    'fields_options' => [
                        'background' => [
                            'default' => 'classic',
                        ],
                        'color' => [
                            'default' => ($key === 'normal') ? '#111' : '',
                        ],
                    ],
                    'separator' => 'before',
                ]
            );
            $this->end_controls_tab();
        endforeach;
        $this->end_controls_tabs();
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'product_button_border',
                'selector' => '{{WRAPPER}} .eszlwcf-product-button a',
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'product_button_padding',
            [
                'label' => esc_html__('Button Padding', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => ['top' => '6', 'right' => '24', 'bottom' => '8', 'left' => '24', 'unit' => 'px', 'isLinked' => false],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-product-button a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->end_controls_section();
    }

    protected function register_controls_filter_box_style() {
        $this->start_controls_section(
            'filter_block_style',
            [
                'label' => esc_html__('Filter Box', 'esz-wc-product-filter-widget-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'heading_filter_list_frame',
            [
                'label' => esc_html__('Filter List', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'filter_frame_box_shadow',
                'selector' => '{{WRAPPER}} .eszlwcf-filter-form',
                'description' => esc_html__('If you are enable box shadow please set appropriate margin for prevent confliction of design', 'esz-wc-product-filter-widget-elementor'),
            ]
        );
        $this->add_responsive_control(
            'filter_frame_border_radius',
            [
                'label' => esc_html__('Border Radius', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-filter-form' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'filter_frame_padding',
            [
                'label' => esc_html__('Padding', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-filter-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'filter_frame_margin',
            [
                'label' => esc_html__('Margin', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-filter-form' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'heading_filter_list_box',
            [
                'label' => esc_html__('Filter List Box', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'filter_list_box_border',
                'selector' => '{{WRAPPER}} .eszlwcf-filter-block',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'filter_list_box_box_shadow',
                'selector' => '{{WRAPPER}} .eszlwcf-filter-block',
            ]
        );
        $this->add_responsive_control(
            'filter_list_box_border_radius',
            [
                'label' => esc_html__('Border Radius', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-filter-block' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'filter_list_box_padding',
            [
                'label' => esc_html__('Padding', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-filter-filed-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'filter_list_box_margin',
            [
                'label' => esc_html__('Margin', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-filter-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => ['top' => '0', 'right' => '0', 'bottom' => '30', 'left' => '0', 'unit' => 'px', 'isLinked' => false],
            ]
        );
        $this->add_control(
            'heading_filter_list_heading',
            [
                'label' => esc_html__('Filter List Heading', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'filter_list_heading_padding',
            [
                'label' => esc_html__('Padding', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-filter-heading-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => ['top' => '0', 'right' => '0', 'bottom' => '10', 'left' => '0', 'unit' => 'px', 'isLinked' => false],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'filter_list_heading_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
                ],
                'selector' => '{{WRAPPER}} .eszlwcf-filter-block-heading',
            ]
        );
        $this->add_control(
            'filter_list_heading_color',
            [
                'label' => esc_html__('Color', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-filter-block-heading' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'filter_list_heading_bg_color',
            [
                'label' => esc_html__('Background Color', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-filter-heading-box' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
    }

    protected function register_controls_filter_list_items_style() {
        $this->start_controls_section(
            'filter_list_item_style',
            [
                'label' => esc_html__('Filter List Item', 'esz-wc-product-filter-widget-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'heading_filter_list_item',
            [
                'label' => esc_html__('Filter List Item', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'filter_list_item_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
                'selector' => '{{WRAPPER}} .eszlwcf-filter-filed-wrapper  label',
            ]
        );
        $this->add_control(
            'filter_list_item_color',
            [
                'label' => esc_html__('Color', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_TEXT,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-filter-filed-wrapper  label' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'heading_filter_list_item_range',
            [
                'label' => esc_html__('Filter List Range', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'filter_list_item_range_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_ACCENT,
                ],
                'selector' => '{{WRAPPER}} .eszlwcf-range-value-display',
            ]
        );
        $this->add_control(
            'filter_list_item_range_color',
            [
                'label' => esc_html__('Color', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_ACCENT,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-range-value-display' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->start_controls_tabs('filter_list_range_handle_bar_tabs');
        $this->start_controls_tab('filter_list_range_handle_bar_style_normal',
            [
                'label' => esc_html__('Normal', 'esz-wc-product-filter-widget-elementor'),
            ]
        );
        $this->add_control(
            'filter_list_range_handle_bar_color_normal',
            [
                'label' => esc_html__(' Bar Color ', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-filter-filed-wrapper .eszlwcf-price-range .ui-slider-handle.ui-state-default' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('filter_list_range_handle_bar_style_active',
            [
                'label' => esc_html__('Active', 'esz-wc-product-filter-widget-elementor'),
            ]
        );
        $this->add_control(
            'filter_list_range_handle_bar_color_active',
            [
                'label' => esc_html__('Handle Bar Color ', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#4E7661',
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-filter-filed-wrapper .eszlwcf-price-range .ui-slider-handle.ui-state-active' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eszlwcf-filter-filed-wrapper .eszlwcf-price-range .ui-slider-range' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control(
            'heading_filter_checkbox_radio',
            [
                'label' => esc_html__('Checkbox / Radio', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'filter_input_spacing_bottom_checkbox_radio',
            [
                'label' => esc_html__('Spacing', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 8,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-field-type-checkbox .eszlwcf-field-box,
                        {{WRAPPER}} .eszlwcf-field-type-radio .eszlwcf-field-box' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eszlwcf-field-type-checkbox .eszlwcf-field-box:last-child,
                        {{WRAPPER}} .eszlwcf-field-type-radio .eszlwcf-field-box:last-child' => 'margin-bottom: 0;',
                ],
            ]
        );
        $targets = array(
            'mark' => ['label' => 'Mark', 'default' => '#6c6f77', 'property' => 'border-color', 'selector' => '::after',],
            'border' => ['label' => 'Border', 'default' => '#e2e4ea', 'property' => 'border-color', 'selector' => '::before',],
            'bg' => ['label' => 'Background', 'default' => '#f7f8f9', 'property' => 'background-color', 'selector' => '::before',]
        );
        foreach ($targets as $key => $target):
            $element = '{{WRAPPER}} .eszlwcf-field-type-checkbox label' . $target['selector'] . ',
            {{WRAPPER}} .eszlwcf-field-type-radio label' . $target['selector'] . ',
            {{WRAPPER}} .eszlwcf-field-type-color label' . $target['selector'] . ',
            {{WRAPPER}} .eszlwcf-field-type-image label' . $target['selector'];
            $css = $target['property'] . ': {{VALUE}};';
            $selector = [$element => $css];
            if ($key == 'mark') {
                $selector['{{WRAPPER}} .eszlwcf-field-type-radio label' . $target['selector']] = 'background-color: {{VALUE}};';
            }
            $this->add_control(
                'filter_list_input_checkbox_radio' . $key,
                [
                    'label' => sprintf(esc_html__('%s', 'esz-wc-product-filter-widget-elementor'), $target['label']),
                    'type' => Controls_Manager::COLOR,
                    'default' => $target['default'],
                    'selectors' => $selector,
                ]
            );
        endforeach;

        $this->add_control(
            'heading_filter_button',
            [
                'label' => esc_html__('Button', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'filter_input_spacing_bottom_button',
            [
                'label' => esc_html__('Spacing', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 8,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-field-type-button .eszlwcf-field-box' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'filter_list_input_button_border_color',
            [
                'label' => esc_html__('Border Color', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#e2e4ea',
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-field-type-button .eszlwcf-field-box label' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'filter_list_input_button_background_color',
            [
                'label' => esc_html__('Background Color', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-field-type-button .eszlwcf-field-box label' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'filter_list_input_button_border_radius',
            [
                'label' => esc_html__('Border Radius', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-field-type-button .eszlwcf-field-box label' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'heading_filter_select',
            [
                'label' => esc_html__('Select', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'filter_input_select_border_radius',
            [
                'label' => esc_html__('Border Radius', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 4,
                ],
                'selectors' => [
                    '{{WRAPPER}} select' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $colors = array(
            'color' => ['label' => 'Color', 'default' => '#111', 'property' => 'color',],
            'border' => ['label' => 'Border', 'default' => '#e2e4ea', 'property' => 'border-color',],
            'bg' => ['label' => 'Background', 'default' => '#f7f8f9', 'property' => 'background-color',],
        );
        foreach ($colors as $color_handle => $color):
            $this->add_control(
                'filter_list_select_' . $color_handle,
                [
                    'label' => sprintf(esc_html__('%s', 'esz-wc-product-filter-widget-elementor'), $color['label']),
                    'type' => Controls_Manager::COLOR,
                    'default' => $color['default'],
                    'selectors' => [
                        '{{WRAPPER}} select' => $color['property'] . ': {{VALUE}};',
                    ],
                ]
            );
        endforeach;
        $this->add_control(
            'heading_filter_color_image_option',
            [
                'label' => esc_html__('Color & Image', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->start_controls_tabs('filter_option_image_color_tabs');
        $options = array('image' => 'Image', 'color' => 'Color');
        foreach ($options as $key => $tab):
            $selector = ($key === 'color') ? '.eszlwcf-field-type-color .eszlwcf-filter-filed-wrapper input:checked + label span' : '.eszlwcf-field-type-image .eszlwcf-filter-filed-wrapper input:checked + label img';
            $this->start_controls_tab('filter_option_tab_' . $key,
                [
                    'label' => sprintf(esc_html__('%s', 'esz-wc-product-filter-widget-elementor'), $tab),
                ]
            );
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'filter_list_option_' . $key,
                    'selector' => '{{WRAPPER}} ' . $selector,
                ]
            );
            $this->end_controls_tab();
        endforeach;
        $this->end_controls_tabs();


        $this->add_control(
            'heading_filter_search_input',
            [
                'label' => esc_html__('Search Input', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'filter_list_search_input_border_radius',
            [
                'label' => esc_html__('Border Radius', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} input[type="search"]' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'filter_list_search_input_border_color',
            [
                'label' => esc_html__('Border Color', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#e2e4ea',
                'selectors' => [
                    '{{WRAPPER}} input[type="search"]' =>  'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'heading_filter_clear_option',
            [
                'label' => esc_html__('Clear Options', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'filter_clear_option_border',
            [
                'label' => esc_html__('Border Size', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-filter-clear-block .eszlwcf-clear, {{WRAPPER}} .eszlwcf-not-found-section .eszlwcf-clear' => 'border: {{SIZE}}{{UNIT}} solid;',
                ],
            ]
        );
        $this->add_responsive_control(
            'filter_clear_option_border_radius',
            [
                'label' => esc_html__('Border Radius', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-filter-clear-block .eszlwcf-clear, {{WRAPPER}} .eszlwcf-not-found-section .eszlwcf-clear' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $colors_state = array('normal' => 'Normal', 'hover' => 'Hover');
        $this->start_controls_tabs('filter_clear_option_state');
        foreach ($this->tab_state as $key => $tab):
            $state = ($key === 'hover') ? esc_attr(':hover') : '';
            $this->start_controls_tab('filter_clear_option_state' . $key,
                [
                    'label' => sprintf(esc_html__('%s', 'esz-wc-product-filter-widget-elementor'), $tab),
                ]
            );
            $colors = array('color' => 'Color', 'border-color' => 'Border Color', 'background-color' => 'Background Color');
            $default = array('color' => '', 'border-color' => '#111', 'background-color' => '');
            foreach ($colors as $color_handle => $color):
                $this->add_control(
                    'filter_list_heading_' . $color_handle . $key,
                    [
                        'label' => sprintf(esc_html__('%s', 'esz-wc-product-filter-widget-elementor'), $color_handle),
                        'type' => Controls_Manager::COLOR,
                        'default' => $default[$color_handle],
                        'selectors' => [
                            '{{WRAPPER}} .eszlwcf-filter-clear-block .eszlwcf-clear' . $state . ', {{WRAPPER}} .eszlwcf-not-found-section .eszlwcf-clear' . $state => $color_handle . ': {{VALUE}};',
                        ],
                    ]
                );
            endforeach;
            $this->end_controls_tab();
        endforeach;
        $this->end_controls_tabs();
        $dimension_default = array(
            'padding' => ['top' => '5', 'right' => '15', 'bottom' => '5', 'left' => '15', 'unit' => 'px', 'isLinked' => false],
            'margin' => ['top' => '0', 'right' => '10', 'bottom' => '10', 'left' => '0', 'unit' => 'px', 'isLinked' => false],
        );
        $dimension = array('padding' => 'Padding', 'margin' => 'Margin');
        foreach ($dimension as $key => $value) :
            $this->add_responsive_control(
                'filter_clear_option_' . $key,
                [
                    'label' => sprintf(esc_html__('Option %s', 'esz-wc-product-filter-widget-elementor'), $value),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 50,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eszlwcf-filter-clear-block .eszlwcf-clear, {{WRAPPER}} .eszlwcf-not-found-section .eszlwcf-clear' => $key . ': {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'default' => $dimension_default[$key],
                ]
            );
        endforeach;
        $this->end_controls_section();
    }

    protected function register_controls_load_more_button_style() {
        $this->start_controls_section(
            'load_more_button_style',
            [
                'label' => esc_html__('Load More Button', 'esz-wc-product-filter-widget-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'button_align',
            [
                'label' => esc_html__('Alignment', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'esz-wc-product-filter-widget-elementor'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'esz-wc-product-filter-widget-elementor'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'esz-wc-product-filter-widget-elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-load-more' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'load_more_button_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_ACCENT,
                ],
                'selector' => '{{WRAPPER}} .eszlwcf-load-more a',
            ]
        );
        $this->add_control(
            'heading_load_more_button_style',
            [
                'label' => esc_html__('Style', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->start_controls_tabs('load_more_button_effects_tabs');
        $this->start_controls_tab('load_more_button_style_normal',
            [
                'label' => esc_html__('Normal', 'esz-wc-product-filter-widget-elementor'),
            ]
        );
        $this->add_control(
            'load_more_button_color',
            [
                'label' => esc_html__('Text Color', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-load-more a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'load_more_button_bg_color',
                'label' => esc_html__('Background', 'esz-wc-product-filter-widget-elementor'),
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .eszlwcf-load-more a',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'default' => '#111',
                    ],
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'load_more_button_box_shadow',
                'selector' => '{{WRAPPER}} .eszlwcf-load-more a',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('load_more_button_style_hover',
            [
                'label' => esc_html__('Hover', 'esz-wc-product-filter-widget-elementor'),
            ]
        );
        $this->add_control(
            'load_more_button_color_hover',
            [
                'label' => esc_html__('Text Color', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-load-more a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'load_more_button_border_color_hover',
            [
                'label' => esc_html__('Border Color', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-load-more a:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'load_more_button_bg_color_hover',
                'label' => esc_html__('Background', 'esz-wc-product-filter-widget-elementor'),
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .eszlwcf-load-more a:hover',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'load_more_button_box_shadow_hover',
                'selector' => '{{WRAPPER}} .eszlwcf-load-more a:hover',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control(
            'heading_load_more_button_border',
            [
                'label' => esc_html__('Border', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} .eszlwcf-load-more a',
            ]
        );
        $this->add_responsive_control(
            'load_more_button_border_radius',
            [
                'label' => esc_html__('Border Radius', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => ['top' => '5', 'right' => '5', 'bottom' => '5', 'left' => '5', 'unit' => 'px', 'isLinked' => true],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-load-more a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'heading_load_more_button_spaction',
            [
                'label' => esc_html__('Spacing', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'load_more_button_margin',
            [
                'label' => esc_html__('Button margin', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => ['top' => '30', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px', 'isLinked' => false],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-load-more a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'load_more_button_padding',
            [
                'label' => esc_html__('Button Padding', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => ['top' => '6', 'right' => '24', 'bottom' => '8', 'left' => '24', 'unit' => 'px', 'isLinked' => false],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-load-more a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
    }

    protected function register_controls_loader_style() {
        $this->start_controls_section(
            'loader_style',
            [
                'label' => esc_html__('Loader', 'esz-wc-product-filter-widget-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'loader_image_size',
            [
                'label' => esc_html__('Loader Image Size', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 250,
                ],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-loader img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'loader_bg_color',
            [
                'label' => esc_html__('Loader Background Color', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff5',
                'selectors' => [
                    '{{WRAPPER}} .eszlwcf-loader' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
    }

    protected function register_controls_quick_view_style() {

        $this->start_controls_section(
            'quick_view_style',
            [
                'label' => esc_html__('Quick View Model', 'esz-wc-product-filter-widget-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'quick_view_modal_heading',
            [
                'label' => esc_html__('Modal', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'modal_bg_color',
            [
                'label' => esc_html__('Modal Background Color', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .esz-product-modal-frame .esz-product-modal-container' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'modal_overlay_bg_color',
            [
                'label' => esc_html__('Modal Overlay Color', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1115',
                'selectors' => [
                    '{{WRAPPER}} .esz-product-modal-frame' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'quick_view_icon_heading',
            [
                'label' => esc_html__('Quick View', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'quick_view_icon_color',
            [
                'label' => esc_html__('Icon Color', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eszwcf-quick-view i' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'quick_view_icon_bg_color',
            [
                'label' => esc_html__('Icon Background Color', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#111',
                'selectors' => [
                    '{{WRAPPER}} .eszwcf-quick-view' => 'background-color: {{VALUE}};',
                ],
            ]
        );


        $this->add_control(
            'quick_view_modal_content_heading',
            [
                'label' => esc_html__('Modal Content', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        /*
         * Modal Product Title
         */
        $this->add_control(
            'modal_product_title_heading',
            [
                'label' => esc_html__('Title', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_responsive_control(
            'modal_product_title_bottom_space',
            [
                'label' => esc_html__('Spacing', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 15,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .esz-modal-product-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'modal_product_title_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .esz-modal-product-title',
            ]
        );
        $this->add_control(
            'modal_product_title_color',
            [
                'label' => esc_html__('Title Color', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#111',
                'selectors' => [
                    '{{WRAPPER}} .esz-modal-product-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        /*
         * Modal Product pricing
         */
        $this->add_control(
            'modal_heading_pricing',
            [
                'label' => esc_html__('Pricing', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'modal_pricing_bottom_space',
            [
                'label' => esc_html__('Spacing', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 5,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .esz-modal-product-price' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs('modal_product_price_tabs');
        $prices = array(
            'regular' => [
                'label' => 'Regular Price',
                'selector' => '{{WRAPPER}} .esz-modal-product-price-regular:not(.sale-price) span, {{WRAPPER}} .esz-modal-product-price-regular.sale-price del span',
                'default_typo' => Global_Typography::TYPOGRAPHY_TEXT,
                'default_color' => '#7e7e7e',
            ],
            'sale' => [
                'label' => 'Sale Price',
                'selector' => '{{WRAPPER}} .esz-modal-product-price-regular.sale-price ins, {{WRAPPER}} .esz-modal-product-price-regular.sale-price ins span',
                'default_typo' => Global_Typography::TYPOGRAPHY_ACCENT,
                'default_color' => '#008000',
            ]
        );
        foreach ($prices as $key => $tab):
            $this->start_controls_tab('modal_product_price_tab' . $key,
                [
                    'label' => sprintf(esc_html__('%s', 'esz-wc-product-filter-widget-elementor'), $tab['label']),
                ]
            );
            $this->add_control(
                'modal_product_price_meta_color' . $key,
                [
                    'label' => sprintf(esc_html__('%s Color', 'esz-wc-product-filter-widget-elementor'), $tab['label']),
                    'type' => Controls_Manager::COLOR,
                    'default' => $tab['default_color'],
                    'selectors' => [
                        $tab['selector'] => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'modal_product_price_typography_' . $key,
                    'global' => [
                        'default' => $tab['default_typo'],
                    ],
                    'selector' => $tab['selector'],
                ]
            );
            $this->end_controls_tab();
        endforeach;
        $this->end_controls_tabs();
        /*
         * Modal Product Desc
         */
        $this->add_control(
            'modal_product_desc_heading',
            [
                'label' => esc_html__('Product Description', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'modal_product_desc_bottom_space',
            [
                'label' => esc_html__('Spacing', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 15,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .esz-modal-product-desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'modal_product_desc_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
                'selector' => '{{WRAPPER}} .esz-modal-product-desc',
            ]
        );
        $this->add_control(
            'modal_product_desc_color',
            [
                'label' => esc_html__('Title Color', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#7e7e7e',
                'selectors' => [
                    '{{WRAPPER}} .esz-modal-product-desc' => 'color: {{VALUE}};',
                ],
            ]
        );
        /*
         * Modal Product Meta
         */
        $this->add_control(
            'modal_product_meta_heading',
            [
                'label' => esc_html__('Product Meta', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'modal_product_meta_bottom_space',
            [
                'label' => esc_html__('Spacing', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 8,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .esz-modal-product-meta > *' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'modal_product_meta_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
                'selector' => '{{WRAPPER}} .esz-modal-product-meta',
            ]
        );
        $this->add_control(
            'modal_product_meta_label_color',
            [
                'label' => esc_html__('Title Color', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#111',
                'selectors' => [
                    '{{WRAPPER}} .esz-modal-product-meta label' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'modal_product_meta_color',
            [
                'label' => esc_html__('Title Color', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#7e7e7e',
                'selectors' => [
                    '{{WRAPPER}} .esz-modal-product-meta' => 'color: {{VALUE}};',
                ],
            ]
        );

        /*
         * Modal Product Button
         */
        $this->add_control(
            'modal_product_button_heading',
            [
                'label' => esc_html__('Button', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'modal_product_button_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_ACCENT,
                ],
                'selector' => '{{WRAPPER}} .esz-modal-product-cart-button a',
            ]
        );
        $this->start_controls_tabs('modal_product_button_effects_tabs');
        foreach ($this->tab_state as $key => $tab):
            $state = ($key === 'hover') ? esc_attr(':hover') : '';
            $this->start_controls_tab('modal_product_button_style' . $key,
                [
                    'label' => sprintf(esc_html__('%s', 'esz-wc-product-filter-widget-elementor'), $tab),
                ]
            );
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'modal_product_button_box_shadow' . $key,
                    'selector' => '{{WRAPPER}} .esz-modal-product-cart-button' . $state . ' a',
                    'condition' => [
                        'display_product_button' => 'yes'
                    ],
                ]
            );
            $this->add_control(
                'modal_product_button_color' . $key,
                [
                    'label' => esc_html__('Color', 'esz-wc-product-filter-widget-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .esz-modal-product-cart-button' . $state . ' a, {{WRAPPER}} .esz-modal-product-cart-button' . $state . ' i' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .esz-modal-product-cart-button' . $state . ' a svg' => 'fill: {{VALUE}};',
                    ],
                    'default' => ($key === 'normal') ? '#fff' : '',
                ]
            );
            if ($key === 'hover'):
                $this->add_control(
                    'modal_product_button_border_color' . $key,
                    [
                        'label' => esc_html__('Border Color', 'esz-wc-product-filter-widget-elementor'),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .esz-modal-product-cart-button' . $state . ' a' => 'border-color: {{VALUE}};',
                        ],
                        'condition' => [
                            'display_product_button' => 'yes'
                        ],
                    ]
                );
            endif;
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'modal_product_button_background' . $key,
                    'label' => esc_html__('Background', 'plugin-domain'),
                    'types' => ['classic', 'gradient'],
                    'exclude' => ['image'],
                    'selector' => '{{WRAPPER}} .esz-modal-product-cart-button' . $state . ' a',
                    'fields_options' => [
                        'background' => [
                            'default' => 'classic',
                        ],
                        'color' => [
                            'default' => ($key === 'normal') ? '#111' : '',
                        ],
                    ],
                    'separator' => 'before',
                ]
            );
            $this->end_controls_tab();
        endforeach;
        $this->end_controls_tabs();
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'modal_product_button_border',
                'selector' => '{{WRAPPER}} .esz-modal-product-cart-button a',
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'modal_product_button_padding',
            [
                'label' => esc_html__('Button Padding', 'esz-wc-product-filter-widget-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => ['top' => '6', 'right' => '24', 'bottom' => '8', 'left' => '24', 'unit' => 'px', 'isLinked' => false],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .esz-modal-product-cart-button a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();


    }


    /**
     * Render widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $widget_id = $this->get_unique_selector();
        $esz_the_query = $this->eszwcq->eszlwcf_get_query_data(array(), $settings);
        include plugin_dir_path(__DIR__) . 'templates/product-filter-main-view.php';
    }
}

    