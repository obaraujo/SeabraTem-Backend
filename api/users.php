<?php

class ST_Users
{
  function __construct()
  {
    add_action('rest_api_init', [$this, 'register_routes_api_user']);
  }

  public function register_routes_api_user()
  {
    register_rest_route('api/v1', '/user/create', [
      'methods' => WP_REST_Server::CREATABLE,
      'callback' =>  [$this, 'create']
    ]);
    register_rest_route('api/v1', '/user', [
      'methods' => 'PUT',
      'callback' =>  [$this, 'update']
    ]);
    register_rest_route('api/v1', '/user', [
      'methods' => 'GET',
      'callback' =>  [$this, 'get']
    ]);
    register_rest_route('api/v1', '/user', [
      'methods' => 'DELETE',
      'callback' =>  [$this, 'delete']
    ]);
  }

  public function create($request)
  {
    if (!is_valid_origin($request)) {
      return message_access_not_allowed();
    }

    $firstname = sanitize_text_field($request['first_name']);
    $lastname = sanitize_text_field($request['last_name']);
    $email = sanitize_email($request['user_email']);
    $username = sanitize_text_field($request['username']);
    $password = $request['user_pass'];

    $id_user = wp_create_user($username, $password, $email);

    if (!is_numeric($id_user)) {
      return rest_ensure_response($id_user);
    }
    $userdata = [
      'ID' => $id_user,
      'first_name' => $firstname,
      'last_name' => $lastname,
      'display_name' => $firstname . " " . $lastname,
      'role' => 'subscriber'
    ];
    wp_update_user($userdata);
  }

  public function update($request)
  {
    if (!is_valid_origin($request)) {
      return message_access_not_allowed();
    }

    $user = wp_get_current_user();
    $id_user = $user->ID;

    $firstname = sanitize_text_field($request['first_name']);
    $lastname = sanitize_text_field($request['last_name']);
    $nickname = sanitize_text_field($request['nickname']);
    $email = sanitize_email($request['user_email']);
    $password = $request['user_pass'];
    $description = sanitize_text_field($request['description']);

    $userdata = [
      'ID' => $id_user,
      'first_name' => $firstname,
      'last_name' => $lastname,
      'nickname' => $nickname,
      'user_email' => $email,
      'user_pass' => $password,
      'description' => $description,
    ];
    wp_update_user($userdata);

    return rest_ensure_response($firstname);
  }

  public function get($request)
  {
    if (!is_valid_origin($request)) {
      return message_access_not_allowed();
    }

    $user = wp_get_current_user();
    $usermeta = get_user_meta($user->ID);

    $response = [
      'ID' => $user->ID,
      'first_name' => $usermeta['first_name'],
      'last_name' => $usermeta['last_name'],
      'nickname' => $usermeta['nickname'],
      'description' => $usermeta['description'],
      'user_email' => $user->user_email
    ];

    return rest_ensure_response($response);
  }

  public function delete($request)
  {
    if (!is_valid_origin($request)) {
      return message_access_not_allowed();
    }

    $user = wp_get_current_user();
    $id_user = $user->ID;
    require_once(ABSPATH . 'wp-admin/includes/user.php');
    $result = wp_delete_user($id_user);
    return rest_ensure_response($id_user);
  }
}
