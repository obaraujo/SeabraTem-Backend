<?php
define("ST_REQUEST_HOST", "https://seabratem.com");
function is_valid_origin($request)
{
  return $request->get_header('origin') === ST_REQUEST_HOST || $request->get_header('host') === 'localhost';
}

function message_access_not_allowed()
{
  return rest_ensure_response(new WP_Error('premission', 'Origem não autorizada!', ['status' => 401]));
}

function check_is_invalid_password($password)
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
function check_is_invalid_text($text, $max, $min, $code)
{
  if (empty($text)) {
    return new WP_Error($code, 'O valor enviado está vazio!', ['status' => 401]);
  }

  if (strlen($text) < $min) {
    return new WP_Error($code, 'O valor enviado é curto demais!', ['status' => 401, 'min' => $min, 'max' => $max]);
  }

  if (strlen($text) > $max) {
    return new WP_Error($code, 'O valor enviado é longo demais!', ['status' => 401, 'min' => $min, 'max' => $max]);
  }

  return false;
}

function add_in_array_if_is_not_empty($array, $key, $value)
{
  if (!empty($value)) {
    $array[$key] = $value;
  }
  return $array;
}

function get_product_id_by_slug($slug)
{

  $query = new WP_Query([
    'name' => $slug,
    'post_type' => 'st-product',
    'numberposts' => 1,
    'fields' =>  'ids'
  ]);

  $product = $query->get_posts();

  return array_shift($product);
}

add_action('jwt_auth_expire', function () {
  return time() + 60 * 60 * 24;
});

add_filter('jwt_auth_default_whitelist', function ($default_whitelist) {
  // Modify the $default_whitelist here.
  $default_whitelist = [
    '/wp-json/api/v1/user/create',
  ];
  return $default_whitelist;
});
