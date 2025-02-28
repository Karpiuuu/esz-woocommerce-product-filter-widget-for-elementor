<div class="eszlwcf-section">
    <div class="esz-product-modal-frame">
        <div class="esz-product-modal-container">
            <span class="esz-modal-close"><?php echo esc_html('🞪') ?></span>
            <div class="esz-product-modal-inner-wrapper">
            </div>
        </div>
    </div>
    <div class="eszlwcf-filters-open-button-container">
        <a class="eszlwcf-filters-open-widget" href="javascript:void(0);">
            <div class="eszlwcf-button-inner">
                <div class="eszlwcf-icon-html-wrapper">
                    <span class="eszlwcf-icon-line-1"></span>
                    <span class="eszlwcf-icon-line-2"></span>
                    <span class="eszlwcf-icon-line-3"></span>
                </div>
                <span class="eszlwcf-filters-button-text"><?php echo esc_html($settings['mobile_filter_button_text']) ?></span>
            </div>
        </a>
    </div>
    <div class="eszlwcf-setting-data">
        <textarea class="eszlwcf-product-query">  <?php echo wp_json_encode($this->eszwcq->eszlpf_get_product_query()->query_vars); ?>  </textarea>
        <textarea class="eszlwcf-widget-settings"> <?php echo wp_json_encode($settings); ?> </textarea>
    </div>
    <div class="eszlwcf-filter-frame">
        <div class="eszlwcf-widget-close-container">
            <a class="eszlwcf-widget-close-icon" href="javascript:void(0)">
                <?php // Below String is not for translate because its used as Symbol ?>
                <i><?php echo esc_html('×') ?></i>
            </a>
            <span class="wpc-widget-popup-title"><?php echo esc_html($settings['mobile_filter_button_text']) ?></span>
        </div>
        <form class="eszlwcf-filter-form">
            <?php echo $this->eszlwcf->eszlwcf_render_filter_block($settings, $widget_id); ?>
        </form>
    </div>
    <div class="eszlwcf-products-frame">
        <div class="eszlwcf-extra-filter-frame">
            <div class="eszlwcf-filter-clear-block">
                <span class="eszlwcf-clear eszlwcf-clear-editor"
                      title="<?php echo esc_attr__('It will not display in live', 'esz-wc-product-filter-widget-elementor') ?>"><?php echo esc_html__('Preview for applied filters', 'esz-wc-product-filter-widget-elementor') ?><i>×</i></span>
                <div class="eszlwcf-filter-clear-options">
                </div>
            </div>
            <?php if ($settings['display_products_sorting'] === 'yes') : ?>
                <div class="eszlwcf-sorting-block">
                    <select class="eszlwcf-sorting-select" name="eszlwcf-product-sorting">
                        <option value=""
                                disabled><?php echo esc_html__('Sorting...', 'esz-wc-product-filter-widget-elementor') ?></option>
                        <option value="<?php echo esc_attr('by-name-a-z') ?>"><?php echo esc_html__('By Name A-Z', 'esz-wc-product-filter-widget-elementor'); ?></option>
                        <option value="<?php echo esc_attr('by-name-z-a') ?>"><?php echo esc_html__('By Name Z-A', 'esz-wc-product-filter-widget-elementor') ?></option>
                        <option value="<?php echo esc_attr('by-price-low-to-high') ?>"><?php echo esc_html__('By Price Low to High', 'esz-wc-product-filter-widget-elementor') ?></option>
                        <option value="<?php echo esc_attr('by-price-high-to-low') ?>"><?php echo esc_html__('By Price High to Low', 'esz-wc-product-filter-widget-elementor') ?></option>
                        <option value="<?php echo esc_attr('by-oldest') ?>"><?php echo esc_html__('Oldest', 'esz-wc-product-filter-widget-elementor') ?></option>
                        <option value="<?php echo esc_attr('by-newest') ?>"><?php echo esc_html__('Newest', 'esz-wc-product-filter-widget-elementor') ?></option>
                    </select>
                </div>
            <?php endif; ?>
        </div>
        <div class="eszlwcf-products-section">
            <div class="eszlwcf-loader">
                <?php
                $eszlpf_loader_url = esc_url(plugin_dir_url(__DIR__) . 'assets/img/loader.svg');
                if (!empty($settings['loader_image']['url'])):
                    $eszlpf_loader_url = esc_url($settings['loader_image']['url']);
                endif; ?>
                <img src="<?php echo esc_url($eszlpf_loader_url); ?>"
                     alt="<?php esc_attr__('Loader', 'esz-wc-product-filter-widget-elementor') ?>">
            </div>
            <?php if ($esz_the_query->have_posts()): ?>
                <div class="eszlwcf-products-block">
                    <?php while ($esz_the_query->have_posts()): $esz_the_query->the_post(); ?>
                        <?php include plugin_dir_path(__DIR__) . 'templates/product/product.php'; ?>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="eszlwcf-load-more">
            <a class="<?php echo ($this->eszlwcf->eszlpf_check_have_more_posts($esz_the_query)) ? '' : esc_attr('eszlwcf-load-button-hide') ?>"
               href="javascript:void(0)"
               data-eszlwcf-page-count="<?php echo esc_attr(1); ?>"><?php echo esc_html($settings['load_more_button_text']); ?></a>
        </div>
        <?php wp_reset_postdata();
        wp_reset_query(); ?>
    </div>
</div>