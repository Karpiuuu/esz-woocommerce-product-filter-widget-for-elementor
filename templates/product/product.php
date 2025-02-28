<?php
$product = wc_get_product(get_the_ID());
$link_target = ($settings['open_in_new_window'] === 'yes') ? esc_attr('_blank') : '';
$icons = array(
    'quick-view' => array('value' => 'fas fa-search-plus', 'library' => 'fa-regular'),
    'arrow-left' => array('value' => 'fas fa-chevron-left', 'library' => 'fa-solid'),
    'arrow-right' => array('value' => 'fas fa-chevron-right', 'library' => 'fa-solid'),
);
if ($settings['products_layout_skin'] === 'default'):
    wc_get_template_part('content', 'product');
else: ?>
    <div class="eszlwcf-product esz-product-<?php the_ID(); ?>">
        <?php if (get_the_post_thumbnail_url()) : ?>
            <div class="eszlwcf-product-thumb">
                <?php if ($product->is_on_sale()) : ?>
                    <div class="eszlwcf-product-badge">
                        <span class="eszlwcf-product-badge-sale"><?php echo esc_html__('Sale', 'esz-wc-product-filter-widget-elementor') ?></span>
                    </div>
                <?php endif; ?>
                <?php $front_thumb = Eszlwcf_Custom_Function::eszlwcf_get_products_image_url(get_post_thumbnail_id(), 'product_thumbnail_size', $settings) ?>
                <img class="eszlwcf-product-thumbnail-front eszlwcf-product-thumbnail" src="<?php echo esc_url($front_thumb); ?>" alt="<?php echo esc_attr(get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true)); ?>">
                <?php $image_id = $product->get_gallery_image_ids();
                if (!empty($image_id)) :
                    $image_id = array_shift($image_id);
                    $back_thumb = Eszlwcf_Custom_Function::eszlwcf_get_products_image_url($image_id, 'product_thumbnail_size', $settings); ?>
                    <img class="eszlwcf-product-thumbnail-back eszlwcf-product-thumbnail" src="<?php echo esc_url($back_thumb); ?>" alt="<?php echo esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', true)); ?>">
                <?php endif; ?>
                <span class="eszwcf-quick-view">
                    <?php echo $this->eszlwcf->eszlwcf_get_the_icon($icons, 'quick-view'); ?>
                </span>
            </div>
        <?php endif;
        $title_class = esc_attr('eszlwcf-product-title');
        if ($settings['link_attach_with_title'] === 'yes' && $settings['open_in_new_window'] === 'yes'):
            $target = esc_attr('target="_blank"');
            $title = sprintf('<a href="%1$s" %3$s>%2$s</a>', esc_url(get_the_permalink()), esc_html(get_the_title()), $target);
        elseif ($settings['link_attach_with_title'] === 'yes'):
            $title = sprintf('<a href="%1$s">%2$s</a>', esc_url(get_the_permalink()), esc_html(get_the_title()));
        else:
            $title = esc_html(get_the_title());
        endif;
        $title_html = sprintf('<%1$s class="%2$s"> %3$s </%1$s>', Eszlwcf_Custom_Function::validate_html_tag($settings['product_title_tag']), $title_class, $title);
        // PHPCS - the variable $title_html holds safe data.
        echo $title_html;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        ?>
        <div class="eszlwcf-product-meta">
            <div class="eszlwcf-product-price">
                <?php $class = 'regular';
                if ($product->get_sale_price()) {
                    $class = 'regular sale-price';
                } ?>
                <span class="eszlwcf-product-price-<?php echo esc_attr($class) ?>"> <?php echo wp_kses_post($product->get_price_html()); ?> </span>
            </div>

        </div>
        <?php if ($settings['display_product_button']): ?>
            <div class="eszlwcf-product-button">
                <?php $ico_key = ($product->get_type() == 'simple') ? 'filter_bar_icon_cart' : 'filter_bar_icon_option'; ?>
                <a href="<?php echo esc_url($product->add_to_cart_url()) ?>"
                   target="<?php echo esc_attr($link_target) ?>"
                   rel="nofollow"
                   data-product_id="<?php echo esc_attr($product->get_id()); ?>"
                   data-product_sku="<?php echo esc_attr($product->get_sku()); ?>"
                   class="button <?php echo ($product->is_purchasable()) ? esc_attr('add_to_cart_button') : '' ?> product_type_<?php echo esc_attr($product->get_type()); ?>">
                    <?php echo esc_html($product->add_to_cart_text()) . ' ';
                    $this->eszlwcf->eszlwcf_get_the_icon($settings, $ico_key) ?>
                </a>
            </div>
        <?php endif; ?>
        <div class="product-info">
            <?php // test if product is variable
            if ($product->is_type('variable')) {
                $available_variations = $product->get_available_variations();
                foreach ($available_variations as $key => $value) {
                    $attributes = $value['attributes'];
                    $image_id = $value['image_id'];

                }
            }
            ?>
        </div>
        <?php include plugin_dir_path(__DIR__) . '../templates/product/product-modal.php'; ?>
    </div>
<?php endif;
