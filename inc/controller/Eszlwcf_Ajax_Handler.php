<?php

class Eszlwcf_Ajax_Handler {
    private $eszwcq;
    private $eszwcf;

    public function __construct() {
        $this->eszwcq = new \Eszlwcf_Product_Filter_Query_Controller();
        $this->eszlwcf = new \Eszlwcf_Custom_Function();
    }

    protected function eszlwcf_filter_product_args_ajax($data) {
        $args = json_decode(stripslashes($data['query']), true);
        
        parse_str($data['args']['filterFormArgs'], $form_args);
        parse_str($data['args']['sortingFormArgs'], $sorting_args);
        if ($data['action'] === 'eszlwcf_load_more_products') {
            $args['paged'] = ($_POST['page']) ? intval($_POST['page']) + 1 : 1;
        } else {
            $args['paged'] = $data['page'];
        }
        $args['post_status'] = 'publish';
        
        // Inicjalizuj tablice tax_query i meta_query
        $tax_query = [];
        $meta_query = [];
        
        if (!empty($form_args)) :
            $range_handler = array('_price', 'eszlwcf-range-value-min', 'eszlwcf-range-value-max');
            $stock_status_handler = array('_stock_status');
            $product_rating_handler = array('_wc_average_rating');
            $product_search_handler = array('esz-product-search');
            
            // Grupuj kategorie produktów według taksonomii
            $taxonomy_terms = [];
            
            foreach ($form_args as $key => $arg) :
                if ($arg === '') continue;
                
                if (in_array($key, $range_handler)):
                    if ($key === 'eszlwcf-range-value-min'):
                        $meta_query[] = array(
                            'key' => '_price',
                            'value' => $arg, // From price value
                            'compare' => '>=',
                            'type' => 'NUMERIC'
                        );
                    else:
                        $meta_query[] = array(
                            'key' => '_price',
                            'value' => $arg, // From price value
                            'compare' => '<=',
                            'type' => 'NUMERIC'
                        );
                    endif;
                elseif (in_array($key, $stock_status_handler)):
                    $meta_query[] = array(
                        'key' => '_stock_status',
                        'value' => $arg, // From price value
                    );
                elseif (in_array($key, $product_rating_handler)):
                    $meta_query[] = array(
                        'key' => '_wc_average_rating',
                        'value' => 5, // From price value
                        'compare' => '<=',
                        'type' => 'NUMERIC'
                    );
                    $meta_query[] = array(
                        'key' => '_wc_average_rating',
                        'value' => $arg, // From price value
                        'compare' => '>=',
                        'type' => 'NUMERIC'
                    );
                elseif (in_array($key, $product_search_handler)):
                    $args['s'] = $arg;
                else:
                    // Zbierz wszystkie wartości dla danej taksonomii
                    if (!isset($taxonomy_terms[$key])) {
                        $taxonomy_terms[$key] = [];
                    }
                    
                    // Jeśli $arg jest tablicą, dodaj wszystkie wartości
                    if (is_array($arg)) {
                        $taxonomy_terms[$key] = array_merge($taxonomy_terms[$key], $arg);
                    } else {
                        $taxonomy_terms[$key][] = $arg;
                    }
                endif;
            endforeach;
            
            // Dodaj zapytania taksonomiczne
            foreach ($taxonomy_terms as $taxonomy => $terms) {
                $tax_query[] = [
                    'taxonomy' => $taxonomy,
                    'field' => 'slug',
                    'terms' => $terms,
                    'operator' => 'AND', // To zapewnia, że produkt ma WSZYSTKIE wybrane kategorie
                ];
            }
        endif;
        
        // Jeśli mamy zapytania taksonomiczne, dodaj je do argumentów z relacją AND
        if (!empty($tax_query)) {
            $args['tax_query'] = array_merge(
                ['relation' => 'AND'],
                $tax_query
            );
        }
        
        // Jeśli mamy zapytania meta, dodaj je do argumentów z relacją AND
        if (!empty($meta_query)) {
            $args['meta_query'] = array_merge(
                ['relation' => 'AND'],
                $meta_query
            );
        }
        
        // Sortowanie
        if (!empty($sorting_args)) {
            $order_key = array_shift($sorting_args);
            switch ($order_key) {
                case "by-price-low-to-high":
                    $args['orderby'] = 'meta_value_num';
                    $args['meta_key'] = '_price';
                    $args['order'] = 'asc';
                    break;
                case "by-price-high-to-low":
                    $args['orderby'] = 'meta_value_num';
                    $args['meta_key'] = '_price';
                    $args['order'] = 'desc';
                    break;
                case "by-name-a-z":
                    $args['orderby'] = 'title';
                    $args['order'] = 'asc';
                    break;
                case "by-name-z-a":
                    $args['orderby'] = 'title';
                    $args['order'] = 'desc';
                    break;
                case "by-newest":
                    $args['orderby'] = 'date';
                    $args['order'] = 'desc';
                    break;
                case "by-oldest":
                    $args['orderby'] = 'date';
                    $args['order'] = 'asc';
                    break;
            }
        }
        
        return $args;
    }

    protected function eszlwcf_filter_product_result_ajax($args, $data) {
        $result = array();
        $result = ['eszlwcfMoreData' => '', 'result' => '', 'eszlpfPageCount' => ''];
        $result['selectedFilter'] = $data['args']['filterFormArray'];
        $settings = json_decode(stripslashes($data['settings']), true);
        query_posts($args);
        ob_start();
        if (have_posts()) :
            // run the loop
            while (have_posts()): the_post();
                include plugin_dir_path(__DIR__) . '../templates/product/product.php';
            endwhile;
        endif;
        wp_reset_postdata();
        wp_reset_query();
        $result['result'] = ob_get_contents();
        ob_end_clean();
        return $result;
    }

    protected function eszlwcf_filter_product_more_data_ajax($result, $args, $data) {
        $result['translatedStringClearAll'] = esc_html__('Clear All', 'esz-wc-product-filter-widget-elementor');
        $settings = json_decode(stripslashes($data['settings']), true);
        ob_start(); ?>
        <div class="eszlwcf-not-found-section">
            <p><?php echo esc_html($settings['not_found_message']); ?></p>
            <span class="eszlwcf-clear-all eszlwcf-clear"><?php echo esc_html__('Clear All', 'esz-wc-product-filter-widget-elementor') ?> <i>×</i></span>
        </div>
        <?php $not_found = ob_get_contents();
        ob_end_clean();
        if ($result['result'] === '') {
            $result['result'] = $not_found;
        }
        if ($data['action'] === 'eszlwcf_load_more_products') {
            $args['paged'] = ($_POST['page']) ? intval($_POST['page']) + 2 : 1;
        } else {
            $args['paged'] = ($_POST['page']) ? intval($_POST['page']) + 1 : 1;
        }
        $result['eszlwcfPageCount'] = $args['paged'] - 1;
        query_posts($args);
        if (have_posts() && $args['posts_per_page'] !== -1) :
            $result['eszlwcfMoreData'] = '1';
        else:
            $result['eszlwcfMoreData'] = '0';
            if ($data['action'] === 'eszlwcf_filter_products') {
                $result['eszlwcfPageCount'] = 1;
            }
        endif;
        wp_reset_query();
        return $result;
    }

    public function eszlwcf_filter_products() {
        $args = $this->eszlwcf_filter_product_args_ajax($_POST);
        $result = $this->eszlwcf_filter_product_result_ajax($args, $_POST);
        $result = $this->eszlwcf_filter_product_more_data_ajax($result, $args, $_POST);
        echo json_encode($result);
        die(); // here we exit the script and even no wp_reset_query() required!
    }
}

$Eszah = new Eszlwcf_Ajax_Handler();
add_action('wp_ajax_eszlwcf_filter_products', [$Eszah, 'eszlwcf_filter_products']);
add_action('wp_ajax_nopriv_eszlwcf_filter_products', [$Eszah, 'eszlwcf_filter_products']);
add_action('wp_ajax_eszlwcf_load_more_products', [$Eszah, 'eszlwcf_filter_products']);
add_action('wp_ajax_nopriv_eszlwcf_load_more_products', [$Eszah, 'eszlwcf_filter_products']);