<?php

class ST_users
{
  function __construct()
  {
    add_action('rest_api_init', [$this, 'register_routes_api_user']);
  }

  public function register_routes_api_user()
  {
    register_rest_route('api/v1', '/user/create', [
      'methods' => WP_REST_Server::CREATABLE,
      'callback' =>  [$this, 'create'],
      'permission_callback' => function ($request) {
        return is_valid_origin($request);
      }
    ]);
    register_rest_route('api/v1', '/user', [
      'methods' => WP_REST_Server::EDITABLE,
      'callback' =>  [$this, 'update'],
      'permission_callback' => function ($request) {
        return is_valid_origin($request);
      }
    ]);
    register_rest_route('api/v1', '/user/pass', [
      'methods' => WP_REST_Server::EDITABLE,
      'callback' =>  [$this, 'update_pass'],
      'permission_callback' => function ($request) {
        return is_valid_origin($request);
      }
    ]);
    register_rest_route('api/v1', '/user', [
      'methods' => WP_REST_Server::READABLE,
      'callback' =>  [$this, 'get'],
      'permission_callback' => function ($request) {
        return is_valid_origin($request);
      }
    ]);
    register_rest_route('api/v1', '/user', [
      'methods' => WP_REST_Server::DELETABLE,
      'callback' =>  [$this, 'delete'],
      'permission_callback' => function ($request) {
        return is_valid_origin($request);
      }
    ]);
  }

  public function create($request)
  {
    $firstname = sanitize_text_field($request['first_name']);
    $lastname = sanitize_text_field($request['last_name']);
    $email = sanitize_email($request['user_email']);
    $username = sanitize_text_field($request['username']);

    $is_invalid_password = check_is_invalid_password($request['user_pass']);
    if ($is_invalid_password) {
      return $is_invalid_password;
    }

    $password = $request['user_pass'];

    $id_user = wp_create_user($username, $password, $email);

    if (!is_numeric($id_user)) {
      $response_error = $id_user;
      return rest_ensure_response($response_error);
    }

    $display_name = $firstname . " " . $lastname;
    $userdata = [
      'ID' => $id_user,
      'first_name' => $firstname,
      'last_name' => $lastname,
      'display_name' => $display_name,
      'role' => 'subscriber'
    ];
    wp_update_user($userdata);

    $response = [
      "success" => true,
      "message" => "Pronto $display_name, a sua conta foi criada!",
      "data" => [
        "user_id" => $id_user,
        "user_name" => $username,
        "display_name" => $display_name,
      ]
    ];
    return rest_ensure_response($response);
  }

  public function get($request)
  {
    $user = wp_get_current_user();
    $usermeta = get_user_meta($user->ID);

    $response = [
      'ID' => $user->ID,
      'first_name' => array_shift($usermeta['first_name']),
      'last_name' => array_shift($usermeta['last_name']),
      'nickname' => array_shift($usermeta['nickname']),
      'description' => array_shift($usermeta['description']),
      'user_email' => $user->user_email
    ];

    return rest_ensure_response($response);
  }

  public function update($request)
  {
    $user = wp_get_current_user();
    $id_user = $user->ID;

    $userdata = ['ID' => $id_user];
    $userdata = add_in_array_if_is_not_empty($userdata, "first_name", sanitize_text_field($request['first_name']));
    $userdata = add_in_array_if_is_not_empty($userdata, "last_name", sanitize_text_field($request['last_name']));
    $userdata = add_in_array_if_is_not_empty($userdata, "display_name", sanitize_text_field($request['display_name']));
    $userdata = add_in_array_if_is_not_empty($userdata, "nickname", sanitize_text_field($request['nickname']));
    $userdata = add_in_array_if_is_not_empty($userdata, "user_email", sanitize_email($request['user_email']));
    $userdata = add_in_array_if_is_not_empty($userdata, "description", sanitize_text_field($request['description']));

    wp_update_user($userdata);

    $user = wp_get_current_user();

    $response = [
      "success" => true,
      "message" => "Pronto $user->display_name! Os dados de sua conta foram atualizados!",
      "data" => [
        "user_id" => $id_user,
        "user_name" => $user->user_login,
        "display_name" => $user->display_name,
      ]
    ];
    return rest_ensure_response($response);
  }

  public function update_pass($request)
  {
    $user = wp_get_current_user();
    $id_user = $user->ID;

    $is_invalid_password = check_is_invalid_password($request['user_pass']);
    if ($is_invalid_password) {
      return $is_invalid_password;
    }

    $password = $request['user_pass'];
    wp_update_user([
      'ID' => $id_user,
      'user_pass' => $password
    ]);

    $response = [
      "success" => true,
      "message" => "Pronto $user->display_name! A sua senha atualizada!",
      "data" => [
        "user_id" => $id_user,
        "user_name" => $user->user_login,
        "display_name" => $user->display_name,
      ]
    ];
    return rest_ensure_response($response);
  }

  public function delete($request)
  {
    $user = wp_get_current_user();
    $id_user = $user->ID;

    delete_all_categorys();
    delete_all_business();
    delete_all_products();

    require_once(ABSPATH . 'wp-admin/includes/user.php');
    wp_delete_user($id_user);
    $response = [
      "success" => true,
      "message" => "Pronto o usuário $user->user_login foi deletado!",
      "data" => [
        "user_id" => $id_user,
        "user_name" => $user->user_login,
      ]
    ];
    return rest_ensure_response($response);
  }
}
