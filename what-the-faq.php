<?php 
/*
Plugin Name:    What the FAQ
Plugin URI:     https://wordpress.org/plugins/what-the-faq/
Description:    Create and configure Frequently Asked Questions.
Version:        1.0.0
Author: 		Rocket Apps
Author URI: 	https://rocketapps.com.au
Text Domain: 	what-the-faq
License:        GPL2
Author Email:   support@rocketapps.com.au
Domain Path:    /languages/
*/

/* Look for translation file. */
function what_the_faq_textdomain() {
    load_plugin_textdomain( 'what-the-faq', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'what_the_faq_textdomain' );

/* Register settings */
function what_the_faq_settings_init(){
    register_setting( 'what_the_faq_settings', 'what_the_faq_settings' );
}
/* Add settings page to menu */
function add_what_the_faq_settings_page() {
    add_options_page( 'What the FAQ', 'What the FAQ', 'manage_options', 'what_the_faq_settings', 'what_the_faq_settings_page' );
}
add_action( 'admin_init', 'what_the_faq_settings_init' );
add_action( 'admin_menu', 'add_what_the_faq_settings_page' );


/* Enqueue admin CSS */
function what_the_faq_admin_css() {
    $plugin_data = get_plugin_data( __FILE__ );
    wp_register_style( 'what_the_faq_admin_css', esc_url(plugins_url('css/', __FILE__ ) . 'wtf-admin.css'), false, $plugin_data['Version'] );
    wp_enqueue_style( 'what_the_faq_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'what_the_faq_admin_css' );


/* Enqueue bundled WordPress jQuery. */
function what_the_faq_load_scripts(){
    wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'what_the_faq_load_scripts');


/* Set up custom post type for faqs */
function create_what_the_faq_post_type() {

    $options                = get_option( 'what_the_faq_settings' );
    $allow_featured_image   = isset($options['allow_featured_image']) ? $options['allow_featured_image'] : '';
    $exclude_from_search    = isset($options['exclude_from_search']) ? $options['exclude_from_search'] : ''; 

    if($allow_featured_image) {
        $allow_featured_image = 'thumbnail';
    } else {
        $allow_featured_image = '';
    }

    if($exclude_from_search) {
        $exclude_from_search = true;
    } else {
        $exclude_from_search = false;
    }

    register_post_type( 'wtf_faq',

    array(
        'labels' => array(
            'singular_name'     => __( 'What the FAQ', 'what-the-faq'),
            'name' 				=> __( 'What the FAQ', 'what-the-faq'),
            'add_new'           => __( 'Add FAQ', 'what-the-faq'),
            'add_new_item'      => __( 'Add FAQ', 'what-the-faq'),
            'edit_item'         => __( 'Edit FAQ', 'what-the-faq'),
            'new_item'          => __( 'New FAQ', 'what-the-faq'),
            'search_items'      => __( 'Search FAQs', 'what-the-faq'),
            'not_found'  		=> __( 'No FAQs Found', 'what-the-faq'),
            'not_found_in_trash'=> __( 'No FAQs Found in Trash', 'what-the-faq'),
            'all_items'     	=> __( 'All FAQs','what-the-faq')
        ),
        'public'			 	=> false,
        'has_archive' 			=> false,
        'rewrite'				=> array('slug' => 'faq'),
        'publicly_queryable'  	=> false,
        'hierarchical'        	=> false,
        'show_ui' 				=> true,
        'show_in_menu'          => true,
        'exclude_from_search'	=> $exclude_from_search,
        'query_var'				=> true,
        'menu_position'			=> 70,
        'can_export'          	=> true,
        'menu_icon'         	=> plugins_url('images/', __FILE__ ) . 'admin-icon.svg',
        'supports'  			=> array('title', 'revisions', 'author', 'editor', $allow_featured_image),
        'capability_type'       => 'post',
        'taxonomies'            => array('wtf_category'),
        'map_meta_cap'          => true,
        )
    );
}
add_action( 'init', 'create_what_the_faq_post_type' );


/* Include settings page UI */
require_once('inc/settings-ui.php');


/* Output on selected FAQ page */
function what_the_faq_content_filter($content) {

    global $post;
    $options                    = get_option( 'what_the_faq_settings' );
    $initial_state              = isset($options['initial_state']) ? $options['initial_state'] : '';
    $faq_page                   = isset($options['faq_page']) ? $options['faq_page'] : '';
    $animation                  = isset($options['animation']) ? $options['animation'] : '';
    $disable_css                = isset($options['disable_css']) ? $options['disable_css'] : '';
    $disable_js                 = isset($options['disable_js']) ? $options['disable_js'] : '';
    $border_colour              = isset($options['border_colour']) ? $options['border_colour'] : '';
    $border_radius              = isset($options['border_radius']) ? $options['border_radius'] : '';
    $border_style               = isset($options['border_style']) ? $options['border_style'] : '';
    $padding                    = isset($options['padding']) ? $options['padding'] : '';
    $icon_stroke                = isset($options['icon_stroke']) ? $options['icon_stroke'] : '';
    $bg_colour                  = isset($options['bg_colour']) ? $options['bg_colour'] : '';
    $title_colour               = isset($options['title_colour']) ? $options['title_colour'] : '';
    $icon_colour                = isset($options['icon_colour']) ? $options['icon_colour'] : '';
    $arrow_style                = isset($options['arrow_style']) ? $options['arrow_style'] : '';
    $allow_featured_image       = isset($options['allow_featured_image']) ? $options['allow_featured_image'] : '';
    $faq_date                   = isset($options['faq_date']) ? $options['faq_date'] : '';
    $featured_image_position    = isset($options['featured_image_position']) ? $options['featured_image_position'] : '';
    $allow_faq_pages            = isset($options['allow_faq_pages']) ? $options['allow_faq_pages'] : '';
    $use_faq_categories         = isset($options['use_faq_categories']) ? $options['use_faq_categories'] : '';            
    $show_faq_link              = isset($options['show_faq_link']) ? $options['show_faq_link'] : '';
    $exclude_from_search        = isset($options['exclude_from_search']) ? $options['exclude_from_search'] : '';
    $post_slug                  = $post->post_name;

    if($padding) {
        $padding = $padding;
    } else {
        $padding = '15';
    }

    if($featured_image_position) {
        $featured_image_position = $featured_image_position;
    } else {
        $featured_image_position = 'none';
    }
    if($featured_image_position == 'bottom-full-width' || $featured_image_position == 'top-full-width') {
        $image_size = 'large';
    } else {
        $image_size = 'medium';
    }

    if($title_colour) {
        $title_colour = $title_colour;
    } else {
        $title_colour = '#000';
    }

    if($border_colour) {
        $border_colour = $border_colour;
    } else {
        $border_colour = '#000';
    }

    if($icon_colour) {
        $icon_colour = $icon_colour;
    } else {
        $icon_colour = '#000';
    }

    if($faq_page) {
        $faq_page == $faq_page;
    } else {
        $faq_page = '1';
    }
    if(is_page($faq_page)) {

        /* Get page content */
        if(get_the_content()) {
            echo wp_kses_post( $content );
        }

        if($use_faq_categories) {

            $args = array(
                'post_type' => 'wtf_faq',
                'taxonomy'  => 'wtf_category',
                'orderby'   => 'name'
            );
            $categories = get_categories( $args );
            
            foreach ( $categories as $category ) {
                $cat_args = array(
                    'post_type' => 'wtf_faq',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'wtf_category',
                            'field'    => 'slug',
                            'terms'    => $category->slug,
                        ),
                    ),
                );
                $posts = get_posts($cat_args);

                if(!is_singular('wtf_faq')) { ?>
                    <h2 class="wtf-category">
                        <?php echo esc_html($category->name); ?>
                    </h2>
                <?php }
                
                foreach($posts as $post) { ?>
        
                    <div class="wtf wtf-<?php echo esc_attr($post->post_name); ?> wtf-<?php echo esc_attr($category->slug); ?>">
                        <p class="wtf-question">
                            <?php echo esc_html(get_the_title()); ?>
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="<?php echo esc_attr($icon_colour); ?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                            </span>
                        </p>
                        <div class="wtf-content">
                            <?php 
                                if ($allow_featured_image && has_post_thumbnail( get_the_ID()) && $featured_image_position !='bottom-full-width') { ?>
                                    <img src="<?php echo esc_url(the_post_thumbnail_url($image_size)); ?>" class="<?php echo esc_attr($featured_image_position); ?>" />
                                <?php }
                                $faq_content = get_post_field('post_content', get_the_ID()); 

                                echo wpautop(wp_kses_post($faq_content));

                                if ($allow_featured_image && has_post_thumbnail( get_the_ID()) && $featured_image_position =='bottom-full-width') { ?>
                                    <img src="<?php echo esc_url(the_post_thumbnail_url($image_size)); ?>" class="<?php echo esc_attr($featured_image_position); ?>" />
                                <?php }
                            ?>
                        </div>
                    </div>

                <?php
                }    
            }
        } else {
            $args = array(
            'post_type'         => 'wtf_faq',
            'posts_per_page'    => -1
            );
            $query = new WP_Query($args);
            while ($query->have_posts()) : $query->the_post(); ?>
            
            <div class="wtf wtf-<?php echo esc_attr($post->post_name); ?>">
                <p class="wtf-question">
                    <?php echo esc_html(get_the_title()); ?>
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="<?php echo esc_attr($icon_colour); ?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </span>
                </p>
                <div class="wtf-content">
                    <?php 
                        if ($allow_featured_image && has_post_thumbnail( get_the_ID()) && $featured_image_position !='bottom-full-width') { ?>
                            <img src="<?php echo esc_url(the_post_thumbnail_url($image_size)); ?>" class="<?php echo esc_attr($featured_image_position); ?>" />
                        <?php }
                        $faq_content = get_post_field('post_content', get_the_ID()); 

                        echo wpautop(wp_kses_post($faq_content));

                        if ($allow_featured_image && has_post_thumbnail( get_the_ID()) && $featured_image_position =='bottom-full-width') { ?>
                            <img src="<?php echo esc_url(the_post_thumbnail_url($image_size)); ?>" class="<?php echo esc_attr($featured_image_position); ?>" />
                        <?php }
                    ?>
                </div>
            </div>

            <?php endwhile;
            wp_reset_postdata();
        }
        ?>

        <?php if(!$disable_js) { ?>
        <script>
            jQuery('.wtf-question').click(function() {
                jQuery(this).next('.wtf-content').toggle();
                jQuery(this).toggleClass('active');
                jQuery('.wtf-content').toggleClass('active');
            });
            <?php if($initial_state == 'open') { ?>
                jQuery('.wtf-question, .wtf-content').addClass('active');
                jQuery('.wtf-content').css('display', 'block').addClass('active');
            <?php } else if($initial_state == 'closed') { ?>
            <?php } ?>
        </script>
        <?php } ?>

        <?php if(!$disable_css) { ?>
            <style>
            .wtf-container {
                margin: 25px 0;
            }
            .wtf-question {
                color: <?php echo esc_html($title_colour); ?>;
                width: 100%;

                <?php if($border_style == 'boxed' || $border_style == '') { ?>
                    padding: <?php echo esc_html($padding); ?>px 60px <?php echo esc_html($padding); ?>px <?php echo esc_html($padding); ?>px;
                    border: solid 1px <?php echo esc_html($border_colour); ?>;
                    margin-bottom: -1px !important;         
                <?php } else if($border_style == 'underline') { ?>
                    padding: <?php echo esc_html($padding); ?>px 60px <?php echo esc_html($padding); ?>px 0;
                    border-bottom: solid 1px <?php echo esc_html($border_colour); ?>;
                    margin: 0;
                <?php } else if($border_style == 'none') { ?>
                    padding: <?php echo esc_html($padding); ?>px 60px <?php echo esc_html($padding); ?>px 0;
                    border: none;
                    margin-bottom: -1px !important;
                <?php } ?>

                <?php if($bg_colour) { ?>
                    background: <?php echo esc_html($bg_colour); ?>;
                <?php } ?>
                position: relative;
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                font-weight: bold;
                cursor: pointer;
                border-radius: <?php echo esc_html($border_radius); ?>px;
            }
            .wtf-question.active {
                border-top-left-radius: <?php echo esc_html($border_radius); ?>px;
                border-top-right-radius: <?php echo esc_html($border_radius); ?>px;
                border-bottom-left-radius: 0px;
                border-bottom-right-radius: 0px;
            }
            .wtf-question span {
                width: 24px;
                height: 24px; 
                display: block;
                position: absolute;
                top: <?php echo esc_html($padding); ?>px;

                <?php if($border_style == 'boxed' || $border_style == '') { ?>
                    right: <?php echo esc_html($padding); ?>px;
                <?php } else { ?>
                    right: 0;
                <?php } ?>
            }
            .wtf-content {
                display: none;

                <?php if($border_style == 'boxed' || $border_style == '') { ?>
                    padding: 0 <?php echo esc_html($padding); ?>px <?php echo esc_html($padding); ?>px <?php echo esc_html($padding); ?>px;
                    border-bottom: solid 1px <?php echo esc_html($border_colour); ?>;
                    border-left: solid 1px <?php echo esc_html($border_colour); ?>;
                    border-right: solid 1px <?php echo esc_html($border_colour); ?>;
                    border-top: none;
                <?php } else if($border_style == 'underline') { ?>
                    padding: 0 0 <?php echo esc_html($padding); ?>px 0;
                    border-bottom: solid 1px <?php echo esc_html($border_colour); ?>;
                <?php } else if($border_style == 'none') { ?>
                    padding: 0;
                <?php } ?>
                
                border-bottom-left-radius: <?php echo esc_html($border_radius); ?>px;
                border-bottom-right-radius: <?php echo esc_html($border_radius); ?>px;

                <?php if($bg_colour) { ?>
                    background: <?php echo esc_html($bg_colour); ?>;
                <?php } ?>
            }
            .wtf-content.active {
                margin: 0 0 20px 0;
            }
            .wtf-content::after {
                content: '';
                display: block;
                width: 100%;
                clear: both;
            }

            .wtf .active span {
                transform: rotate(180deg);
            }

            .wtf date,
            .wtf .go-to-faq {
                align-items: center;
                margin: 15px 0;
                font-size: .9em;
                line-height: 1em;
            }

            .wtf date svg,
            .wtf .go-to-faq svg {
                width: 12px;
                height: 12px;
                margin: 0 4px 0 0;
            }

            .wtf .wtf-question.active {
                <?php if($border_style == 'boxed' || $border_style == '') { ?>
                    border-top: solid 1px <?php echo esc_html($border_colour); ?>;
                    border-left: solid 1px <?php echo esc_html($border_colour); ?>;
                    border-right: solid 1px <?php echo esc_html($border_colour); ?>;
                <?php } else if($border_style == 'underline') { ?>
                    border: none;
                <?php } else if($border_style == 'none') { ?>
                    border: none;
                <?php } ?>

                border-bottom: none;
                margin: 20px 0 0 0;
                background: <?php echo esc_html($bg_colour); ?>;
            }
            .wtf img.none {
                display: block;
                float: right;
                margin: 0 0 20px 20px;
            }
            .wtf .float-top-right {
                display: block;
                float: right;
                margin: 0 0 20px 20px;
            }
            .wtf .float-top-left {
                display: block;
                float: left;
                margin: 0 20px 20px 0;
            }
            .wtf .top-full-width {
                display: block;
                width: 100%;
                margin: 0 0 20px 0;
            }
            .wtf .bottom-full-width {
                display: block;
                width: 100%;
                margin: 20px 0 0 0;
            }
        </style>
        <?php } ?>

    <?php }

    if(is_singular('wtf_faq')) {
    
        $faq_content = get_post_field('post_content', get_the_ID()); 

        echo wpautop(wp_kses_post($faq_content));

    }
}
add_filter( 'the_content', 'what_the_faq_content_filter' );