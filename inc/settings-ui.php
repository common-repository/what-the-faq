<?php if ( ! defined( 'ABSPATH' ) ) exit;

/* Select 2 for page selection */
function what_the_faq_enqueue_select2_jquery() {
    wp_register_style( 'select2css', esc_url(plugins_url('../css/', __FILE__ ) . 'select2.css'), false, '1.0', 'all' );
    wp_register_script( 'select2', esc_url(plugins_url('../js/', __FILE__ ) . 'select2.js'), array( 'jquery' ), '1.0', true );
    wp_enqueue_style( 'select2css' );
    wp_enqueue_script( 'select2' );
}
add_action( 'admin_enqueue_scripts', 'what_the_faq_enqueue_select2_jquery' );

function what_the_faq_select2jquery_inline() {
		?>
		<style type="text/css">
			.select2-container {margin: 0 2px 0 2px;}
			.tablenav.top #doaction, #doaction2, #post-query-submit {margin: 0px 4px 0 4px;}
		</style>
		<script type='text/javascript'>
            jQuery(document).ready(function ($) {
                if( $( '#wtf-page, #featured-image-position' ).length > 0 ) {
                    $( '#wtf-page, #featured-image-position' ).select2({ width: 'resolve' });
                }
            });
		</script>
		<?php
	}
add_action( 'admin_head', 'what_the_faq_select2jquery_inline' );

/* Colour picker */
add_action( 'admin_enqueue_scripts', 'what_the_faq_color_picker' );
function what_the_faq_color_picker( $hook ) {
 
    if( is_admin() ) { 
     
        // Add the color picker css file       
        wp_enqueue_style( 'wp-color-picker' ); 
         
        // Include our custom jQuery file with WordPress Color Picker dependency
        wp_enqueue_script( 'color-script-handle', esc_url(plugins_url('../js/', __FILE__ ) . 'wtf.js'), array( 'wp-color-picker' ), false, true ); 
    }
}

function what_the_faq_settings_page() {
    $options = get_option( 'what_the_faq_settings' ); 
?>

<h1><?php esc_html_e('What The FAQ', 'what-the-faq'); ?></h1>

<div class="wrap wtf-settings">   

    <form method="post" action="options.php">
        <?php 
            settings_fields( 'what_the_faq_settings' );
            $options                    = get_option( 'what_the_faq_settings' );
            $initial_state              = isset($options['initial_state']) ? $options['initial_state'] : '';
            $faq_page                   = isset($options['faq_page']) ? $options['faq_page'] : '';
            $disable_css                = isset($options['disable_css']) ? $options['disable_css'] : '';
            $disable_js                 = isset($options['disable_js']) ? $options['disable_js'] : '';
            $border_colour              = isset($options['border_colour']) ? $options['border_colour'] : '';
            $border_radius              = isset($options['border_radius']) ? $options['border_radius'] : '';
            $border_style               = isset($options['border_style']) ? $options['border_style'] : '';
            $padding                    = isset($options['padding']) ? $options['padding'] : '';
            $bg_colour                  = isset($options['bg_colour']) ? $options['bg_colour'] : '';
            $title_colour               = isset($options['title_colour']) ? $options['title_colour'] : '';
            $icon_colour                = isset($options['icon_colour']) ? $options['icon_colour'] : '';
            $allow_featured_image       = isset($options['allow_featured_image']) ? $options['allow_featured_image'] : '';
            $featured_image_position    = isset($options['featured_image_position']) ? $options['featured_image_position'] : '';
            $use_faq_categories         = isset($options['use_faq_categories']) ? $options['use_faq_categories'] : '';            
            $exclude_from_search        = isset($options['exclude_from_search']) ? $options['exclude_from_search'] : '';

            $pro                        = ' <a href="' . esc_url('https://rocketapps.com.au/product/what-the-faq-pro/?origin=wtf') . '" target="' . esc_attr('_blank') . '" rel="' . esc_attr('noopener') . '">' . __("Pro", "what-the-faq") . '</a>';
        ?>

        <table>
            <tbody>
                <tr class="table-section">
                    <td><?php esc_html_e('General Settings', 'what-the-faq'); ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <strong><?php esc_html_e('FAQ page', 'what-the-faq'); ?></strong>
                        <p class="description"><?php esc_html_e('Which page to show the FAQs.', 'what-the-faq'); ?> 
                            <?php if($faq_page) { ?>
                            <a href="<?php echo esc_url(get_the_permalink($faq_page)); ?>" target="_blank"><?php esc_html_e('View page', 'what-the-faq'); ?></a>.
                            <?php } ?>
                        </p>
                    </td>
                    <td>
                        <select name="what_the_faq_settings[faq_page]" id="wtf-page">
                            <option></option>
                            <?php
                                $args = array(
                                    'post_type'         => 'page',
                                    'orderby'           => 'title',
                                    'order'             => 'desc',
                                    'posts_per_page'    => -1
                                );
                                $query = new WP_Query($args);
                                while ($query->have_posts()) : $query->the_post(); ?>
                                    <option value="<?php echo esc_attr(get_the_id()); ?>" <?php if($faq_page == get_the_id()) { echo 'selected'; } ?>><?php echo esc_html(the_title()); ?></option>
                                <?php endwhile;
                                wp_reset_postdata();
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong><?php esc_html_e('Initital state', 'what-the-faq'); ?></strong>
                        <p class="description"><?php esc_html_e('How FAQs should be initially displayed.', 'what-the-faq'); ?></p>
                    </td>
                    <td>
                        <ul>
                            <li><input type="radio" name="what_the_faq_settings[initial_state]" value="collapsed" <?php if($initial_state == 'collapsed' || $initial_state == '') { echo esc_attr('checked'); } ?> /> <?php esc_html_e('Collapsed', 'what-the-faq'); ?></li>
                            <li><input type="radio" name="what_the_faq_settings[initial_state]" value="open" <?php if($initial_state == 'open') { echo esc_attr('checked'); } ?> /> <?php esc_html_e('Open', 'what-the-faq'); ?></li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong><?php esc_html_e('Background colour', 'what-the-faq'); ?></strong>
                        <p class="description"><?php esc_html_e('The background colour of listed FAQs.', 'what-the-faq'); ?></p>
                    </td>
                    <td>
                        <ul>
                            <li><input type="text" name="what_the_faq_settings[bg_colour]" <?php if ( ! empty( $bg_colour ) ) { echo 'value="' . esc_attr($bg_colour) . '"'; } ?> class="colour-picker" /></li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong><?php esc_html_e('FAQ title colour', 'what-the-faq'); ?></strong>
                        <p class="description"><?php esc_html_e('The colour of the FAQ title.', 'what-the-faq'); ?></p>
                    </td>
                    <td>
                        <ul>
                            <li><input type="text" name="what_the_faq_settings[title_colour]" <?php if ( ! empty( $title_colour ) ) { echo 'value="' . esc_attr($title_colour) . '"'; } ?> class="colour-picker" /></li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong><?php esc_html_e('Icon colour', 'what-the-faq'); ?></strong>
                        <p class="description"><?php esc_html_e('The colour of the icons (arrows, dates and links).', 'what-the-faq'); ?></p>
                    </td>
                    <td>
                        <ul>
                            <li><input type="text" name="what_the_faq_settings[icon_colour]" <?php if ( ! empty( $icon_colour ) ) { echo 'value="' . esc_attr($icon_colour) . '"'; } ?> class="colour-picker" /></li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong><?php esc_html_e('Border style', 'what-the-faq'); ?></strong>
                        <p class="description"><?php esc_html_e('The presentation of the FAQ borders.', 'what-the-faq'); ?></p>
                    </td>
                    <td>
                        <ul class="border-style-images">
                            <li <?php if($border_style == 'boxed' || $border_style == '') { echo 'class="selected"'; } ?>>
                                <label>
                                    <img src="<?php echo plugins_url('../images/', __FILE__ ) . 'border-style-boxed.png'; ?>" title="<?php esc_html_e('Boxed (default)', 'what-the-faq'); ?>" />
                                    <input type="radio" name="what_the_faq_settings[border_style]" value="boxed" <?php if($border_style == 'Boxed') { echo esc_attr('checked'); } ?> id="border_choice_boxed" />
                                </label>
                            </li>
                            <li <?php if($border_style == 'underline') { echo 'class="selected"'; } ?>>
                                <label>
                                    <img src="<?php echo plugins_url('../images/', __FILE__ ) . 'border-style-underline.png'; ?>" title="<?php esc_html_e('Underline', 'what-the-faq'); ?>" />
                                    <input type="radio" name="what_the_faq_settings[border_style]" value="underline" <?php if($border_style == 'underline') { echo esc_attr('checked'); } ?> id="border_choice_underline" /> 
                                </label>
                            </li>
                            <li <?php if($border_style == 'none') { echo 'class="selected"'; } ?>>
                                <label>
                                    <img src="<?php echo plugins_url('../images/', __FILE__ ) . 'border-style-none.png'; ?>" title="<?php esc_html_e('None', 'what-the-faq'); ?>" />
                                    <input type="radio" name="what_the_faq_settings[border_style]" value="none" <?php if($border_style == 'none') { echo esc_attr('checked'); } ?> id="border_choice_none" /> 
                                </label>
                            </li>
                        </ul>
                    </td>
                </tr>
                <tr class="border-choices <?php if($border_style == 'boxed' || $border_style == 'underline' || $border_style == '') { echo 'visible'; } else { echo 'hidden'; } ?>">
                    <td>
                        <strong><?php esc_html_e('Border colour', 'what-the-faq'); ?></strong>
                        <p class="description"><?php esc_html_e('The colour of the FAQ borders.', 'what-the-faq'); ?></p>
                    </td>
                    <td>
                        <ul>
                            <li><input type="text" name="what_the_faq_settings[border_colour]" <?php if ( ! empty( $border_colour ) ) { echo 'value="' . esc_attr($border_colour) . '"'; } ?> class="colour-picker" /></li>
                        </ul>
                    </td>
                </tr>
                <tr class="border-choices <?php if($border_style == 'boxed' || $border_style == 'underline') { echo 'visible'; } else { echo 'hidden'; } ?>">
                    <td>
                        <strong><?php esc_html_e('Border radius', 'what-the-faq'); ?></strong>
                        <p class="description"><?php esc_html_e('The border roundness of FAQs when open.', 'what-the-faq'); ?></p>
                    </td>
                    <td>
                        <ul>
                            <li><input type="number" name="what_the_faq_settings[border_radius]" <?php if ( ! empty( $border_radius ) ) { echo 'value="' . esc_attr($border_radius) . '"'; } ?> style="width: 65px" /> <?php esc_html_e('px', 'what-the-faq'); ?></li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong><?php esc_html_e('Padding', 'what-the-faq'); ?></strong>
                        <p class="description"><?php esc_html_e('The amount of padding inside the FAQ containers.', 'what-the-faq'); ?></p>
                    </td>
                    <td>
                        <ul>
                            <li><input type="number" name="what_the_faq_settings[padding]" <?php if ( ! empty( $padding ) ) { echo 'value="' . esc_attr($padding) . '"'; } ?> style="width: 65px" placeholder="15" /> <?php esc_html_e('px', 'what-the-faq'); ?></li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong><?php esc_html_e('Allow featured image', 'what-the-faq'); ?></strong>
                        <p class="description"><?php esc_html_e('Allow a featured image to appear with FAQs.', 'what-the-faq'); ?></p>
                    </td>
                    <td>
                        <ul>
                            <li><input type="checkbox" name="what_the_faq_settings[allow_featured_image]" value="1" <?php if($allow_featured_image) { echo esc_attr('checked'); } ?> /> <?php esc_html_e('Yes', 'what-the-faq'); ?></li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong><?php esc_html_e('Featured image position', 'what-the-faq'); ?></strong>
                        <p class="description"><?php esc_html_e('Where featured images appear within FAQs.', 'what-the-faq'); ?></p>
                    </td>
                    <td>
                        <select name="what_the_faq_settings[featured_image_position]" id="featured-image-position">
                            <option value=""></option>
                            <option value="float-top-left" <?php if($featured_image_position == 'float-top-left') { echo esc_attr('selected'); } ?>><?php esc_html_e('Float top left', 'what-the-faq'); ?></option>
                            <option value="float-top-right" <?php if($featured_image_position == 'float-top-right' || $featured_image_position == '') { echo esc_attr('selected'); } ?>><?php esc_html_e('Float top right', 'what-the-faq'); ?></option>
                            <option value="top-full-width" <?php if($featured_image_position == 'top-full-width') { echo esc_attr('selected'); } ?>><?php esc_html_e('Top full width', 'what-the-faq'); ?></option>
                            <option value="bottom-full-width" <?php if($featured_image_position == 'bottom-full-width') { echo esc_attr('selected'); } ?>><?php esc_html_e('Bottom full width', 'what-the-faq'); ?></option>
                        </select>
                    </td>
                </tr>
                
                <tr class="table-section">
                    <td><?php esc_html_e('Advanced Settings', 'what-the-faq'); ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <strong><?php esc_html_e('Disable FAQ styles', 'what-the-faq'); ?></strong>
                        <p class="description"><?php esc_html_e('Disable all FAQ styles (you will have to write your own CSS).', 'what-the-faq'); ?></p>
                    </td>
                    <td>
                        <ul>
                            <li><input type="checkbox" name="what_the_faq_settings[disable_css]" value="1" <?php if($disable_css) { echo esc_attr('checked'); } ?> /> <?php esc_html_e('Disable CSS', 'what-the-faq'); ?>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong><?php esc_html_e('Disable FAQ JS', 'what-the-faq'); ?></strong>
                        <p class="description"><?php esc_html_e('Disable all FAQ JavaScript (you will have to write your own JS if you want it).', 'what-the-faq'); ?></p>
                    </td>
                    <td>
                        <ul>
                            <li><input type="checkbox" name="what_the_faq_settings[disable_js]" value="1" <?php if($disable_js) { echo esc_attr('checked'); } ?> /> <?php esc_html_e('Disable JS', 'what-the-faq'); ?>
                        </ul>
                    </td>
                </tr>       
                <tr>
                    <td>
                        <strong><?php esc_html_e('Exclude from search', 'what-the-faq'); ?></strong>
                        <p class="description"><?php esc_html_e("Don't show FAQs in search results", 'what-the-faq'); ?></p>
                    </td>
                    <td>
                        <ul>
                            <li><input type="checkbox" name="what_the_faq_settings[exclude_from_search]" value="1" <?php if($exclude_from_search) { echo esc_attr('checked'); } ?> /> <?php esc_html_e('Exclude', 'what-the-faq'); ?>
                        </ul>
                    </td>
                </tr>     
                <tr>
                    <td colspan="2" class="upgrade-prompt">
                        <input name="submit" class="button button-primary" value="Save Settings" type="submit" />    
                    </td>
                </tr>



                <!--/ Pro Features /-->
                <tr class="table-section">
                    <td><?php esc_html_e('Pro Features', 'what-the-faq'); ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <strong><?php esc_html_e('Animation', 'what-the-faq'); ?> <?php echo $pro; ?></strong>
                        <p class="description"><?php esc_html_e('How the FAQs appear when clicked.', 'what-the-faq'); ?></p>
                    </td>
                    <td>
                        <ul>
                            <li><input type="radio" value="fade" name="animation" class="upgrade" /> <?php esc_html_e('Fade', 'what-the-faq'); ?></li>
                            <li><input type="radio" value="slide" name="animation" class="upgrade" /> <?php esc_html_e('Slide', 'what-the-faq'); ?></li>
                            <li><input type="radio"value="none" name="animation" class="upgrade" checked /><?php esc_html_e('None', 'what-the-faq'); ?></li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong><?php esc_html_e('Arrow style', 'what-the-faq'); ?> <?php echo $pro; ?></strong>
                        <p class="description"><?php esc_html_e('How the arrows appear.', 'what-the-faq'); ?></p>
                    </td>
                    <td>
                        <ul class="box-choices">
                            <li>
                                <img src="<?php echo plugins_url('../images/', __FILE__ ) . 'arrow-down.svg'; ?>" />
                                <input type="radio" name="arrow_style" class="upgrade" />
                            </li>
                            <li>
                                <img src="<?php echo plugins_url('../images/', __FILE__ ) . 'arrow-down-circle.svg'; ?>" />
                                <input type="radio" name="arrow_style" class="upgrade" />
                            </li>
                            <li class="selected">
                                <img src="<?php echo plugins_url('../images/', __FILE__ ) . 'chevron-down.svg'; ?>" />
                                <input type="radio" name="arrow_style" class="upgrade" />
                            </li>
                            <li>
                                <img src="<?php echo plugins_url('../images/', __FILE__ ) . 'chevrons-down.svg'; ?>" />
                                <input type="radio" name="arrow_style" class="upgrade" />
                            </li>
                            <li>
                                <img src="<?php echo plugins_url('../images/', __FILE__ ) . 'plus.svg'; ?>" />
                                <input type="radio" name="arrow_style" class="upgrade" />
                            </li>
                            <li>
                                <img src="<?php echo plugins_url('../images/', __FILE__ ) . 'plus-circle.svg'; ?>" />
                                <input type="radio" name="arrow_style" class="upgrade" />
                            </li>
                            <li>
                                <img src="<?php echo plugins_url('../images/', __FILE__ ) . 'plus-square.svg'; ?>" />
                                <input type="radio" name="arrow_style" class="upgrade" />
                            </li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong><?php esc_html_e('Arrow position', 'what-the-faq'); ?> <?php echo $pro; ?></strong>
                        <p class="description"><?php esc_html_e('Where the arrows appear.', 'what-the-faq'); ?></p>
                    </td>
                    <td>
                        <ul>
                            <li><input type="radio" class="upgrade" checked /> <?php esc_html_e('Right', 'what-the-faq'); ?></li>
                            <li><input type="radio" class="upgrade" /> <?php esc_html_e('Left', 'what-the-faq'); ?></li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong><?php esc_html_e('Date', 'what-the-faq'); ?> <?php echo $pro; ?></strong>
                        <p class="description"><?php esc_html_e('Show the date the FAQ was published.', 'what-the-faq'); ?></p>
                    </td>
                    <td>
                        <ul>
                            <li><input type="checkbox" name="faq_date" class="upgrade" /> <?php esc_html_e('Yes', 'what-the-faq'); ?></li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong><?php esc_html_e('Allow FAQ pages', 'what-the-faq'); ?> <?php echo $pro; ?></strong>
                        <p class="description"><?php esc_html_e("Each FAQ can also appear on a its own page (with its own URL).", 'what-the-faq'); ?></p>
                    </td>
                    <td>
                        <ul>
                            <li><input type="checkbox" name="allow_faq_pages" class="upgrade" /> <?php esc_html_e('Yes', 'what-the-faq'); ?>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong><?php esc_html_e('Show FAQ link', 'what-the-faq'); ?> <?php echo $pro; ?></strong>
                        <p class="description"><?php esc_html_e("Each FAQ will include a link to its page.", 'what-the-faq'); ?></p>
                    </td>
                    <td>
                        <ul>
                            <li><input type="checkbox" name="show_faq_link" class="upgrade" /> <?php esc_html_e('Yes', 'what-the-faq'); ?>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong><?php esc_html_e('Icon stroke width', 'what-the-faq'); ?> <?php echo $pro; ?></strong>
                        <p class="description"><?php esc_html_e('How thick the outlines of the icons are.', 'what-the-faq'); ?></p>
                    </td>
                    <td>
                        <ul>
                            <li><input type="number" name="what_the_faq_settings[icon_stroke]" style="width: 65px" min="1" max="5" value="2" class="upgrade" /> <?php esc_html_e('px', 'what-the-faq'); ?></li>
                        </ul>
                    </td>
                </tr>    
                <tr>
                    <td>
                        <strong><?php esc_html_e('Use FAQ categories', 'what-the-faq'); ?> <?php echo $pro; ?></strong>
                        <p class="description"><?php esc_html_e("FAQs must be categorised within WTF Categories.", 'what-the-faq'); ?></p>
                    </td>
                    <td>
                        <ul>
                            <li><input type="checkbox" class="upgrade" /> <?php esc_html_e('Yes', 'what-the-faq'); ?>
                        </ul>
                    </td>
                </tr>   
                <tr>
                    <td>
                        <strong><?php esc_html_e('Category heading colour', 'what-the-faq'); ?> <?php echo $pro; ?></strong>
                        <p class="description"><?php esc_html_e('The colour of the FAQ category headings.', 'what-the-faq'); ?></p>
                    </td>
                    <td>
                        <ul>
                            <li><input type="text" class="colour-picker upgrade" /></li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="upgrade-prompt">
                        <a href="https://rocketapps.com.au/product/what-the-faq-pro/?origin=wtf" target="_blank" rel="noopener"><?php esc_html_e('Upgrade To Pro', 'what-the-faq'); ?><img src="<?php echo plugins_url('../images/', __FILE__ ) . 'external-link.svg'; ?>" /></a>
                    </td>
                </tr>  

            </tbody>
        </table>   

        <script>
            jQuery('.border-style-images li').click(function() {
                jQuery('.border-style-images li').removeClass('selected');
                jQuery(this).addClass('selected');
            });
            jQuery('#border_choice_boxed, #border_choice_underline').click(function() {
                if( jQuery(this).is(':checked') ){
                    jQuery('.border-choices').fadeIn();
                } 
            });
            jQuery('#border_choice_none').click(function() {
                if( jQuery(this).is(':checked') ){
                    jQuery('.border-choices').fadeOut();
                }
            });
        </script>

    </form>

    <?php require_once('settings-sidebar.php'); ?>

</div>
<?php }