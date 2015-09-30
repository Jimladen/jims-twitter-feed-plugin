<?php
/*
Plugin Name: Jims Twitter Feed
Plugin URI:  https://github.com/Jimladen/jims-twitter-feed-plugin
Description: Creates a very simple twitter feed widget that can be dropped into a widget area
Version:     1.0
Author:      Jim Sheen
Author URI:  https://github.com/Jimladen
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: jims-twitter-feed-plugin
*/

add_action('admin_menu', 'jtp_add_admin_menu');
add_action('admin_init', 'jtp_settings_init');

function jtp_add_admin_menu() {
    
    add_options_page('Jims Twitter Plugin ', 'Jims Twitter Plugin ', 'manage_options', 'jims_twitter_plugin_', 'jtp_options_page');
}

function jims_twitter_plugin__options_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    // echo '<div class="wrap">';
    // echo '<p>Here is where the form would go if I actually had options.</p>';
    // echo '</div>';
}

function jtp_settings_init() {
    
    register_setting('pluginPage', 'jtp_settings');
    
    add_settings_section('jtp_pluginPage_section', __('Please fill in the details below from your Twitter API account', 'jims-twitter-plugin'), 'jtp_settings_section_callback', 'pluginPage');
    
    add_settings_field('jtp_text_field_0', __('Consumer Key', 'jims-twitter-plugin'), 'jtp_text_field_0_render', 'pluginPage', 'jtp_pluginPage_section');
    
    add_settings_field('jtp_text_field_1', __('Consumer Secret', 'jims-twitter-plugin'), 'jtp_text_field_1_render', 'pluginPage', 'jtp_pluginPage_section');
    
    add_settings_field('jtp_text_field_2', __('User Token', 'jims-twitter-plugin'), 'jtp_text_field_2_render', 'pluginPage', 'jtp_pluginPage_section');
    
    add_settings_field('jtp_text_field_3', __('User Secret', 'jims-twitter-plugin'), 'jtp_text_field_3_render', 'pluginPage', 'jtp_pluginPage_section');
}

function jtp_text_field_0_render() {
    
    $options = get_option('jtp_settings');
?>
    <input type='text' name='jtp_settings[jtp_text_field_0]' class="regular-text" value='<?php
    echo $options['jtp_text_field_0']; ?>'>
    <?php
}

function jtp_text_field_1_render() {
    
    $options = get_option('jtp_settings');
?>
    <input type='text' name='jtp_settings[jtp_text_field_1]' class="regular-text" value='<?php
    echo $options['jtp_text_field_1']; ?>'>
    <?php
}

function jtp_text_field_2_render() {
    
    $options = get_option('jtp_settings');
?>
    <input type='text' name='jtp_settings[jtp_text_field_2]' class="regular-text" value='<?php
    echo $options['jtp_text_field_2']; ?>'>
    <?php
}

function jtp_text_field_3_render() {
    
    $options = get_option('jtp_settings');
?>
    <input type='text' name='jtp_settings[jtp_text_field_3]' class="regular-text" value='<?php
    echo $options['jtp_text_field_3']; ?>'>
    <?php
}

function jtp_settings_section_callback() {
    
    // echo __('This section description', 'jims-twitter-plugin');
}

function jtp_options_page() {
?>
    <form action='options.php' method='post'>
        
        <h2>Jims Twitter Plugin </h2>
        <?php
?>
        
        <?php
    settings_fields('pluginPage');
    do_settings_sections('pluginPage');
    submit_button();
?>
        
    </form>
    <?php
}

/**
 * Adds Foo_Widget widget.
 */
class Foo_Widget extends WP_Widget
{
    
    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct('jpt_widget',
        
        // Base ID
        __('Jims Twitter Feed', 'text_domain'),
        
        // Name
        array('description' => __('Jims Twitter Feed Plugin', 'text_domain'),)
        
        // Args
        );
    }
    
    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        
        wp_enqueue_script('jquery');
        wp_register_script('myplugin', plugins_url('jquery.tweet.js', __FILE__), array('jquery'), '', true);
        wp_enqueue_script('myplugin');
        
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        
        // echo __('Hello, World!', 'text_domain');
        
        $directory = $dir = plugin_dir_url( __FILE__ );
        $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
        $textarea = $instance['textarea'];
        $username = $instance['username'];
        $post_count = $instance['post_count'];
?>
        <script type="text/javascript">
        (function($) {

    $(document).ready(function() {

        $('.twitter-content').tweet({
            username: "DJMSolutions",
            modpath: '<?php echo $directory ?>index.php',
            count: '<?php echo $post_count;?>' ,
            loading_text: 'loading twitter feed...',
            avatar_size: 48,
            template: "{avatar}<a class='username' href='http://twitter.com/<?php
         echo $username;
         ?>'><?php
          echo $username;
         ?></a>{time}{join}{text}<div class='clear'></div>"
            });
        });
    })(jQuery);

</script>
<?php
        echo '<div class="twitter-content"></div>';
        
        echo $args['after_widget'];
    }
    
    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance) {
        if ($instance) {
            $title = esc_attr($instance['title']);
            $textarea = $instance['textarea'];
            $username = $instance['username'];
            $post_count = $instance['post_count'];
        } 
        else {
            $title = '';
            $textarea = '';
            $username = '';
            $post_count = '';
        }
?>
        <p>
        <label for="<?php
        echo $this->get_field_id('title'); ?>">Title:
    <input class="widefat" id="<?php
        echo $this->get_field_id('title'); ?>" name="<?php
        echo $this->get_field_name('title'); ?>" type="text" value="<?php
        echo esc_attr($title); ?>" />
 
  <label for="<?php
        echo $this->get_field_id('username'); ?>">Username:
    <input class="widefat" id="<?php
        echo $this->get_field_id('username'); ?>" name="<?php
        echo $this->get_field_name('username'); ?>" type="text" value="<?php
        echo esc_attr($username); ?>" />
    
    
     <label for="<?php
        echo $this->get_field_id('post_count'); ?>">Post Count:
    <input type="number" class="widefat" id="<?php
        echo $this->get_field_id('post_count'); ?>" name="<?php
        echo $this->get_field_name('post_count'); ?>" type="text" value="<?php
        echo esc_attr($post_count); ?>" />
    
    
        <?php
    }
    
    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance = $old_instance;
        
        // fields
        $instance['title'] = $new_instance['title'];
        $instance['textarea'] = $new_instance['textarea'];
        $instance['username'] = $new_instance['username'];
        $instance['post_count'] = $new_instance['post_count'];
        return $instance;
    }
}

// class Foo_Widget

// register Foo_Widget widget
function register_foo_widget() {
    register_widget('Foo_Widget');
}
add_action('widgets_init', 'register_foo_widget');
?>