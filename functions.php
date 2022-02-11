<?php
define("ST_REQUEST_HOST", "localhost");
function is_valid_origin($request)
{
  return $request->get_header('origin') === ST_REQUEST_HOST || $request->get_header('host') === ST_REQUEST_HOST;
}

function message_access_not_allowed()
{
  return rest_ensure_response(new WP_Error('premission', 'Origem não autorizada!', ['status' => 401]));
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
