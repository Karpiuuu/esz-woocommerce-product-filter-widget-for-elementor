<div class="esz-product-modal" style="order:6">
    <div class="esz-product-modal-left esz-product-modal-column">
        <div class="esz-product-modal-thumb-gallery">
            <?php
            $attachment_ids = array();
            if (!empty($product->get_gallery_image_ids()))
                $attachment_ids = $product->get_gallery_image_ids();
            if (get_post_thumbnail_id())
                $attachment_ids = array_merge(array(get_post_thumbnail_id()), $attachment_ids);
            if (!empty($attachment_ids) && count($attachment_ids) > 1): ?>
                <div class="esz-product-modal-image-slider">
                    <?php foreach ($attachment_ids as $attachment_id) {
                        $thumb = Eszlwcf_Custom_Function::eszlwcf_get_products_image_url($attachment_id, 'product_quick_view_modal_thumbnail_size', $settings);
                        ?>
                        <div class="esz-image-slide">
                            <img src="<?php echo $thumb; ?>" alt="<?php echo esc_attr(get_post_meta($attachment_id, '_wp_attachment_image_alt', true)); ?>">
                        </div>
                    <?php } ?>
                </div>
                <div class="esz-product-modal-thumb-slider">
                    <?php foreach ($attachment_ids as $attachment_id) {
                        $thumb = wp_get_attachment_image_url($attachment_id); ?>
                        <div class="esz-thumb-slide">
                            <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_post_meta($attachment_id, '_wp_attachment_image_alt', true)); ?>">
                        </div>
                    <?php } ?>
                </div>
            <?php elseif (!empty($attachment_ids)) : ?>
                <div class="esz-product-modal-thumbnail">
                    <?php
                    $thumb = Eszlwcf_Custom_Function::eszlwcf_get_products_image_url(array_shift($attachment_ids), 'product_quick_view_modal_thumbnail_size', $settings); ?>
                    <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_post_meta(array_shift($attachment_ids), '_wp_attachment_image_alt', true)); ?>">
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="esz-product-modal-right esz-product-modal-column">
        <?php
        $title_class = esc_attr('esz-modal-product-title');
        $title = esc_html(get_the_title());
        $title_html = sprintf('<%1$s class="%2$s"> %3$s </%1$s>', Eszlwcf_Custom_Function::validate_html_tag($settings['quick_view_product_title_tag']), $title_class, $title);
        // PHPCS - the variable $title_html holds safe data.
        echo $title_html;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        ?>
        <div class="esz-modal-product-price">
            <?php $class = 'regular';
            if ($product->get_sale_price()) {
                $class = 'regular sale-price';
            } ?>
            <span class="esz-modal-product-price-<?php echo esc_attr($class) ?>"> <?php echo wp_kses_post($product->get_price_html()); ?> </span>
        </div>
        <div class="esz-modal-product-desc">
            <?php echo esc_html($product->get_short_description()) ?>
        </div>
        <div class="esz-modal-product-cart-button <?php echo esc_attr($product->get_type()); ?>">
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

        <div class="esz-modal-product-meta">
            <?php if (wc_product_sku_enabled() && ($product->get_sku() || $product->is_type('variable'))) : ?>
                <div class="product-sku">
                    <span class="sku_wrapper"><label><?php esc_html_e('SKU:', 'woocommerce'); ?></label>
                        <span class="sku"><?php echo ($sku = $product->get_sku()) ? $sku : esc_html__('N/A', 'woocommerce'); ?></span>
                    </span>
                </div>
            <?php endif; ?>
            <?php echo wc_get_product_category_list($product->get_id(), ', ', '<div class="esz-product-category"><span class="posted_in">' . _n('<label>Category:</label>', 'Categories:', count($product->get_category_ids()), 'woocommerce') . ' ', '</span></div>'); ?>
            <?php echo wc_get_product_tag_list($product->get_id(), ', ', '<div class="esz-product-tags"><span class="tagged_as">' . _n('<label>Tag:</label>', 'Tags:', count($product->get_tag_ids()), 'woocommerce') . ' ', '</span></div>'); ?>
        </div>
    </div>
</div>