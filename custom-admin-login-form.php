<?php
/*
Plugin Name: Admin login custom form
Plugin URI: 
Description: Custom plugin for customizing admin login form with custom CSS
Author: Vaibhav Gangrade
Version: 1.0.0
Author URI: https://profiles.wordpress.org/vaibhav31gangrade/
*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly
//Defining contants
define("PLUGIN_DIR_PATH", plugin_dir_path(__FILE__));
define("PLUGIN_URL", plugins_url());
define("SITE_URL", site_url());

if (!function_exists('CDLF_admin_custom_login_form'))
{

    function CDLF_admin_custom_login_form()
    {

        $form_background_img_url = get_option('form_background_img_url');
        $form_background_color = get_option('form_background_color');

?>
    <style type="text/css">
    	/*Adding our custom CSS for admin login area*/
    	#loginform{
    		margin-top: 50%;
    		margin: 0, auto;
    		color:white;
    		background-color:rgba(0,0,0,0.7);
    		width: 600px;
    }
    body{
    	
    	background-image: url('<?php echo esc_html($form_background_img_url); ?>');
    	background-color: <?php echo esc_html($form_background_color); ?>;
    	background-repeat: no-repeat;
    	background-size: cover;
    }

    /*Applying CSS on text message section*/
   .message, .login .success {
    border-left: 4px solid #72aee6;
    padding: 12px;
    margin-left: 0;
    margin-bottom: 20px;
    background-color: #fff;
    box-shadow: 0 1px 1px 0 rgb(0 0 0 / 10%);
    word-wrap: break-word;
    font-size: 20px;
    text-align: center;
}

/*Applying custom logo*/
.login h1 a {

	/*background-image: url('');*/
}
/*Applying CSS on login button*/
#wp-submit{
	width: 150px;
}

#nav a{
	background: #135e96;
    border-color: #135e96;
    box-shadow: none;
    color: #fff!important;
    padding: 12px;
    width: auto;
}
#backtoblog a{
	background: #135e96;
    border-color: #135e96;
    box-shadow: none;
    color: #fff!important;
    padding: 5px;
    width: 70%;
}
    
</style>
    <?php
    }

    add_action('login_head', 'CDLF_admin_custom_login_form');

    //We'll key on the slug for the settings page so set it here so it can be used in various places
    define('MY_PLUGIN_SLUG', 'my-plugin-slug');

    //Register a callback for our specific plugin's actions
    add_filter('plugin_action_links_' . plugin_basename(__FILE__) , 'CDLF_my_plugin_action_links');
    function CDLF_my_plugin_action_links($links)
    {
        $links[] = '<a href="' . menu_page_url(MY_PLUGIN_SLUG, false) . '">Settings</a>';
        return $links;
    }

    //Create a normal admin menu
    add_action('admin_menu', 'CDLF_register_settings');
    function CDLF_register_settings()
    {
        add_options_page('My Plugin Settings', 'My Plugin Settings', 'manage_options', MY_PLUGIN_SLUG, 'CDLF_my_plugin_settings_page');

        //We just want to URL to be valid so now we're going to remove the item from the menu
        //The code below walks the global menu and removes our specific item by its slug
        global $submenu;
        if (array_key_exists('options-general.php', $submenu))
        {
            foreach ($submenu['options-general.php'] as $k => $v)
            {
                if (MY_PLUGIN_SLUG === $v[2])
                {
                    unset($submenu['options-general.php'][$k]);
                }
            }
        }
    }

    //This is our plugins settings page
    function CDLF_my_plugin_settings_page()
    {

        $form_background_img_url = get_option('form_background_img_url');
        $form_background_color = get_option('form_background_color');

?>
	<!--Plugin setting form starts here-->
	<div class="settings_Section">
		<h4>Login form settings:</h4>
		<form action="" method="POST">
			<label>Form background image:</label>
			<input type="text" name="form_background_img_url" id="form_background_img_url" placeholder="Enter background image url" value="<?php echo esc_attr($form_background_img_url); ?>">
			<br><br>
			<label>Form background color:</label>
			<input type="color" name="form_background_color" placeholder="Enter background color" id="form_background_color" value="<?php echo esc_attr($form_background_color); ?>">
			<br><br>
			<?php
        if (!empty($form_background_img_url) && !empty($form_background_color))
        { ?>	<input type="hidden" name="_nonce" value="<?php echo wp_create_nonce('update-settings') ?>">
				<input type="submit" name="update_setting" value="Update Setting">
			<?php
        }
        else
        { ?>
				<input type="submit" name="save_setting" value="Save Setting">
			<?php
        } ?>
			
			
		</form>
	</div>
	<!--Plugin setting form ends here-->
    
<?php
		//Inserting plugin setting data
        if (isset($_POST['save_setting']))
        {

            $form_background_img_url = sanitize_text_field($_POST['form_background_img_url']);
            $form_background_color = sanitize_hex_color($_POST['form_background_color']);
            if (!empty($form_background_img_url) && !empty($form_background_color))
            {
                add_option('form_background_img_url', $form_background_img_url, '', 'yes');
                add_option('form_background_color', $form_background_color, '', 'yes');
                //echo "Saved Successfully!!";
                
            }

        }
        //Updating plugin setting data
        if (isset($_POST['update_setting']))
        {
            if (wp_verify_nonce($_POST['_nonce'], 'update-settings'))
            {
                $form_background_img_url = sanitize_text_field($_POST['form_background_img_url']);
                $form_background_color = sanitize_hex_color($_POST['form_background_color']);
                if (!empty($form_background_img_url) && !empty($form_background_color))
                {
                    update_option('form_background_img_url', $form_background_img_url, '', 'yes');
                    update_option('form_background_color', $form_background_color, '', 'yes');
                    //echo "Updated Successfully!!";
                    
                }
            }

        }

    }

}

?>
