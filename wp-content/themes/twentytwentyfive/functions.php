<?php
    /**
     * Twenty Twenty-Five functions and definitions.
     *
     * @link https://developer.wordpress.org/themes/basics/theme-functions/
     *
     * @package WordPress
     * @subpackage Twenty_Twenty_Five
     * @since Twenty Twenty-Five 1.0
     */

    // Adds theme support for post formats.
    if (! function_exists('twentytwentyfive_post_format_setup')):
    /**
     * Adds theme support for post formats.
     *
     * @since Twenty Twenty-Five 1.0
     *
     * @return void
     */

        function twentytwentyfive_post_format_setup()
    {
            add_theme_support('post-formats', ['aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video']);
        }
    endif;
    add_action('after_setup_theme', 'twentytwentyfive_post_format_setup');

    // Enqueues editor-style.css in the editors.
    if (! function_exists('twentytwentyfive_editor_style')):
    /**
     * Enqueues editor-style.css in the editors.
     *
     * @since Twenty Twenty-Five 1.0
     *
     * @return void
     */

        function twentytwentyfive_editor_style()
    {
            add_editor_style(get_parent_theme_file_uri('assets/css/editor-style.css'));
        }
    endif;
    add_action('after_setup_theme', 'twentytwentyfive_editor_style');

    // Enqueues style.css on the front.
    if (! function_exists('twentytwentyfive_enqueue_styles')):
    /**
     * Enqueues style.css on the front.
     *
     * @since Twenty Twenty-Five 1.0
     *
     * @return void
     */

        function twentytwentyfive_enqueue_styles()
    {
            wp_enqueue_style(
                'twentytwentyfive-style',
                get_parent_theme_file_uri('style.css'),
                [],
                wp_get_theme()->get('Version')
            );
        }
    endif;
    add_action('wp_enqueue_scripts', 'twentytwentyfive_enqueue_styles');

    // Registers custom block styles.
    if (! function_exists('twentytwentyfive_block_styles')):
    /**
     * Registers custom block styles.
     *
     * @since Twenty Twenty-Five 1.0
     *
     * @return void
     */

        function twentytwentyfive_block_styles()
    {
            register_block_style(
                'core/list',
                [
                    'name'         => 'checkmark-list',
                    'label'        => __('Checkmark', 'twentytwentyfive'),
                    'inline_style' => '
					ul.is-style-checkmark-list {
						list-style-type: "\2713";
					}

					ul.is-style-checkmark-list li {
						padding-inline-start: 1ch;
					}',
                ]
            );
        }
    endif;
    add_action('init', 'twentytwentyfive_block_styles');

    // Registers pattern categories.
    if (! function_exists('twentytwentyfive_pattern_categories')):
    /**
     * Registers pattern categories.
     *
     * @since Twenty Twenty-Five 1.0
     *
     * @return void
     */

        function twentytwentyfive_pattern_categories()
    {

            register_block_pattern_category(
                'twentytwentyfive_page',
                [
                    'label'       => __('Pages', 'twentytwentyfive'),
                    'description' => __('A collection of full page layouts.', 'twentytwentyfive'),
                ]
            );

            register_block_pattern_category(
                'twentytwentyfive_post-format',
                [
                    'label'       => __('Post formats', 'twentytwentyfive'),
                    'description' => __('A collection of post format patterns.', 'twentytwentyfive'),
                ]
            );
        }
    endif;
    add_action('init', 'twentytwentyfive_pattern_categories');

    // Registers block binding sources.
    if (! function_exists('twentytwentyfive_register_block_bindings')):
    /**
     * Registers the post format block binding source.
     *
     * @since Twenty Twenty-Five 1.0
     *
     * @return void
     */

        function twentytwentyfive_register_block_bindings()
    {
            register_block_bindings_source(
                'twentytwentyfive/format',
                [
                    'label'              => _x('Post format name', 'Label for the block binding placeholder in the editor', 'twentytwentyfive'),
                    'get_value_callback' => 'twentytwentyfive_format_binding',
                ]
            );
        }
    endif;
    add_action('init', 'twentytwentyfive_register_block_bindings');

    // Registers block binding callback function for the post format name.
    if (! function_exists('twentytwentyfive_format_binding')):
    /**
     * Callback function for the post format name block binding source.
     *
     * @since Twenty Twenty-Five 1.0
     *
     * @return string|void Post format name, or nothing if the format is 'standard'.
     */

        function twentytwentyfive_format_binding()
    {
            $post_format_slug = get_post_format();

            if ($post_format_slug && 'standard' !== $post_format_slug) {
                return get_post_format_string($post_format_slug);
            }
        }
    endif;
 
	// Check if the function already exists to prevent redeclaration
	if (!function_exists('custom_registration_form_with_ajax')):
	
		// Function to render the custom registration form with AJAX
		function custom_registration_form_with_ajax()
		{
			ob_start(); ?>

			<!-- HTML Form for Custom Registration -->
            <p id="registration-message" class=""></p>
<form id="custom-registration-form" method="POST">
  <label for="email">Email Address:</label>
  <input type="email" name="email" id="email" required><br />
  <br />
  <button type="button" id="register-button" class="wp-element-button">
    Confirm
  </button>
</form>
			
			
	
			<script type="text/javascript">
			jQuery(document).ready(function($) {
				// Function to validate the email address format
				function validateEmail(email) {
					const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
					return emailRegex.test(email);
				}
	
				// Event listener for the "Confirm" button
				$('#register-button').on('click', function() {
					const email = $('#email').val();
					const messageElement = $('#registration-message');
	
					// Validate email input
					if (!email || !validateEmail(email)) {
						messageElement.text('Please enter a valid email address.');
						messageElement.attr('class','error');
						return;
					}
	
					// Send AJAX request to process registration
					$.ajax({
						url: '<?php echo admin_url('admin-ajax.php'); ?>',
						type: 'POST',
						data: {
							action: 'register_user_and_character', // WordPress AJAX action
							email: email
						},
						beforeSend: function() {
							messageElement.text('Registering...');
						},
						success: function(response) {
							if (response.success) {
								messageElement.text('Registration successful!');
						        messageElement.attr('class','success');

							} else {
								messageElement.text(response.data || 'An error occurred.');
						        messageElement.attr('class','error');
								
							}
						},
						error: function() {
							messageElement.text('An unexpected error occurred. Please try again. If the issue persists, use a different email as this one may already be registered.');
						}
					});
				});
			});
			</script>
	
			<?php return ob_get_clean();
		}
	
		// Register shortcode for the custom registration form
		add_shortcode('custom_registration_form', 'custom_registration_form_with_ajax');
	
	endif;
	
	// Check if the function already exists to prevent redeclaration
	if (!function_exists('register_user_and_character')):
	
		// Function to handle AJAX user registration and character generation
		function register_user_and_character()
		{
			// Ensure it's a POST request and email is set
			if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
				// Sanitize the email input
				$email = sanitize_email($_POST['email']);
	
				// Check if the email is already registered
				if (email_exists($email)) {
					return wp_send_json_error('Email already registered.', 400);
				}
	
				// Create a new user with a randomly generated password
				$user_id = wp_create_user($email, wp_generate_password(), $email);
	
				// Handle errors during user creation
				if (is_wp_error($user_id)) {
					return wp_send_json_error($user_id->get_error_message(), 400);
				}
	
				// Fetch random user data from the external API
				$response = wp_remote_get('https://randomuser.me/api/');
				$data     = json_decode(wp_remote_retrieve_body($response), true);
	
				// If data is retrieved successfully, update user metadata
				if (!empty($data['results'][0])) {
					$character = $data['results'][0];
					update_user_meta($user_id, 'first_name', $character['name']['first']);
					update_user_meta($user_id, 'last_name', $character['name']['last']);
					update_user_meta($user_id, 'country', $character['location']['country']);
					update_user_meta($user_id, 'role', 'Cool Kid');
				}
	
				// Send success response
				wp_send_json_success('User registered and character generated.', 200);
			}
		}
	
		// Register AJAX actions for logged-in and non-logged-in users
		add_action('wp_ajax_register_user_and_character', 'register_user_and_character');
		add_action('wp_ajax_nopriv_register_user_and_character', 'register_user_and_character');
	
	endif;



 if (!function_exists('cool_kids_login_form')):   
    // Shortcode for login form
function cool_kids_login_form() {
    // Check if the user is already logged in
    if (is_user_logged_in()) {
        return '<p>You are already logged in. <a href="' . esc_url(wp_logout_url(home_url())) . '">Logout</a></p>';
    }

    // HTML for the login form

    // Handle form submission
    if (isset($_POST['login_submit'])) {
        $email = sanitize_email($_POST['email']);
        $user = get_user_by('email', $email);

        if ($user) {
            // Log in the user
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID);
            wp_redirect('./index.php/view-my-data/'); // Redirect to homepage after login
            exit;
        } 
    }

    $output .= '
    <form id="custom-registration-form"  method="post" action="">';
     $output .= '<label for="email">Email:</label>
        <input type="email" id="email" name="email" required>';
        if (!$user && isset($_POST['login_submit'])) {
            $output .= '<div class="error login_class">Invalid email address. Please try again.</div><br>';
        }
        $output .= '<input type="submit" name="login_submit" value="Login" id="login-button" class="wp-element-button">
 
    </form>';


    return $output;
}

// Register shortcode [cool_kids_login_form]
add_shortcode('cool_kids_login_form', 'cool_kids_login_form');
endif;




if (!function_exists('get_logged_in_user_data')):   

function get_logged_in_user_data() {
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        
        
       # echo $user_id;
        $character_data = get_user_meta($user_id);
        $user_data = get_userdata($user_id);


       # echo "<pre>";
       # print_R($character_data);exit;

        if ($character_data) {
        $user_data = get_userdata($user_id);
            if($user_data){
                $character_data['email'][0]=$user_data->user_email;
            }
            return $character_data; // Array containing first_name, last_name, country, email, role
        } else {
            return ['error' => 'Character data not found.'];
        }
    } else {
        return ['error' => 'User not logged in.'];
    }
}

add_shortcode('user_character_data', function () {
    $data = get_logged_in_user_data();
    if (isset($data['error'])) {
        return $data['error'];
    }
 
    $html_data="<div class='get_user_meta'>
     <p> <b>First Name:</b>".$data['first_name'][0]."</p>
     <p> <b>Last Name:</b>".$data['last_name'][0]."</p>
     <p> <b>Email:</b>".$data['email'][0]."</p>
    
     <p> <b>Country:</b>".$data['country'][0]."</p>
     <p> <b>Role:</b>".$data['role'][0]."</p>
     
    </div>";


return $html_data;
});
endif;



if (!function_exists('get_all_users_name_country')):   


function get_all_users_name_country() {
    
        if (is_user_logged_in()) {
            $user_id = get_current_user_id();
            $character_data = get_user_meta($user_id);
            $role=$character_data['role'][0];

            if ($role=='Coole Kid') {
                return ['error' => 'Access denied.'];
            }
        
        }    

    $users = get_users(['fields' => ['ID']]);

    $data = [];

    foreach ($users as $user) {
        $user_meta = get_user_meta($user->ID);
         
        $data[] = [
            'name' => "{$user_meta['first_name'][0]} {$user_meta['last_name'][0]}",
            'country' => $user_meta['country'][0]
        ];
    }

    return $data;
}

add_shortcode('all_users_name_country', function () {
    $users_data = get_all_users_name_country();
    if (isset($users_data['error'])) {
        return $users_data['error'];
    }

    $output = '<ul>';
    foreach ($users_data as $user) {
         
            if(trim($user['name'])!=""){ 
                $output .= "<li>Name: {$user['name']}, Country: {$user['country']}</li>";
            }
    }
    $output .= '</ul>';

    return $output;
});

endif;

	?>
	