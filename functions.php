<?php

/*-------------REQUIRE---------------*/

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

/*-----------------------------------*/


function is_valid_origin()
{
  $server_names_permitted = ['seabratem.com', 'localhost', '127.0.0.1', '192.168.2.102', 'api.stagon.in'];
  return in_array($_SERVER['SERVER_NAME'], $server_names_permitted);
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

add_action('rest_api_init', 'register_routes_api_tests');
function register_routes_api_tests()
{
  register_rest_route('v1', '/tests', [
    'methods' => WP_REST_Server::READABLE,
    'callback' => function ($request) {
      $response = "`Vai{$_SERVER['REQUEST_URI']}`";
      return rest_ensure_response($response);
    },
    'permission_callback' => function () {
      return is_valid_origin();
    },
  ]);
}

add_filter('rest_url_prefix', 'st_api_slug');
function st_api_slug($slug)
{
  return 'api';
}

/*--CREATE RELATIONSHIPS BETWEEN DB TABLES--*/

function create_tables_relationship(array $tables){
    /*-- [[$table, $key], [$table2, $foreign_key]] --*/
    $response = false;
    
    if (!empty($tables)){
        $table = $tables[0][0];
        $key = $tables[0][1];
        $table2 = $tables[1][0];
        $foreign_key = $tables[1][1];
        $query = "ALTER TABLE $table ADD FOREIGN KEY ($key) REFERENCES " . $table2 . "($foreign_key);";
        
        $response = dbDelta($query);
    }
    
    return $response;
}

function init_tables_relationships(array $tables){
    /*-[[[$table, $key], [$table2, $foreign_key]], [[$table, $key], [$table2, $foreign_key]]]-*/
    $result = false;
    if (!empty($tables)){
        foreach ($tables as $item){
            create_tables_relationship($item);
        }
        
        $result = true;
    }
    
    return $result;
    
}


flush_rewrite_rules(true);

