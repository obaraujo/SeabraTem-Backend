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
    'wp-json/api/v1/user/create'
  ];
  return $default_whitelist;
});
