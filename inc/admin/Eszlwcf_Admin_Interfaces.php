<?php

class Eszlwcf_Admin_Interfaces {
    private $esz_custom_data;
    private $esz_term_filter_type_select;

    public function __construct() {
        $this->esz_custom_data = new Eszlwcf_Custom_Function();
        $this->eszlwcf_add_attributes_field();
    }

    protected function eszlwcf_add_attributes_field() {
        $this->esz_term_filter_type_select = array(
            'color' => esc_html__('Color', 'esz-wc-product-filter-widget-elementor'),
            'gradient' => esc_html__('Gradient', 'esz-wc-product-filter-widget-elementor'),
            'image' => esc_html__('Image', 'esz-wc-product-filter-widget-elementor')
        );
        $taxonomies = array();
        $taxonomies_1 = $this->esz_custom_data->eszlwcf_filters_custom_data()['eszlwcf_attributes_taxonomies_names'];
        $taxonomies_2 = $this->esz_custom_data->eszlwcf_filters_custom_data()['eszlwcf_registered_taxonomies'];
        if (isset($taxonomies_2['product_cat'])) {
            unset($taxonomies_2['product_cat']);
        }
        if (!empty($taxonomies_1) && !empty($taxonomies_2)) {
            $taxonomies = array_merge($taxonomies_1, $taxonomies_2);
        }
        if (!empty($taxonomies)) :
            foreach ($taxonomies as $taxonomy => $label):
                add_action($taxonomy . '_add_form_fields', [$this, 'esz_add_term_fields']);
                add_action($taxonomy . '_edit_form_fields', [$this, 'esz_edit_term_fields'], 10, 2);
                add_action('created_' . $taxonomy, [$this, 'esz_save_term_fields']);
                add_action('edited_' . $taxonomy, [$this, 'esz_save_term_fields']);
            endforeach;
        endif;
    }

    public function esz_add_term_fields($taxonomy) { ?>
        <div class="form-field">
            <label for="esz-term-filter-type"><?php echo esc_html__('Filter Type', 'esz-wc-product-filter-widget-elementor'); ?></label>
            <select name="esz-term-filter-type" id="esz-term-filter-type">
                <?php foreach ($this->esz_term_filter_type_select as $value => $label): ?>
                    <option value="<?php echo esc_attr($value) ?>"><?php echo esc_html($label); ?></option>
                <?php endforeach; ?>
            </select>
            <p><?php esc_attr_e('Select Attribute Filter Type', ''); ?></p>
        </div>
        <div class="form-field esz-term-color-field">
            <label for="esz-term-color"><?php echo esc_html__('Term Color', ''); ?></label>
            <input type="color" name="esz-term-color" id="esz-term-color" class="wpColorChoose"/>
            <p><?php esc_attr_e('Select Attribute Color', ''); ?></p>
        </div>
        <div class="form-field esz-term-color-field-2">
            <label for="esz-term-color-2"><?php echo esc_html__('Term Color', 'esz-wc-product-filter-widget-elementor'); ?></label>
            <input type="color" name="esz-term-color-2" id="esz-term-color-2" class="wpColorChoose"/>
            <p><?php echo esc_html__('Select Attribute Color 2', 'esz-wc-product-filter-widget-elementor'); ?></p>
        </div>
        <div class="form-field term-group esz-term-image-field">
            <label for="esz-term-image-id"><?php echo esc_html__('Term Image', 'esz-wc-product-filter-widget-elementor'); ?></label>
            <input type="hidden" id="esz-term-image-id" name="esz-term-image-id" class="custom_media_url" value="">
            <div id="esz-term-image-wrapper"></div>
            <p>
                <input type="button" class="button button-secondary esz-tax-media-button" id="esz-tax-media-button" name="esz-tax-media-button" value="<?php echo esc_html__('Add Image', 'esz-wc-product-filter-widget-elementor'); ?>"/>
                <input type="button" class="button button-secondary esz-tax-media-remove" id="esz-tax-media-remove" name="esz-tax-media-remove" value="<?php echo esc_html__('Remove Image', 'esz-wc-product-filter-widget-elementor'); ?>"/>
            </p>
        </div>
        <?php
    }

    public function esz_edit_term_fields($term, $taxonomy) {
        $esz_term_filter_type = get_term_meta($term->term_id, 'esz-term-filter-type', true);
        $esz_term_color = get_term_meta($term->term_id, 'esz-term-color', true);
        $esz_term_color_2 = get_term_meta($term->term_id, 'esz-term-color-2', true);
        $esz_term_image_id = get_term_meta($term->term_id, 'esz-term-image-id', true); ?>
        <tr class="form-field">
            <th>
                <label for="esz-term-filter-type"><?php echo esc_html__('Term Filter Type', 'esz-wc-product-filter-widget-elementor'); ?></label>
            </th>
            <td>
                <select name="esz-term-filter-type" id="esz-term-filter-type">
                    <?php foreach ($this->esz_term_filter_type_select as $value => $label): ?>
                        <?php // Below Label is translated where it assigned ?>
                        <option value="<?php echo esc_attr($value) ?>" <?php echo ($value === $esz_term_filter_type) ? esc_attr('selected') : '' ?>><?php echo esc_html($label); ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr class="form-field esz-term-color-field">
            <th>
                <label for="esz-term-color"><?php esc_attr_e('Term  Color', ''); ?></label>
            </th>
            <td>
                <input name="esz-term-color" id="esz-term-color" type="color" class="wpColorChoose" value="<?php echo esc_attr($esz_term_color) ?>"/>
            </td>
        </tr>
        <tr class="form-field esz-term-color-field-2">
            <th>
                <label for="esz-term-color-2"><?php esc_attr_e('Term  Color 2', ''); ?></label>
            </th>
            <td>
                <input name="esz-term-color-2" id="esz-term-color-2" type="color" class="wpColorChoose" value="<?php echo esc_attr($esz_term_color_2) ?>"/>
            </td>
        </tr>
        <tr class="form-field term-group-wrap esz-term-image-field">
            <th scope="row">
                <label for="esz-term-image-id"><?php _e('Term Attribute Image', 'esz-wc-product-filter-widget-elementor'); ?></label>
            </th>
            <td>
                <input type="hidden" id="esz-term-image-id" name="esz-term-image-id" value="<?php echo esc_attr($esz_term_image_id); ?>">
                <div id="esz-term-image-wrapper">
                    <?php if ($esz_term_image_id) { ?>
                        <?php echo wp_get_attachment_image($esz_term_image_id, 'full'); ?>
                    <?php } ?>
                </div>
                <p>
                    <input type="button" class="button button-secondary esz-tax-media-button" id="esz-tax-media-button" name="esz-tax-media-button" value="<?php echo esc_attr__('Add Image', 'esz-wc-product-filter-widget-elementor'); ?>"/>
                    <input type="button" class="button button-secondary esz-tax-media-remove" id="esz-tax-media-remove" name="esz-tax-media-remove" value="<?php echo esc_attr__('Remove Image', 'esz-wc-product-filter-widget-elementor'); ?>"/>
                </p>
            </td>
        </tr>
        <?php
    }

    public function esz_save_term_fields($term_id) {
        if (isset($_POST['esz-term-filter-type']) && '' !== $_POST['esz-term-filter-type']) {
            update_term_meta($term_id, 'esz-term-filter-type', sanitize_text_field($_POST['esz-term-filter-type']));
        }
        if (isset($_POST['esz-term-color']) && '' !== $_POST['esz-term-color']) {
            update_term_meta($term_id, 'esz-term-color', sanitize_text_field($_POST['esz-term-color']));
        }
        if (isset($_POST['esz-term-color-2']) && '' !== $_POST['esz-term-color-2']) {
            update_term_meta($term_id, 'esz-term-color-2', sanitize_text_field($_POST['esz-term-color-2']));
        }
        if (isset($_POST['esz-term-image-id']) && '' !== $_POST['esz-term-image-id']) {
            $image = $_POST['esz-term-image-id'];
            update_term_meta($term_id, 'esz-term-image-id', $image);
        } else {
            update_term_meta($term_id, 'esz-term-image-id', '');
        }
    }
}

$eszlwcf_admin_interfaces = new Eszlwcf_Admin_Interfaces();