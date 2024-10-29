<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://acquaintsoft.com/
 * @since             1.0.0
 * @package           Acquaint_Owl_Carousel
 *
 * @wordpress-plugin
 * Plugin Name:       Acquaint Owl Carousel
 * Plugin URI:        http://acquaintsoft.com/
 * Description:       This is a owl carousel plugin, which provide a dynamic way to create owl carousel with options.
 * Version:           1.0.0
 * Author:            Acquaintsoft
 * Author URI:        http://acquaintsoft.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       acquaint-owl-carousel
 * Domain Path:       /languages
 */
class Acquaint_Owl_Carousel_Plugin {
    /* ------------------Function Construct------------------------ */

    function __construct() {
        add_action('init', array($this, 'create_acquaint_owl_carousel_post_type'));
        add_action('add_meta_boxes', array($this, 'carousel_meta_box_add'));
        add_action('add_meta_boxes', array($this, 'carousel_images_meta_box_add'));
        add_action('save_post', array($this, 'carousel_update_post'), 10, 2);
        add_action('manage_posts_custom_column', array($this, 'custom_columns'), 10, 2);
        add_action('wp_enqueue_scripts', array($this, 'acquaint_owl_carousel_style'));
        add_filter("manage_posts_columns", array($this, "custom_columns_heading"));
        add_filter( 'enter_title_here', array($this, 'wpb_change_title_text' ));
        add_shortcode('acquaint-owl-carousel', array($this, 'acquaint_owl_carousel'));

        /* --------------- Activation & Deactivation Hooks ------------------------ */
        register_activation_hook(__FILE__, 'activate_acquaint_owl_carousel');
        register_deactivation_hook(__FILE__, 'deactivate_acquaint_owl_carousel');

        /**
         * The core plugin class that is used to define internationalization,
         * admin-specific hooks, and public-facing site hooks.
         */
        require plugin_dir_path(__FILE__) . 'includes/class-acquaint-owl-carousel.php';
    }

    /**
     * The code that runs during plugin activation.
     * This action is documented in includes/class-acquaint-owl-carousel-activator.php
     */
    function activate_acquaint_owl_carousel() {
        require_once plugin_dir_path(__FILE__) . 'includes/class-acquaint-owl-carousel-activator.php';
        Acquaint_Owl_Carousel_Activator::activate();
    }

    /**
     * The code that runs during plugin deactivation.
     * This action is documented in includes/class-acquaint-owl-carousel-deactivator.php
     */
    function deactivate_acquaint_owl_carousel() {
        require_once plugin_dir_path(__FILE__) . 'includes/class-acquaint-owl-carousel-deactivator.php';
        Acquaint_Owl_Carousel_Deactivator::deactivate();
    }


    /* ------------------ Create a owl carousel post type------------------ */     
    function create_acquaint_owl_carousel_post_type() {
        register_post_type('owl_carousel', array(
            'labels' => array(
                'name' => __('Acquaint Owl Carousel'),
                'singular_name' => __('acquaint_owl_carousel'),
                'add_new' => __('Add New Slide'),'add_new' => __( 'Add New Slider', AQSS_TEXT_DOMAIN ),
                'add_new_item' => __( 'Add New Carouse Slider', AQSS_TEXT_DOMAIN ),
                'edit_item' => __( 'Edit Carouse Slider', AQSS_TEXT_DOMAIN ),
                'view_item' => __( 'View Image Slider', AQSS_TEXT_DOMAIN ),
                
            ),
            'hierarchical' => false,
            'supports' => array( 'title' ),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 10,
            'menu_icon' => 'dashicons-format-gallery',
            'show_in_nav_menus' => false,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'has_archive' => true,
            'query_var' => true,
            'can_export' => true,
            'rewrite' => false,
            'capability_type' => 'post'
                )
        );
    }

    // Change Slider Title
    function wpb_change_title_text( $title ){
         $screen = get_current_screen();
         if  ( 'owl_carousel' == $screen->post_type ) {
              $title = 'Enter Slider Title';
         }
         return $title;
    }

    /* ------------------ Adds a custom Owl Carousel box ------------------ */
    function carousel_meta_box_add() {
        add_meta_box('carouseldiv', 'Slide Settings', array($this, 'carousel_meta_box'), 'owl_carousel', 'normal', 'low');
    }

    /* ------------------ Get Owl Carousel slider types in metabox --------------------- */
    function carousel_meta_box($post) {
        global $post;

        $post_id = $post->ID;
        $get_slider_type = get_post_meta($post_id, 'slider_type', true);
        $autoplay_checked = 'checked';
        $animate_checked = '';
        $auto_height_checked = '';
        $auto_width_checked = '';
        $center_checked = '';
        $events_checked = '';
        $lazy_load_checked = '';
        $merge_checked = '';
        $mousewheel_checked = '';
        $responsive_checked = '';
        $right_to_left_checked = '';
        $stagepadding_checked = '';
        $url_hash_navigation_checked = '';
        $video_checked = '';

        if ($get_slider_type == 'animate') {
            $animate_checked = 'checked';
        }
        if ($get_slider_type == 'auto_height') {
            $auto_height_checked = 'checked';
        }
        if ($get_slider_type == 'auto_width') {
            $auto_width_checked = 'checked';
        }
        if ($get_slider_type == 'center') {
            $center_checked = 'checked';
        }
        if ($get_slider_type == 'events') {
            $events_checked = 'checked';
        }
        if ($get_slider_type == 'lazy_load') {
            $lazy_load_checked = 'checked';
        }
        if ($get_slider_type == 'merge') {
            $merge_checked = 'checked';
        }
        if ($get_slider_type == 'mousewheel') {
            $mousewheel_checked = 'checked';
        }
        if ($get_slider_type == 'responsive') {
            $responsive_checked = 'checked';
        }
        if ($get_slider_type == 'right_to_left') {
            $right_to_left_checked = 'checked';
        }
        if ($get_slider_type == 'stagepadding') {
            $stagepadding_checked = 'checked';
        }
        if ($get_slider_type == 'url_hash_navigation') {
            $url_hash_navigation_checked = 'checked';
        }
        if ($get_slider_type == 'video') {
            $video_checked = 'checked';
        }

        /*
         * Start: Slider Types HTML
         */
        $slider_type = '<p class="label"><label>Slider Type</label></p><ul class="agc-radio-list radio horizontal">';
        $slider_type .= '<li><label for="autoplay"><input type="radio" ' . esc_attr($autoplay_checked) . ' id="autoplay" name="slider_type" value="autoplay"> Autoplay </label></li>';
        $slider_type .= '<li><label for="animate"><input type="radio" ' . esc_attr($animate_checked) . ' id="animate" name="slider_type" value="animate"> Animate </label></li>';
        $slider_type .= '<li><label for="auto_height"><input type="radio" ' . esc_attr($auto_height_checked) . ' id="auto_height" name="slider_type" value="auto_height"> Auto Height </label></li>';
        $slider_type .= '<li><label for="auto_width"><input type="radio" ' . esc_attr($auto_width_checked) . ' id="auto_width" name="slider_type" value="auto_width"> Auto Width </label></li>';
        $slider_type .= '<li><label for="center"><input type="radio" ' . esc_attr($center_checked) . ' id="center" name="slider_type" value="center"> Center </label></li>';
        $slider_type .= '<li><label for="events"><input type="radio" ' . esc_attr($events_checked) . ' id="events" name="slider_type" value="events"> Events </label></li>';
        $slider_type .= '<li><label for="lazy_load"><input type="radio" ' . esc_attr($lazy_load_checked) . ' id="lazy_load" name="slider_type" value="lazy_load"> Lazy Load </label></li>';
        $slider_type .= '<li><label for="merge"><input type="radio" ' . esc_attr($merge_checked) . ' id="merge" name="slider_type" value="merge"> Merge </label></li>';
        $slider_type .= '<li><label for="mousewheel"><input type="radio" ' . esc_attr($mousewheel_checked) . ' id="mousewheel" name="slider_type" value="mousewheel"> Mousewheel </label></li>';
        $slider_type .= '<li><label for="responsive"><input type="radio" ' . esc_attr($responsive_checked) . ' id="responsive" name="slider_type" value="responsive"> Responsive </label></li>';
        $slider_type .= '<li><label for="right_to_left"><input type="radio" ' . esc_attr($right_to_left_checked) . ' id="right_to_left" name="slider_type" value="right_to_left"> Right To Left </label></li>';
        $slider_type .= '<li><label for="stagepadding"><input type="radio" ' . esc_attr($stagepadding_checked) . ' id="stagepadding" name="slider_type" value="stagepadding"> stagePadding </label></li>';
        $slider_type .= '<li><label for="url_hash_navigation"><input type="radio" ' . esc_attr($url_hash_navigation_checked) . ' id="url_hash_navigation" name="slider_type" value="url_hash_navigation"> Url Hash Navigation </label></li>';
        $slider_type .= '<li><label for="video"><input type="radio" ' . esc_attr($video_checked) . ' id="video" name="slider_type" value="video"> Video </label></li></ul>';
        /*
         * End: Slider Types HTML
         */
        echo $slider_type;
    }

    /* ------------------ Add a custom Owl Carousel Data box ------------------ */
    function carousel_images_meta_box_add() {
        add_meta_box('carouselimagesdiv', 'Slider', array($this, 'carousel_images_meta_box'), 'owl_carousel', 'normal', 'low');
    }

    /* ------------------ add/edit slider data in metabox --------------------- */
    function carousel_images_meta_box($post) {
        global $post;

        $post_id = $post->ID;
        /*---------------------------- Start: Slide js ---------------------------------*/
        ?>
        <script type="text/javascript" language="JavaScript">
            jQuery(document).ready(function() {
                var elementCounter = jQuery('#elementcounter').val();
                
                // Add slide - change name, id only
                jQuery("#add").click(function() {
                    var elementRow = jQuery("tr.new_slider").clone();
                    var newId = "slider-" + elementCounter;
                    elementRow.attr("id", newId);
                    elementRow.show();
                    jQuery(".slide-table > tbody").append(elementRow);
                    jQuery('#slider-' + elementCounter + ' input#slider_title').attr("name", "slider_title[]");
                    jQuery('#slider-' + elementCounter + ' input#slider_title').attr("id", "slider_title_" + elementCounter);
                    jQuery('#slider-' + elementCounter + ' input#slider_merge').attr("name", "slider_merge[]");
                    jQuery('#slider-' + elementCounter + ' input#slider_merge').attr("id", "slider_merge_" + elementCounter);
                    jQuery('#slider-' + elementCounter + ' input#slider_video').attr("name", "slider_video[]");
                    jQuery('#slider-' + elementCounter + ' input#slider_video').attr("id", "slider_video_" + elementCounter);
                    jQuery('#slider-' + elementCounter + ' input#slider_image').attr("name", "slider_image[]");
                    jQuery('#slider-' + elementCounter + ' input#slider_image').attr("id", "slider_image_" + elementCounter);
                    jQuery('#slider-' + elementCounter + ' input#remove').attr("id", "removediv");
                    jQuery('#slider-' + elementCounter).removeClass("new_slider");
                    elementCounter++;
                    return false;
                });
                
                // Click on slider option event
                jQuery("input[name='slider_type']").click(function($this){
                    var checked_slider_type = $this.target.value; // Slider type
                    
                    // Check if merge option is selected then hide/show related div
                    if (checked_slider_type == 'merge'){
                        jQuery(".merge_div").show();
                        jQuery(".image_div").show();
                        jQuery(".video_div").hide();
                        jQuery(".note").hide();
                        jQuery( "input[name='slider_video[]']" ).rules( "remove");

                        // If mode is not edit then add slider image validation rule      
                        <?php if (sanitize_text_field($_GET['action']) != 'edit') { ?>
                            jQuery( "input[name='slider_image[]']" ).rules( "add", {
                                required: true,
                                accept: "gif|jpg|jpeg|bmp|png",
                                messages: {
                                    required: "Please select slider image.",
                                    accept: "Please select proper slider image. Ex. gif, jpg, jpeg, bmp, png."
                                }
                            });
                        <?php } ?>
                    } 
                    // Check if video option is selected then hide/show related div
                    else if (checked_slider_type == 'video'){
                        jQuery(".merge_div").show();
                        jQuery(".video_div").show();
                        jQuery(".image_div").hide();
                        jQuery(".note").show();
                        jQuery( "input[name='slider_video[]']" ).rules( "add", {
                            required: true,
                            checkVideoURL: true,
                            messages: {
                                required: "Please enter video URL",
                                checkVideoURL: "Please enter valid video URL"
                            }
                        });
                        jQuery( "input[name='slider_image[]']" ).rules( "remove");
                    }
                    // All other option hide/show related divs.
                    else {
                        jQuery(".merge_div").hide();
                        jQuery(".video_div").hide();
                        jQuery(".image_div").show();
                        jQuery(".note").hide();
                        jQuery( "input[name='slider_video[]']" ).rules( "remove");

                        // If mode is not edit then add slider image validation rule      
                        <?php if (sanitize_text_field($_GET['action']) != 'edit') { ?>
                            jQuery( "input[name='slider_image[]']" ).rules( "add", {
                                required: true,
                                accept: "gif|jpg|jpeg|bmp|png",
                                messages: {
                                    required: "Please select slider image.",
                                    accept: "Please select proper slider image. Ex. gif, jpg, jpeg, bmp, png."
                                }
                            });
                        <?php } ?>
                    }
                });

                
                /*
                 * Start: Slide Valdation
                 */
                
                // Add Video urls validation method(Youtube, Vimeo)
                jQuery.validator.addMethod("checkVideoURL", function(value, element) {
                    if (/https?:\/\/(?:[0-9A-Z\-]+\.)?(vimeo\.com\/(clip\:)?(\d+).*|player\.vimeo\.com\/video\/?(\d+).*|(?:youtu\.be\/|youtube\.com\S*[^\w\-\s])([\w\-]{11})(?=[^\w\-]|$)(?![?=&+%\w]*(?:['"][^<>]*>|<\/a>))[?=&+%\w\-]*)/i.test(value)) {
                       return true; // FAIL validation when REGEX matches
                    } else {
                      return false; // PASS validation otherwise
                    };
                });
                
                // Validate slide data
                jQuery("#post").validate({
                    rules: {
                        'slider_title[]': {
                            required: true,
                        },
                    },
                    messages: {
                        'slider_title[]': {
                            required: "Please enter slider title.",
                        },
                    }
                });

                // Check if merge option is selected then show related div
                if (jQuery("#merge").is(':checked')){
                    jQuery(".merge_div").show();
                    jQuery(".note").hide();
                    jQuery( "input[name='slider_video[]']" ).rules( "remove");

                    // If mode is not edit then add slider image validation rule      
                    <?php if (sanitize_text_field($_GET['action']) != 'edit') { ?>
                        jQuery( "input[name='slider_image[]']" ).rules( "add", {
                            required: true,
                            accept: "gif|jpg|jpeg|bmp|png",
                            messages: {
                                required: "Please select slider image.",
                                accept: "Please select proper slider image. Ex. gif, jpg, jpeg, bmp, png."
                            }
                        });
                    <?php } ?>
                } 
                // Check if video option is selected then show related div
                else if (jQuery("#video").is(':checked')){
                    jQuery(".merge_div").show();
                    jQuery(".video_div").show();
                    jQuery(".image_div").hide();
                    jQuery(".note").show();
                    jQuery( "input[name='slider_video[]']" ).rules( "add", {
                        required: true,
                        checkVideoURL: true,
                        messages: {
                            required: "Please enter video URL",
                            checkVideoURL: "Please enter valid video URL"
                        }
                    });
                    jQuery( "input[name='slider_image[]']" ).rules( "remove");
                }
            });
                
            /*
             * End: Slide Valdation
             */

            /*--------- Remove Slide ----------*/
            function remove(remove){
                jQuery(remove).closest('tr.row').remove();
            return false;
            }
        </script>
        <?php
        /*
         * End: Slide js
         */
        
        /*
         * Start: Add/Edit Slide html
         */
        $slidetitles = get_post_meta($post_id, 'slide_title', true);
        $slideimages = get_post_meta($post_id, 'slide_image', true);
        $slidemerges = get_post_meta($post_id, 'slide_merge', true);
        $slidevideos = get_post_meta($post_id, 'slide_video', true);
        $slidetitle = explode(",", $slidetitles);
        $slideimage = explode(",", $slideimages);
        $slidemerge = explode(",", $slidemerges);
        $slidevideo = explode(",", $slidevideos);
        $count_tolal_slide = count($slidetitle);
        if($count_tolal_slide == 0){
            $count_tolal_slide = 1;
        }
        echo '<div class="note" style="display:none;"><b>Note:</b> <span>Accept Youtube and Vimeo videos only.</span></div>
            <table class="agc-input-table slide-table">';
        for ($i=0; $i < $count_tolal_slide; $i++) { 
            ?>
            <tr class="row">
                <td class="order"><span class="toggle-indicator" aria-hidden="true"></span></td>
                <td class="agc-input-wrap">
                    <div id="slider-<?php echo $i; ?>">
                        <table class="widefat agc_input">
                            <tr class="field">
                                <td class="label">Title</td>
                                <td>
                                    <input type="text" name="slider_title[]" id="slider_title_<?php echo $i; ?>" placeholder="Title" value="<?php echo $slidetitle[$i]; ?>">
                                </td>
                            </tr>
                            <tr class="field merge_div" style="display:none;">
                                <td class="label">Data Merge</td>
                                <td>
                                    <input type="number" name="slider_merge[]" class="merge" id="slider_merge_<?php echo $i; ?>" value="<?php echo $slidemerge[$i] ?>">                                     
                                </td>
                            </tr>
                            <tr class="field video_div" style="display:none;">
                                <td class="label">Video URL</td>
                                <td>
                                    <input type="text" name="slider_video[]" class="video" id="slider_video_<?php echo $i; ?>" value="<?php echo $slidevideo[$i] ?>">                                   
                                </td>
                            </tr>
                            <tr class="field image_div">
                                <td class="label">Upload Image</td>
                                <td>
                                    <input type="file" name="slider_image[]" id="slider_image_<?php echo $i; ?>">
                                <?php if ($slideimage[$i] != '') { ?>
                                    <img class="slider_edit_img" src="<?php echo $slideimage[$i]; ?>" width="50px" height="50px">
                                    <input type="hidden" id="hidden_image" name="hidden_image[]" value="<?php echo $slideimage[$i]; ?>">
                                <?php } ?>
                                </td>
                            </tr>                            

                        </table>
                    </div>
                </td>
                <td class="remove">
                    <?php if($i !=0){ ?>
                            <input id="remove" onclick="remove(this);" value="" class="remove-button" type="button">
                     <?php } ?>
                </td>
            </tr>
        <?php  } ?>
        </table>
        <input type="hidden" id="elementcounter" name="elementCounter" value="<?php echo $count_tolal_slide; ?>">

        <!-- End: Add/Edit Slide html -->

        <!-- Start: Html which is used during add new slide -->
        <table>
            <tr class="row new_slider" style="display:none;">
                <td class="order"><span class="toggle-indicator" aria-hidden="true"></span></td>
                <td class="agc-input-wrap">
                    <div id="slider">
                        <table class="widefat agc_input">
                            <tr class="field">
                                <td class="label">Title</td>
                                <td>
                                    <input type="text" name="slider_t" id="slider_title" placeholder="Title" value=""> 
                                </td>
                            </tr>
                            <tr class="field merge_div" style="display:none;">
                                <td class="label">Data Merge</td>
                                <td>
                                    <input type="number" name="slider_m" class="merge" id="slider_merge" value>                                     
                                </td>
                            </tr>
                            <tr class="field video_div" style="display:none;">
                                <td class="label">Video URL</td>
                                <td>
                                    <input type="text" name="slider_v" class="video" id="slider_video" value>                                   
                                </td>
                            </tr>
                            <tr class="field image_div">
                                <td class="label">Upload Image</td>
                                <td>
                                    <input type="file" name="slider_img" id="slider_image" value>                                 
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
                <td class="remove">
                    <input id="remove" onclick="remove(this);" value="" class="remove-button" type="button">
                </td>
            </tr>
            </table>
        <!-- End: Html which is used during add new slide -->
        
        <!-- Add lside button -->
        <ul class="clearfix agc-footer right">
			<li><input id="add" value="Add slide" class="agc-button" type="button"></li>
        </ul>
    <?php
    }

    /*---------------------- Start: Add/edit data save-------------------------*/
    function carousel_update_post($post_id, $post) {
        $slider_type_post = sanitize_text_field($_POST['slider_type']);
        if(isset($_POST['slider_title']) || isset($_POST['slider_merge']) || isset($_POST['slider_video'])){
            $slider_title_post = array_map('sanitize_text_field', wp_unslash($_POST['slider_title']));
            $slider_merge_post = array_map('intval',$_POST['slider_merge']);
            $slider_video_post = array_map('sanitize_text_field', wp_unslash($_POST['slider_video']));
        }
        $slider_type = get_post_meta($post_id, 'slider_type');
        if ($slider_type != '') {
            update_post_meta($post_id, 'slider_type', $slider_type_post);
        } else {
            add_post_meta($post_id, 'slider_type', $slider_type_post);
        }
        $totalslide = count($_FILES['slider_image']['name']);
        for ($i = 0; $i < $totalslide; $i++) {
            $imagefileurl = sanitize_text_field($_FILES['slider_image']['name'][$i]);
            $imagefiletype = sanitize_text_field($_FILES['slider_image']['type'][$i]);
            $imagetempname = sanitize_text_field($_FILES['slider_image']['tmp_name'][$i]);
            $imageerror = sanitize_text_field($_FILES['slider_image']['errro'][$i]);
            $imagesize = sanitize_text_field($_FILES['slider_image']['size'][$i]);
            $uploadedfile = array(
                'name' => $imagefileurl,
                'type' => $imagefiletype,
                'tmp_name' => $imagetempname,
                'error' => $imageerror,
                'size' => $imagesize
            );
            $movefile = wp_handle_upload($uploadedfile, array('test_form' => FALSE));
            if ($imagefileurl != '') {
                $slideimage[] = $movefile['url'];
            } else {
                $slideimage[] = sanitize_text_field($_POST['hidden_image'][$i]);
            }
        }
        if (isset($slider_title_post) || isset($slideimage) || isset($slider_merge_post) || isset($slider_video_post)) {
            $slidetitle = implode(',', $slider_title_post);
            $slideimage = implode(',', $slideimage);
            $slidemerge = implode(',', $slider_merge_post);
            $slidevideo = implode(',', $slider_video_post);
        }

        $slidetitles = get_post_meta($post_id, 'slide_title');
        if ($slidetitles != '') {
            update_post_meta($post_id, 'slide_title', $slidetitle);
        } else {
            add_post_meta($post_id, 'slide_title', $slidetitle);
        }
        $slideimages = get_post_meta($post_id, 'slide_image');
        if ($slideimages != '') {
            update_post_meta($post_id, 'slide_image', $slideimage);
        } else {
            add_post_meta($post_id, 'slide_image', $slideimage);
        }
        $slidemerges = get_post_meta($post_id, 'slide_merge');
        if ($slidemerges != '') {
            update_post_meta($post_id, 'slide_merge', $slidemerge);
        } else {
            add_post_meta($post_id, 'slide_merge', $slidemerge);
        }
        $slidevideos = get_post_meta($post_id, 'slide_video');
        if ($slidevideos != '') {
            update_post_meta($post_id, 'slide_video', $slidevideo);
        } else {
            add_post_meta($post_id, 'slide_video', $slidevideo);
        }
    }
    /*---------------------- End: Add/edit data save-------------------------*/

    // Add Shortcode Columns Heading on Acquiant Owl Carousel Listing
    function custom_columns_heading($columns) {
        $column['cb'] = '<input type="checkbox" />';
        $column['title'] = 'Title';
        $column['shortcode'] = 'shortcode';
        $column['date'] = 'Date';
        return $column;
    }

    // Add Shortcode Columns Value on Acquiant Owl Carousel Listing
    function custom_columns($column, $post_id) {
        switch ($column) {
            case 'shortcode':
                echo '[acquaint-owl-carousel id="' . esc_attr($post_id) . '"]';
                break;
        }
    }

    // Include Owl Carousel Css and js
    function acquaint_owl_carousel_style() {
        wp_enqueue_style('docs_theme', plugins_url('acquaint-owl-carousel') . '/assets/css/docs_theme.min.css');
        wp_enqueue_style('acquaint_owl_carousel', plugins_url('acquaint-owl-carousel') . '/assets/css/acquaint_owl_carousel.min.css');
        wp_enqueue_style('acquaint_owl_theme_default', plugins_url('acquaint-owl-carousel') . '/assets/css/acquaint_owl_theme_default.min.css');
        wp_enqueue_script('acquaint', plugins_url('acquaint-owl-carousel') . '/assets/js/jquery.acquaint.min.js', array('jquery'));
        wp_enqueue_script('acquaint_owl_carousel', plugins_url('acquaint-owl-carousel') . '/assets/js/acquaint_owl_carousel.js', array('jquery'));
         wp_enqueue_style('animate', plugins_url('acquaint-owl-carousel') . '/assets/css/animate.css');
    }

    /*---------------------- Start: Display slider shortcode at front -------------------------*/
    function acquaint_owl_carousel($atts) {
        $id = $atts['id'];
        $slider_type = get_post_meta($id, 'slider_type', true);
        $slider = '<section id="demos">
				      <div class="row">
				        <div class="large-12 columns">';
        if ($slider_type == 'autoplay') {
            $slider .= autoplay($id);
        }
        if ($slider_type == 'animate') {
            $slider .= animate($id);
        }
        if ($slider_type == 'auto_height') {
            $slider .= auto_height($id);
        }
        if ($slider_type == 'auto_width') {
            $slider .= auto_width($id);
        }
        if ($slider_type == 'center') {
            $slider .= center($id);
        }
        if ($slider_type == 'events') {
            $slider .= events($id);
        }
        if ($slider_type == 'lazy_load') {
            $slider .= lazy_load($id);
        }
        if ($slider_type == 'merge') {
            $slider .= merge($id);
        }
        if ($slider_type == 'mousewheel') {
            $slider .= mousewheel($id);
        }
        if ($slider_type == 'responsive') {
            $slider .= responsive($id);
        }
        if ($slider_type == 'right_to_left') {
            $slider .= right_to_left($id);
        }
        if ($slider_type == 'stagepadding') {
            $slider .= stagepadding($id);
        }
        if ($slider_type == 'url_hash_navigation') {
            $slider .= url_hash_navigation($id);
        }
        if ($slider_type == 'video') {
            $slider .= video($id);
        }
        $slider .= '</div>
      				</div>
    			</section>';
        return $slider;
    }
    /*---------------------- End: Display slider shortcode at front -------------------------*/
}

// Create Acquaint_Owl_Carousel_Plugin Method
$acquaint_owl_carousel = new Acquaint_Owl_Carousel_Plugin();

// Auto Play Owl Carousel
function autoplay($id) {
    $slider = '<div class="fadeOut autoplay-slider owl-carousel owl-theme">';
    $slidetitles = get_post_meta($id, 'slide_title', true);
    $slideimages = get_post_meta($id, 'slide_image', true);
    $slidetitle = explode(",", $slidetitles);
    $slideimage = explode(",", $slideimages);
    $s = 0;
    foreach ($slideimage as $image) {
        $slider .= '<img src="' . esc_attr($image) . '" alt="' . esc_attr($slidetitle[$s]) . '">';
        $s++;
    }
    $slider .= '</div>
          <script>
            jQuery(document).ready(function() {
              var owl = jQuery(".autoplay-slider");
              owl.owlCarousel({
                items: 4,
                loop: true,
                margin: 10,
                autoplay: true,
                autoplayTimeout: 1000,
                autoplayHoverPause: true
              });
            })
          </script>';
    return $slider;
}

// Animate Owl Carousel
function animate($id) {
    $slider = '<div class="fadeOut animate-slider owl-carousel owl-theme">';
    $slidetitles = get_post_meta($id, 'slide_title', true);
    $slideimages = get_post_meta($id, 'slide_image', true);
    $slidetitle = explode(",", $slidetitles);
    $slideimage = explode(",", $slideimages);
    $s = 0;
    foreach ($slideimage as $image) {
        $slider .= '<img src="' . esc_attr($image) . '" alt="' . esc_attr($slidetitle[$s]) . '">';
        $s++;
    }
    $plugin_url = plugins_url('acquaint-owl-carousel');
    $slider .= '</div>
          <script>
            jQuery(document).ready(function($) {
              jQuery(".animate-slider").owlCarousel({
                items: 1,
                animateOut: "fadeOut",
                loop: true,
                margin: 10,
              });
            });
          </script>';
    return $slider;
}

// Auto Height Owl Carousel
function auto_height($id) {
    $slider = '<div class="fadeOut auto_height-slider owl-carousel owl-theme">';
    $slidetitles = get_post_meta($id, 'slide_title', true);
    $slideimages = get_post_meta($id, 'slide_image', true);
    $slidetitle = explode(",", $slidetitles);
    $slideimage = explode(",", $slideimages);
    $s = 0;
    foreach ($slideimage as $image) {
        $slider .= '<img src="' . esc_attr($image) . '" alt="' . esc_attr($slidetitle[$s]) . '">';
        $s++;
    }
    $slider .= '</div>
          <script>
            jQuery(document).ready(function() {
              jQuery(".auto_height-slider").owlCarousel({
                items: 1,
                margin: 10,
                autoHeight: true
              });
            })
          </script>';
    return $slider;
}

// Auto Width Owl Carousel
function auto_width($id) {
    $slider = '<div class="auto_width-slider owl-carousel owl-theme">';
    $slidetitles = get_post_meta($id, 'slide_title', true);
    $slideimages = get_post_meta($id, 'slide_image', true);
    $slidetitle = explode(",", $slidetitles);
    $slideimage = explode(",", $slideimages);
    $s = 0;
    foreach ($slideimage as $image) {
        $slider .= '<img src="' . esc_attr($image) . '" alt="' . esc_attr($slidetitle[$s]) . '">';
        $s++;
    }
    $slider .= '</div>
          <script>
            jQuery(document).ready(function() {
              jQuery(".auto_width-slider").owlCarousel({
                margin: 10,
                loop: true,
                autoWidth: true,
                items: 4
              })
            })
          </script>';
    return $slider;
}

// Center Owl Carousel
function center($id) {
    $slider = '<div class="loop center-slider owl-carousel owl-theme">';
    $slidetitles = get_post_meta($id, 'slide_title', true);
    $slideimages = get_post_meta($id, 'slide_image', true);
    $slidetitle = explode(",", $slidetitles);
    $slideimage = explode(",", $slideimages);
    $s = 0;
    foreach ($slideimage as $image) {
        $slider .= '<img src="' . esc_attr($image) . '" alt="' . esc_attr($slidetitle[$s]) . '">';
        $s++;
    }
    $slider .= '</div>
          <script>
            jQuery(document).ready(function($) {
              jQuery(".center-slider").owlCarousel({
                center: true,
                items: 2,
                loop: true,
                margin: 10,
                responsive: {
                  600: {
                    items: 4
                  }
                }
              });
            });
          </script>';
    return $slider;
}

// Events Owl Carousel
function events($id) {
    $slider = '<div class="events-slider owl-carousel owl-theme">';
    $slidetitles = get_post_meta($id, 'slide_title', true);
    $slideimages = get_post_meta($id, 'slide_image', true);
    $slidetitle = explode(",", $slidetitles);
    $slideimage = explode(",", $slideimages);
    $s = 0;
    foreach ($slideimage as $image) {
        $slider .= '<img src="' . esc_attr($image) . '" alt="' . esc_attr($slidetitle[$s]) . '">';
        $s++;
    }
    $slider .= '</div>
          <script>
            jQuery(document).ready(function() {
              var owl = jQuery(".events-slider");
              owl.on("initialize.owl.carousel initialized.owl.carousel " +
                "initialize.owl.carousel initialize.owl.carousel " +
                "resize.owl.carousel resized.owl.carousel " +
                "refresh.owl.carousel refreshed.owl.carousel " +
                "update.owl.carousel updated.owl.carousel " +
                "drag.owl.carousel dragged.owl.carousel " +
                "translate.owl.carousel translated.owl.carousel " +
                "to.owl.carousel changed.owl.carousel",
                function(e) {
                  jQuery("." + e.type)
                    .removeClass("secondary")
                    .addClass("success");
                  window.setTimeout(function() {
                    jQuery("." + e.type)
                      .removeClass("success")
                      .addClass("secondary");
                  }, 500);
                });
              owl.owlCarousel({
                loop: true,
                nav: true,
                lazyLoad: true,
                margin: 10,
                video: true,
                responsive: {
                  0: {
                    items: 1
                  },
                  600: {
                    items: 3
                  },
                  960: {
                    items: 5,
                  },
                  1200: {
                    items: 6
                  }
                }
              });
            });
          </script>';
    return $slider;
}

// Lazy Load Owl Carousel
function lazy_load($id) {
    $slider = '<div class="lazy_load-slider owl-carousel owl-theme">';
    $slidetitles = get_post_meta($id, 'slide_title', true);
    $slideimages = get_post_meta($id, 'slide_image', true);
    $slidetitle = explode(",", $slidetitles);
    $slideimage = explode(",", $slideimages);
    $s = 0;
    foreach ($slideimage as $image) {
        $slider .= '<img src="' . esc_attr($image) . '" alt="' . esc_attr($slidetitle[$s]) . '">';
        $s++;
    }
    $slider .= '</div>
          <script>
            jQuery(document).ready(function($) {
              jQuery(".lazy_load-slider").owlCarousel({
                items: 4,
                lazyLoad: true,
                loop: true,
                margin: 10
              });
            });
          </script>';
    return $slider;
}

// Merge Owl Carousel
function merge($id) {
    $slider = '<div class="merge-slider owl-carousel owl-theme">';
    $slidetitles = get_post_meta($id, 'slide_title', true);
    $slideimages = get_post_meta($id, 'slide_image', true);
    $slidemerges = get_post_meta($id, 'slide_merge', true);
    $slidetitle = explode(",", $slidetitles);
    $slideimage = explode(",", $slideimages);
    $slidemerge = explode(",", $slidemerges);
    $s = 0;
    foreach ($slideimage as $image) {
        if($slidemerge[$s] < 1){
            $slidemerge[$s] = 1;
        }
        $slider .= '<img src="' . esc_attr($image) . '" alt="' . esc_attr($slidetitle[$s]) . '" data-merge="' . esc_attr($slidemerge[$s]) . '" style="height:160px;">';
        $s++;
    }
    $slider .= '</div>
          <script>
            jQuery(document).ready(function() {
              jQuery(".merge-slider").owlCarousel({
                items: 5,
                loop: true,
                margin: 10,
                merge: true,
                responsive: {
                  678: {
                    mergeFit: true
                  },
                  1000: {
                    mergeFit: false
                  }
                }
              });
            })
          </script>';
    return $slider;
}

// Mousewheel Owl Carousel
function mousewheel($id) {
    $slider = '<div class="mousewheel-slider owl-carousel owl-theme">';
    $slidetitles = get_post_meta($id, 'slide_title', true);
    $slideimages = get_post_meta($id, 'slide_image', true);
    $slidetitle = explode(",", $slidetitles);
    $slideimage = explode(",", $slideimages);
    $s = 0;
    foreach ($slideimage as $image) {
        $slider .= '<img src="' . esc_attr($image) . '" alt="' . esc_attr($slidetitle[$s]) . '">';
        $s++;
    }
    $slider .= '</div>
          <script>
            jQuery(document).ready(function() {
              var owl = jQuery(".mousewheel-slider");
              owl.owlCarousel({
                loop: true,
                nav: true,
                margin: 10,
                responsive: {
                  0: {
                    items: 1
                  },
                  600: {
                    items: 3
                  },
                  960: {
                    items: 5
                  },
                  1200: {
                    items: 6
                  }
                }
              });
              owl.on("mousewheel", ".owl-stage", function(e) {
                if (e.deltaY > 0) {
                  owl.trigger("next.owl");
                } else {
                  owl.trigger("prev.owl");
                }
                e.preventDefault();
              });
            })
          </script>';
    return $slider;
}

// Responsive Owl Carousel
function responsive($id) {
    $slider = '<div class="responsive-slider owl-carousel owl-theme">';
    $slidetitles = get_post_meta($id, 'slide_title', true);
    $slideimages = get_post_meta($id, 'slide_image', true);
    $slidetitle = explode(",", $slidetitles);
    $slideimage = explode(",", $slideimages);
    $s = 0;
    foreach ($slideimage as $image) {
        $slider .= '<img src="' . esc_attr($image) . '" alt="' . esc_attr($slidetitle[$s]) . '">';
        $s++;
    }
    $slider .= '</div>
          <script>
            jQuery(document).ready(function() {
              jQuery(".responsive-slider").owlCarousel({
                loop: true,
                margin: 10,
                responsiveClass: true,
                responsive: {
                  0: {
                    items: 1,
                    nav: true
                  },
                  600: {
                    items: 3,
                    nav: false
                  },
                  1000: {
                    items: 5,
                    nav: true,
                    loop: false,
                    margin: 20
                  }
                }
              })
            })
          </script>';
    return $slider;
}

// Right to Left Owl Carousel
function right_to_left($id) {
    $slider = '<div class="right_to_left-slider owl-carousel owl-theme">';
    $slidetitles = get_post_meta($id, 'slide_title', true);
    $slideimages = get_post_meta($id, 'slide_image', true);
    $slidetitle = explode(",", $slidetitles);
    $slideimage = explode(",", $slideimages);
    $s = 0;
    foreach ($slideimage as $image) {
        $slider .= '<img src="' . esc_attr($image) . '" alt="' . esc_attr($slidetitle[$s]) . '">';
        $s++;
    }
    $slider .= '</div>
          <script>
            jQuery(document).ready(function() {
              var owl = jQuery(".right_to_left-slider");
              owl.owlCarousel({
                rtl: true,
                margin: 10,
                nav: true,
                loop: true,
                responsive: {
                  0: {
                    items: 1
                  },
                  600: {
                    items: 3
                  },
                  1000: {
                    items: 5
                  }
                }
              })
            })
          </script>';
    return $slider;
}

// Stagepadding Owl Carousel
function stagepadding($id) {
    $slider = '<div class="stagepadding-slider owl-carousel owl-theme">';
    $slidetitles = get_post_meta($id, 'slide_title', true);
    $slideimages = get_post_meta($id, 'slide_image', true);
    $slidetitle = explode(",", $slidetitles);
    $slideimage = explode(",", $slideimages);
    $s = 0;
    foreach ($slideimage as $image) {
        $slider .= '<img src="' . esc_attr($image) . '" alt="' . esc_attr($slidetitle[$s]) . '">';
        $s++;
    }
    $slider .= '</div>
          <script>
            jQuery(document).ready(function() {
              var owl = jQuery(".stagepadding-slider");
              owl.owlCarousel({
                stagePadding: 50,
                margin: 10,
                nav: true,
                loop: true,
                responsive: {
                  0: {
                    items: 1
                  },
                  600: {
                    items: 3
                  },
                  1000: {
                    items: 5
                  }
                }
              })
            })
          </script>';
    return $slider;
}

// URL Hash Navigation Owl Carousel
function url_hash_navigation($id) {
    $slider = '<div class="url_hash_navigation-slider owl-carousel owl-theme">';
    $slidetitles = get_post_meta($id, 'slide_title', true);
    $slideimages = get_post_meta($id, 'slide_image', true);
    $slidetitle = explode(",", $slidetitles);
    $slideimage = explode(",", $slideimages);
    $s = 0;
    foreach ($slideimage as $image) {
        $slider .= '<img src="' . esc_attr($image) . '" alt="' . esc_attr($slidetitle[$s]) . '" data-hash="' . esc_attr($s) . '">';
        $s++;
    }
    $slider .= '</div>';
    $a = 0;
    $al = 0;
    foreach ($slideimage as $image) {
        if ($a == $al) {
            $slider .= '<a class="button secondary url" href="#' . $a . '">' . $a . '</a>';
            $al += 3;
        }
        $a++;
    }
    $slider .= '<script>
            jQuery(document).ready(function() {
              jQuery(".url_hash_navigation-slider").owlCarousel({
                items: 3,
                loop: false,
                center: true,
                margin: 10,
                callbacks: true,
                URLhashListener: true,
                autoplayHoverPause: true,
                startPosition: "URLHash"
              });
            })
          </script>';
    return $slider;
}

// Video Owl Carousel
function video($id) {
    $slider = '<div class="video-slider owl-carousel owl-theme">';
    $slidetitles = get_post_meta($id, 'slide_title', true);
    $slidevideos = get_post_meta($id, 'slide_video', true);
    $slidemerges = get_post_meta($id, 'slide_merge', true);
    $slidetitle = explode(",", $slidetitles);
    $slidevideo = explode(",", $slidevideos);
    $slidemerge = explode(",", $slidemerges);
    $s = 0;
    foreach ($slidevideo as $video) {
        if($slidemerge[$s] < 1){
            $slidemerge[$s] = 1;
        }
        $slider .= '<div class="item-video" data-merge="' . esc_attr($slidemerge[$s]) . '">
				            	<a class="owl-video" title="' . esc_attr($slidetitle[$s]) . '" href="' . esc_url($video) . '"></a> 
				            </div>';
        $s++;
    }
    $slider .= '</div>';
    $slider .= '<script>
            jQuery(document).ready(function() {
              jQuery(".video-slider").owlCarousel({
                items: 1,
                merge: true,
                loop: true,
                margin: 10,
                video: true,
                lazyLoad: true,
                center: true,
                responsive: {
                  480: {
                    items: 0
                  },
                  600: {
                    items: 1
                  }
                }
              })
            })
          </script>';
    return $slider;
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_acquaint_owl_carousel() {

    $plugin = new Acquaint_Owl_Carousel();
    $plugin->run();
}

run_acquaint_owl_carousel();
