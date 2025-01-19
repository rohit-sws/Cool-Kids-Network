<?php
/*
Plugin Name: Cool Kids API
Description: API for role assignment in Cool Kids Network.
Version: 1.0
Author: Rohit Vakhariya
*/

if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}


add_action('rest_api_init', function () {
    register_rest_route('cool-kids/v1', '/assign-role', [
        'methods' => 'POST',
        'callback' => 'assign_user_role',
        'permission_callback' => function () {
        return current_user_can('manage_options'); // Only admins can access
        //return true;
        }
    ]);
});

function assign_user_role(WP_REST_Request $request) {
    // Get parameters
    $email = sanitize_email($request->get_param('email'));
    $role = sanitize_text_field($request->get_param('role'));
    $first_name = sanitize_text_field($request->get_param('first_name'));
    $last_name = sanitize_text_field($request->get_param('last_name'));

    // Validate role
    $valid_roles = ['Cool Kid', 'Cooler Kid', 'Coolest Kid'];
    if (!in_array($role, $valid_roles)) {
        return new WP_Error('invalid_role', 'Invalid role specified.', ['status' => 400]);
    }

    // Find user by email or name
    if ($email) {
        $user = get_user_by('email', $email);
        if (!$user) {
            return new WP_Error('user_not_found', 'User not found.', ['status' => 404]);
        }
    }elseif ($first_name && $last_name) {
        $users = get_users([
            'meta_query' => [
                ['key' => 'first_name', 'value' => $first_name],
                ['key' => 'last_name', 'value' => $last_name],
            ]
        ]);
        $user = !empty($users) ? $users[0] : null;
    } else {
        return new WP_Error('missing_params', 'Email or first and last name must be provided.', ['status' => 400]);
    }
      $user_id = $user->ID;
    if($user_id!=""){  
      // Assign role
      update_user_meta($user_id, 'role',$role);
      return ['success' => true, 'message' => "Role '{$role}' assigned to user {$user->user_email}."];
    } else{  
        return ['success' => true, 'message' => "User not found."];

     }

}
