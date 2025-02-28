<?php

use Elementor\Icons_Manager;

class Eszlwcf_Custom_Function {
    protected $eszwcq;
    protected $eszlwcf_filters_type;
    protected $eszlwcf_attributes;
    protected $eszlwcf_registered_taxonomies;
    protected $eszlwcf_registered_taxonomies_terms;
    protected $eszlwcf_registered_taxonomies_terms_names;
    protected $eszlwcf_attributes_taxonomies;
    protected $eszlwcf_attributes_taxonomies_names;
    protected $eszlwcf_attributes_terms;
    protected $eszlwcf_attributes_taxonomies_terms_names;
    protected $eszlwcf_product_metas;
    protected $eszlwcf_filter_product_metas;
    protected $eszlwcf_filter_product_extra;
    private $eszlwcf_product_query;
    private $eszlwcf_products;
    const ALLOWED_HTML_WRAPPER_TAGS = ['a', 'article', 'aside', 'div', 'footer', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'header', 'main', 'nav', 'p', 'section', 'span',];

    public function __construct() {
        $this->eszwcq = new \Eszlwcf_Product_Filter_Query_Controller();
        $this->eszlwcf_get_data();
    }

    public static function validate_html_tag($tag) {
        return in_array(strtolower($tag), self::ALLOWED_HTML_WRAPPER_TAGS) ? $tag : 'div';
    }

    protected function eszlwcf_get_data() {
        $this->eszlwcf_filters_type = $this->eszlwcf_filters_type();
        $this->eszlwcf_attributes = array();
        $this->eszlwcf_attributes = wc_get_attribute_taxonomies();
        $this->eszlwcf_attributes_taxonomies = $this->eszlwcf_get_attributes_taxonomies();
        $this->eszlwcf_attributes_terms = $this->eszlwcf_get_attributes_terms();
        $this->eszlwcf_attributes_taxonomies_terms_names = $this->eszlwcf_get_attributes_taxonomies_terms_names();
        $this->eszlwcf_registered_taxonomies = $this->eszlwcf_get_registered_taxonomies();
        $this->eszlwcf_registered_taxonomies_terms = $this->eszlwcf_registered_taxonomies_terms();
        $this->eszlwcf_registered_taxonomies_terms_names = $this->eszlwcf_registered_taxonomies_terms_names();
        $this->eszlwcf_filter_product_metas = $this->eszlwcf_get_filter_product_metas();
        $this->eszlwcf_filter_product_extra = $this->eszlwcf_get_filter_product_extra();
    }

    public function eszlwcf_filters_custom_data() {
        $data = array();
        $data['eszlwcf_filters_type'] = $this->eszlwcf_filters_type;
        $data['eszlwcf_attributes'] = $this->eszlwcf_attributes;
        $data['eszlwcf_registered_taxonomies'] = $this->eszlwcf_registered_taxonomies;
        $data['eszlwcf_registered_taxonomies_terms'] = $this->eszlwcf_registered_taxonomies_terms;
        $data['eszlwcf_registered_taxonomies_terms_names'] = $this->eszlwcf_registered_taxonomies_terms_names;
        $data['eszlwcf_attributes_taxonomies'] = $this->eszlwcf_attributes_taxonomies;
        $data['eszlwcf_attributes_taxonomies_names'] = $this->eszlwcf_attributes_taxonomies_names;
        $data['eszlwcf_attributes_terms'] = $this->eszlwcf_attributes_terms;
        $data['eszlwcf_attributes_taxonomies_terms_names'] = $this->eszlwcf_attributes_taxonomies_terms_names;
        $data['eszlwcf_product_metas'] = $this->eszlwcf_product_metas;
        $data['eszlwcf_filter_product_metas'] = $this->eszlwcf_filter_product_metas;
        $data['eszlwcf_product_query'] = $this->eszlwcf_product_query;
        $data['eszlwcf_products'] = $this->eszlwcf_products;
        return $data;
    }

    public function eszlwcf_filters_type() {
        return array(
            'wc_attributes' => esc_html__('Product Attributes', 'esz-wc-product-filter-widget-elementor'),
            'wc_registered_taxonomies' => esc_html__('Product Taxonomies', 'esz-wc-product-filter-widget-elementor'),
            'wc_product_metas' => esc_html__('Product Info', 'esz-wc-product-filter-widget-elementor')
        );
    }

    protected function eszlwcf_taxonomies_terms($taxonomies) {
        $terms = array();
        if (!empty($taxonomies)) {
            foreach ($taxonomies as $taxonomy => $label) {
                $terms[$taxonomy] = get_terms(array(
                    'taxonomy' => $taxonomy,
                    'hide_empty' => false,
                ));
            }
        }
        return $terms;
    }

    protected function eszlwcf_taxonomies_terms_names($taxonomies) {
        $terms = array();
        if (!empty($taxonomies)) {
            foreach ($taxonomies as $taxonomy => $label) {
                $cats = get_terms(array(
                    'taxonomy' => $taxonomy,
                    'hide_empty' => false,
                ));
                $terms[$taxonomy] = (!empty($cats)) ? array_column($cats, 'name', 'slug') : array();
            }
        }
        return $terms;
    }

    protected function eszlwcf_get_registered_taxonomies() {
        $taxonomies = array();
        $registered_taxonomies = array('product_cat', 'product_tag');
        apply_filters('eszlwcf_registered_taxonomies', $registered_taxonomies);
        foreach ($registered_taxonomies as $taxonomy) {
            $tax = get_taxonomy($taxonomy);
            $taxonomies[$tax->name] = $tax->labels->menu_name;
        }
        return $taxonomies;
    }

    protected function eszlwcf_registered_taxonomies_terms() {
        return $this->eszlwcf_taxonomies_terms($this->eszlwcf_registered_taxonomies);
    }

    protected function eszlwcf_registered_taxonomies_terms_names() {
        return $this->eszlwcf_taxonomies_terms_names($this->eszlwcf_registered_taxonomies);
    }

    protected function eszlwcf_get_attributes_taxonomies() {
        $attributes_taxonomies = array();
        if (!empty($this->eszlwcf_attributes)) {
            foreach ($this->eszlwcf_attributes as $attribute) {
                $attr = wc_get_attribute($attribute->attribute_id);
                $attributes_taxonomies[$attribute->attribute_id] = $attr->slug;
                $this->eszlwcf_attributes_taxonomies_names[$attr->slug] = $attr->name;
            }
        }
        return $attributes_taxonomies;
    }

    protected function eszlwcf_get_attributes_terms() {
        return $this->eszlwcf_taxonomies_terms($this->eszlwcf_attributes_taxonomies_names);
    }

    protected function eszlwcf_get_attributes_taxonomies_terms_names() {
        return $this->eszlwcf_taxonomies_terms_names($this->eszlwcf_attributes_taxonomies_names);
    }

    protected function eszlwcf_get_filter_product_metas() {
        $metas = array(
            '_price' => esc_html__('Product Price', 'esz-wc-product-filter-widget-elementor'),
            '_stock_status' => esc_html__('Product Status', 'esz-wc-product-filter-widget-elementor'),
            '_wc_average_rating' => esc_html__('Product Rating', 'esz-wc-product-filter-widget-elementor'),
        );
        return apply_filters('eszlwcf_product_filter_metas', $metas);
    }

    protected function eszlwcf_get_filter_product_extra() {
        $extra = array(
            'esz-product-search' => esc_html__('Product Search', 'esz-wc-product-filter-widget-elementor'),
        );
        return apply_filters('eszlwcf_product_filter_extra', $extra);
    }

    public function eszlwcf_get_filter_select_options($default = false) {
        $options = array();
        if (!empty($this->eszlwcf_filter_product_extra)) {
            foreach ($this->eszlwcf_filter_product_extra as $key => $value) {
                $options[$key] = $value;
            }
        }
        if (!empty($this->eszlwcf_filter_product_metas)) {
            foreach ($this->eszlwcf_filter_product_metas as $key => $value) {
                $options[$key] = $value;
            }
        }
        if (!empty($this->eszlwcf_registered_taxonomies)) {
            foreach ($this->eszlwcf_registered_taxonomies as $key => $value) {
                $options[$key] = esc_html($value);
            }
        }
        if (!empty($this->eszlwcf_attributes)) {
            foreach ($this->eszlwcf_attributes as $attribute) {
                $attr = wc_get_attribute($attribute->attribute_id);
                $options[$attr->slug] = esc_html($attr->name);
            }
        }

        if ($default)
            $options = array_values(array_flip($options));
        return $options;
    }

    public function eszlwcf_get_controls_key() {
        $controls = $this->eszlwcf_get_filter_select_options();
        return $controls;
    }

    public function eszlwcf_get_controls_default_filter_type($key = '', $name = '') {
        $options = array('default' => 'checkbox');
        if ($key === '_price'):
            $options['default'] = 'range';
            $options['options'] = array(
                'range' => esc_html__('Range', 'esz-wc-product-filter-widget-elementor'),
            );
        elseif ($key === '_stock_status'):
            $options['default'] = 'select';
            $options['options'] = array(
                'select' => esc_html__('Select', 'esz-wc-product-filter-widget-elementor'),
            );
        elseif ($key === '_wc_average_rating'):
            $options['default'] = 'ratings';
            $options['options'] = array(
                'ratings' => esc_html__('Ratings', 'esz-wc-product-filter-widget-elementor'),
            );
        elseif ($key === 'esz-product-search'):
            $options['default'] = 'search';
            $options['options'] = array(
                'search' => esc_html__('Search', 'esz-wc-product-filter-widget-elementor'),
            );
        else:
            $options['options'] = array(
                'select' => esc_html__('Select', 'esz-wc-product-filter-widget-elementor'),
                'checkbox' => esc_html__('Checkbox', 'esz-wc-product-filter-widget-elementor'),
                'radio' => esc_html__('Radio', 'esz-wc-product-filter-widget-elementor'),
                'image' => esc_html__('Image', 'esz-wc-product-filter-widget-elementor'),
                'color' => esc_html__('Color', 'esz-wc-product-filter-widget-elementor'),
                'button' => esc_html__('Button', 'esz-wc-product-filter-widget-elementor'),
            );
        endif;
        return $options;
    }

    public function eszlwcf_render_filter_block($settings, $widget_id) {
        $field = '';
        $selected_filters = $settings['wc_filter_select_options'];
        if (!empty($selected_filters)) {
            ob_start();
            foreach ($selected_filters as $filter) {
                $key = $filter . '_filter_type_select';
                $field .= '<div class="' . $filter . '-filter-box eszlwcf-filter-block eszlwcf-field-type-' . $settings[$key] . '">';
                $field .= '<div class="eszlwcf-filter-heading-box"><' . $settings['filter_list_heading_tag'] . ' class="eszlwcf-filter-block-heading">' . $settings[$filter . "_filter_label"] . ' </' . $settings['filter_list_heading_tag'] . '></div>';
                $field .= $this->eszlwcf_render_filter_field($filter, $key, $settings, $widget_id);
                $field .= '</div>';
            }
            $filed = ob_get_contents();
            ob_end_clean();
        }
        return $field;
    }

    protected function eszlwcf_render_filter_field($filter, $key, $settings, $widget_id) {
        $field = '';
        $data = array(
            'label' => $settings[$filter . '_filter_label'],
            'widget_id' => trim(str_replace('.', '', $widget_id)),
            'data_key' => $filter,
            'field_type' => $settings[$key],
            'field_name' => $filter,
            'display_label' => $settings[$filter . '_filter_label_toggle'],
            'currency_symbol' => get_woocommerce_currency_symbol(),
        );
        if (array_key_exists($filter, $this->eszlwcf_filter_product_extra)) {
            $data['type'] = 'product_extra';
            $field = $this->eszlwcf_render_extra_filter_field_html($data);
        } elseif (array_key_exists($filter, $this->eszlwcf_filter_product_metas)) {
            $metas = $this->eszwcq->eszlwcf_get_all_product_metas();
            $data['data']['_price']['max'] = max($metas['_price']);
            $data['data']['_stock_status'] = array_unique($metas['_stock_status']);
            $data['data']['_wc_average_rating'] = max($metas['_wc_average_rating']);
            $data['type'] = 'product_metas';
            $field = $this->eszlwcf_render_meta_filter_field_html($data);
        } elseif (array_key_exists($filter, $this->eszlwcf_registered_taxonomies)) {
            $data['data'] = $this->eszlwcf_registered_taxonomies_terms[$filter];
            $data['type'] = 'registered_taxonomies';
            $field = $this->eszlwcf_render_filter_field_html($data);
        } elseif (array_key_exists($filter, $this->eszlwcf_attributes_taxonomies_names)) {
            $data['data'] = $this->eszlwcf_attributes_terms[$filter];
            $data['type'] = 'attribute_taxonomies';
            $field = $this->eszlwcf_render_filter_field_html($data);
        }
        return $field;
    }

    protected function eszlwcf_render_extra_filter_field_html($data) {
        ob_start();
        if (!empty($data)): ?>
            <div class="eszlwcf-filter-filed-wrapper eszlwcf-filter-filed-extras" data-filed-type="<?php echo esc_attr($data['type']) ?>">
                <?php if ($data['field_type'] == 'search'): ?>
                    <div class="eszlwcf-field-box">
                        <input type="<?php echo esc_attr($data['field_type']); ?>" id="<?php echo esc_attr($data['field_type'] . '-'. $data['widget_id']); ?>" name="<?php echo esc_attr($data['field_name']); ?>" value="" data-filed-label="">
                    </div>
                <?php endif; ?>
            </div>
        <?php endif;
        $filed = ob_get_contents();
        ob_end_clean();
        return $filed;
    }

    protected function eszlwcf_render_meta_filter_field_html($data) {
        ob_start();
        if (!empty($data['data'])): ?>
            <div class="eszlwcf-filter-filed-wrapper eszlwcf-filter-filed-range" data-filed-type="<?php echo esc_attr($data['type']) ?>">
                <?php if ($data['field_type'] == 'range') :
                    ?>
                    <div class="eszlwcf-field-box">
                        <span class="eszlwcf-range-value-display" id="<?php echo esc_attr($data['data_key'] . '-' . $data['widget_id']); ?>" style="" data-eszlwcf-range-max="<?php echo esc_attr($data['data']['_price']['max']) ?>" data-eszlwcf-price-symbol="<?php echo esc_attr($data['currency_symbol']) ?>"></span>
                        <input class="eszlwcf-range-value-min" name="eszlwcf-range-value-min" type="hidden" value="0" data-filed-label="">
                        <input class="eszlwcf-range-value-max" name="eszlwcf-range-value-max" type="hidden" value="<?php echo esc_attr($data['data']['_price']['max']) ?>" data-filed-label="">
                        <div class="eszlwcf-price-range"></div>
                    </div>
                <?php elseif ($data['field_type'] == 'select'): ?>
                    <div class="eszlwcf-field-box">
                        <select name="<?php echo esc_attr($data['field_name']) ?>" id="<?php echo esc_attr($data['data_key'] . '-' . $data['widget_id']); ?>">
                            <option value="" data-filed-label=""><?php echo esc_html__('Select....', 'esz-wc-product-filter-widget-elementor') ?></option>
                            <?php foreach ($data['data']['_stock_status'] as $value): ?>
                                <option value="<?php echo esc_attr($value); ?>" data-filed-label="<?php echo esc_attr($value) ?>"><?php echo esc_html($value) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php elseif ($data['field_type'] == 'ratings'): ?>
                    <div class="eszlwcf-field-box">
                        <?php
                        $average_rating = $data['data']['_wc_average_rating']; ?>
                        <?php
                        $star_icons = array(
                            'solid' => array('value' => 'fas fa-star', 'library' => 'fa-solid'),
                            'regular' => array('value' => 'far fa-star', 'library' => 'fa-regular'),
                        );

                        for ($star = 1; $star <= 5; $star++): ?>
                            <input type="radio" id="<?php echo esc_attr($data['field_type'] . '-' . $star . '-' . $data['widget_id']); ?>" name="<?php echo esc_attr($data['field_name']);
                            echo ($data['field_type'] === 'checkbox') ? '[]' : ''; ?>" value="<?php echo esc_attr($star); ?>" data-filed-label="<?php echo sprintf(esc_html__('%u star rating %s', 'esz-wc-product-filter-widget-elementor'), $star, ($star < 5) ? 'or Above' : '') ?>">
                            <?php if ($average_rating !== '' && $star <= $average_rating): ?>

                                <label for="<?php echo esc_attr($data['field_type'] . '-' . $star . '-' . $data['widget_id']); ?>"><?php echo $this->eszlwcf_get_the_icon($star_icons, 'solid'); ?>  </label>
                            <?php else: ?>
                                <label for="<?php echo esc_attr($data['field_type'] . '-' . $star . '-' . $data['widget_id']); ?>"><?php echo $this->eszlwcf_get_the_icon($star_icons, 'regular'); ?></label>
                            <?php endif; ?>
                        <?php endfor;
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif;
        $filed = ob_get_contents();
        ob_end_clean();
        return $filed;
    }

    protected function eszlwcf_render_filter_field_html($data) {
        ob_start();
        if (!empty($data['data'])):
            ?>
            <div class="eszlwcf-filter-filed-wrapper  eszlwcf-filter-<?php echo esc_attr($data['type']) ?>" data-filed-type="<?php echo esc_attr($data['type']) ?>">
                <?php if ($data['field_type'] == 'select') : ?>
                    <div class="eszlwcf-field-box">
                        <select name="<?php echo esc_attr($data['field_name']) ?>" id="<?php echo esc_attr($data['data_key'] . '-' . $data['widget_id']); ?>">
                            <option value="" data-filed-label=""><?php echo esc_html__('Select....', 'esz-wc-product-filter-widget-elementor') ?></option>
                            <?php foreach ($data['data'] as $value): ?>
                                <option value="<?php echo esc_attr($value->slug); ?>" data-filed-label="<?php echo esc_attr($value->name) ?>"><?php echo esc_html($value->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php
                elseif
                ($data['field_type'] === 'radio' || $data['field_type'] === 'checkbox'):
                    foreach ($data['data'] as $value): ?>
                        <div class="eszlwcf-field-box">
                            <input type="<?php echo esc_attr($data['field_type']) ?>" id="<?php echo esc_attr($value->slug . '-' . $data['widget_id']); ?>" name="<?php echo esc_attr($data['field_name']);
                            echo ($data['field_type'] === 'checkbox') ? '[]' : ''; ?>" value="<?php echo esc_attr($value->slug); ?>" data-filed-label="<?php echo esc_attr($value->name) ?>">
                            <label for="<?php echo esc_attr($value->slug . '-' . $data['widget_id']); ?>"><?php echo esc_html($value->name) ?></label>
                        </div>
                    <?php endforeach;
                elseif
                ($data['field_type'] === 'button'):
                    foreach ($data['data'] as $value): ?>
                        <div class="eszlwcf-field-box">
                            <input type="checkbox" id="<?php echo esc_attr($value->slug . '-' . $data['widget_id']); ?>" name="<?php echo esc_attr($data['field_name']);
                            echo ($data['field_type'] === 'checkbox') ? '[]' : ''; ?>" value="<?php echo esc_attr($value->slug); ?>" data-filed-label="<?php echo esc_attr($value->name) ?>">
                            <label for="<?php echo esc_attr($value->slug . '-' . $data['widget_id']); ?>"><?php echo esc_html($value->name) ?></label>
                        </div>
                    <?php endforeach;
                elseif ($data['field_type'] == 'image'): ?>
                    <?php foreach ($data['data'] as $value):
                        $image_id = get_term_meta($value->term_id, 'esz-term-image-id', true);
                        ?>
                        <div class="eszlwcf-field-box <?php echo ($image_id) ? '' : esc_attr('not-image'); ?>">
                            <input type="checkbox" id="<?php echo esc_attr($value->slug . '-' . $data['widget_id']); ?>" name="<?php echo esc_attr($data['field_name']) ?>[]" value="<?php echo esc_attr($value->slug); ?>" data-filed-label="<?php echo esc_attr($value->name) ?>">
                            <label for="<?php echo esc_attr($value->slug . '-' . $data['widget_id']); ?>" title="<?php echo esc_attr($value->name); ?>">
                                <?php if ($image_id) :
                                    echo wp_get_attachment_image($image_id, 'full');
                                    if ($data['display_label'] === 'yes'):
                                        echo esc_attr($value->name);
                                    endif;
                                else:
                                    echo esc_attr($value->name);
                                endif; ?>
                            </label>
                        </div>
                    <?php endforeach;
                elseif ($data['field_type'] == 'color'): ?>
                    <?php foreach ($data['data'] as $value):
                        $style = '';
                        $term_filter_type = get_term_meta($value->term_id, 'esz-term-filter-type', true);
                        $color = get_term_meta($value->term_id, 'esz-term-color', true);
                        $color_2 = get_term_meta($value->term_id, 'esz-term-color-2', true);
                        $style = $color;
                        if ($term_filter_type == 'gradient') {
                            $style = 'linear-gradient(45deg, ' . $color . ', ' . $color_2 . ')';
                        }
                        ?>
                        <div class="eszlwcf-field-box <?php echo ($style) ? '' : esc_attr('not-color') ?>">
                            <input type="checkbox" id="<?php echo esc_attr($value->slug . '-' . $data['widget_id']); ?>" name="<?php echo esc_attr($data['field_name']) ?>[]" value="<?php echo esc_attr($value->slug); ?>" data-filed-label="<?php echo esc_attr($value->name) ?>">
                            <label title="<?php echo esc_attr($value->name); ?>" for="<?php echo esc_attr($value->slug . '-' . $data['widget_id']); ?>">
                                <?php if ($style): ?>
                                    <span style="background: <?php echo esc_attr($style) ?>"></span>
                                <?php endif; ?>
                                <?php if ($data['display_label'] === 'yes'): ?>
                                    <?php echo esc_attr($value->name); ?>
                                <?php endif; ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endif;
        $filed = ob_get_contents();
        ob_end_clean();
        return $filed;
    }

    public static function eszlwcf_get_products_image_url($attachment_id, $key, $settings) {
        return Elementor\Group_Control_Image_Size::get_attachment_image_src($attachment_id, $key, $settings);
    }

    public function eszlpf_check_have_more_posts($the_query) {
        $args = $the_query->query;
        $args['paged'] = $the_query->query_vars['paged'] + 2;
        query_posts($args);
        if (have_posts() && $the_query->query['posts_per_page'] !== -1) {
            return true;
        }
        wp_reset_query();
        return false;
    }

    public function eszlwcf_get_the_icon($settings, $key) {
        $migrated = isset($settings['__fa4_migrated'][$key]);
        $is_new = empty($settings['icon']) && Icons_Manager::is_migration_allowed();
        if ($is_new || $migrated) :
            Icons_Manager::render_icon($settings[$key], ['aria-hidden' => 'true']);
        endif;
    }
}

