<?php

class ST_users
{
  public function __construct()
  {
    add_action('rest_api_init', [$this, 'register_routes_api_user']);
  }

  public function register_routes_api_user()
  {
    register_rest_route('api/v1', '/user/create', [
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => [$this, 'st_create_user'],
      'permission_callback' => function ($request) {
        return is_valid_origin($request);
      },
    ]);

    register_rest_route('api/v1', '/user', [
      'methods' => WP_REST_Server::EDITABLE,
      'callback' => [$this, 'update'],
      'permission_callback' => function ($request) {
        return is_valid_origin($request);
      },
    ]);

    register_rest_route('api/v1', '/user/login', [
      'methods' => WP_REST_Server::EDITABLE,
      'callback' => [$this, 'st_login_user'],
      'permission_callback' => function ($request) {
        return is_valid_origin($request);
      },
    ]);

    register_rest_route('api/v1', '/user/pass', [
      'methods' => WP_REST_Server::EDITABLE,
      'callback' => [$this, 'update_pass'],
      'permission_callback' => function ($request) {
        return is_valid_origin($request);
      },
    ]);

    register_rest_route('api/v1', '/user', [
      'methods' => WP_REST_Server::READABLE,
      'callback' => [$this, 'st_get_informations_user'],
      'permission_callback' => function ($request) {
        return is_valid_origin($request);
      },
    ]);

    register_rest_route('api/v1', '/user', [
      'methods' => WP_REST_Server::DELETABLE,
      'callback' => [$this, 'delete'],
      'permission_callback' => function ($request) {
        return is_valid_origin($request);
      },
    ]);
  }

  public function st_generete_request_login($username, $password)
  {
    $info_login = wp_json_encode([
      "username" => $username,
      "password" => $password,
    ]);

    $generete_request_user = new WP_REST_Request('POST', '/jwt-auth/v1/token');
    $generete_request_user->set_header('content-type', 'application/json');
    $generete_request_user->set_body($info_login);

    $generete_resonse_user = rest_do_request($generete_request_user);

    return $generete_resonse_user;
  }

  public function st_get_login_user($username, $password)
  {
    return $this->st_generete_request_login($username, $password);
  }

  public function st_get_token_user($username, $password)
  {
    $login_user = $this->st_get_login_user($username, $password);
    $session_valid = $login_user->data['success'];
    if ($session_valid) {
      return $login_user->data['data']['token'];
    }
    return $login_user;
  }

  public function check_is_invalid_password($password)
  {
    if (empty($password)) {
      return new WP_Error('user_pass_empty', 'A senha enviada está vazia!', ['status' => 401]);
    }

    if (strlen($password) < 6) {
      return new WP_Error('user_pass_shirt', 'A senha enviada é curta demais!', ['status' => 401, 'min' => 6, 'max' => 14]);
    }

    if (strlen($password) > 14) {
      return new WP_Error('user_pass_long', 'A senha enviada é longa demais!', ['status' => 401, 'min' => 6, 'max' => 14]);
    }

    return false;
  }

  public function st_create_user($request)
  {
    $firstname = sanitize_text_field($request['first_name']);
    $lastname = sanitize_text_field($request['last_name']);
    $email = sanitize_email($request['user_email']);
    $username = sanitize_text_field($request['username']);

    $is_invalid_password = $this->check_is_invalid_password($request['user_pass']);
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
      'role' => 'subscriber',
    ];

    wp_update_user($userdata);

    $response = [
      "success" => true,
      "message" => "Pronto $display_name, a sua conta foi criada!",
      "data" => [
        "user_id" => $id_user,
        "user_name" => $username,
        "display_name" => $display_name,
        "user_token" => $this->st_get_token_user($username, $password),
      ],
    ];

    return new WP_REST_Response($response, 201);
  }

  public function st_login_user($request)
  {
    $username = sanitize_text_field($request['username']);
    $password = $request['user_pass'];

    return $this->st_get_login_user($username, $password);
  }
  public function st_get_informations_user($request)
  {
    $user = wp_get_current_user();
    $usermeta = get_user_meta($user->ID);

    $response = [
      'ID' => $user->ID,
      'first_name' => array_shift($usermeta['first_name']),
      'last_name' => array_shift($usermeta['last_name']),
      'nickname' => array_shift($usermeta['nickname']),
      'description' => array_shift($usermeta['description']),
      'user_email' => $user->user_email,
    ];

    return rest_ensure_response($_SERVER['REMOTE_ADDR']);
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
      ],
    ];
    return rest_ensure_response($response);
  }

  public function update_pass($request)
  {
    $user = wp_get_current_user();
    $id_user = $user->ID;

    $is_invalid_password = $this->check_is_invalid_password($request['user_pass']);
    if ($is_invalid_password) {
      return $is_invalid_password;
    }

    $password = $request['user_pass'];
    wp_update_user([
      'ID' => $id_user,
      'user_pass' => $password,
    ]);

    $response = [
      "success" => true,
      "message" => "Pronto $user->display_name! A sua senha atualizada!",
      "data" => [
        "user_id" => $id_user,
        "user_name" => $user->user_login,
        "display_name" => $user->display_name,
      ],
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

    require_once ABSPATH . 'wp-admin/includes/user.php';
    wp_delete_user($id_user);
    $response = [
      "success" => true,
      "message" => "Pronto o usuário $user->user_login foi deletado!",
      "data" => [
        "user_id" => $id_user,
        "user_name" => $user->user_login,
      ],
    ];
    return rest_ensure_response($response);
  }
}
