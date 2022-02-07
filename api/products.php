<?php

class ST_ProductsAPI
{
  function __construct()
  {
    add_action('rest_api_init', [$this, 'register_routes_api_product']);
  }

  public function register_routes_api_product()
  {
    register_rest_route('api/v1', '/product', [
      'methods' => 'POST',
      'callback' =>  [$this, 'create']
    ]);
    register_rest_route('api/v1', '/product', [
      'methods' => 'PUT',
      'callback' =>  [$this, 'update']
    ]);
    register_rest_route('api/v1', '/product/(?P<slug>[-\w]+)', [
      'methods' => 'GET',
      'callback' =>  [$this, 'simple_get']
    ]);
    register_rest_route('api/v1', '/product', [
      'methods' => 'DELETE',
      'callback' =>  [$this, 'delete']
    ]);
  }

  public function create($request)
  {
    if (!is_valid_origin($request)) {
      return message_access_not_allowed();
    }

    $name = sanitize_text_field($request['name']);
    $description = sanitize_text_field($request['description']);
    $price = sanitize_text_field($request['price']);

    $user = wp_get_current_user();
    $id_user = $user->ID;

    $productdata = [
      'post_title'   => $name,
      'post_content' => $description,
      'post_status'  => 'draft',
      'post_author'  => $id_user,
      'post_type'    => 'st-product',
      'meta_input'   => [
        'price' => $price
      ]
    ];

    $product_id = wp_insert_post($productdata);

    $files = $request->get_file_params();
    if ($files) {
      require_once(ABSPATH . 'wp-admin/includes/image.php');
      require_once(ABSPATH . 'wp-admin/includes/file.php');
      require_once(ABSPATH . 'wp-admin/includes/media.php');

      foreach ($files as $file => $array) {
        media_handle_upload($file, $product_id);
      }
    }
    return rest_ensure_response($productdata);
  }

  public function update($request)
  {
    if (!is_valid_origin($request)) {
      return message_access_not_allowed();
    }
  }

  public function simple_get($request)
  {
    if (!is_valid_origin($request)) {
      return message_access_not_allowed();
    }

    $slug = $request['slug'];

    $post_id  = get_product_id_by_slug($slug);

    if ($post_id) {
      $post_meta = get_post_meta($post_id);

      $images = get_attached_media('image', $post_id);

      $response = [
        'id' => $slug,
        'images' => $images,
        'name' => get_the_title($post_id),
        'description' => $post_meta['description'][0],
        'price' => $post_meta['price'][0]
      ];
    }

    return rest_ensure_response($response);
  }

  public function multiple_get($request)
  {
    if (!is_valid_origin($request)) {
      return message_access_not_allowed();
    }
  }

  public function delete($request)
  {
    if (!is_valid_origin($request)) {
      return message_access_not_allowed();
    }
  }
}
