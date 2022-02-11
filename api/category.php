<?php
class ST_categoryAPI
{
  function __construct()
  {
    add_action('rest_api_init', [$this, 'register_routes_api_category']);
  }

  public function register_routes_api_category()
  {
    register_rest_route('v1', '/category/create', [
      'methods' => WP_REST_Server::CREATABLE,
      'callback' =>  [$this, 'create'],
      'permission_callback' => function ($request) {
        return is_valid_origin($request);
      }
    ]);
    register_rest_route('v1', '/category', [
      'methods' => WP_REST_Server::EDITABLE,
      'callback' =>  [$this, 'update'],
      'permission_callback' => function ($request) {
        return is_valid_origin($request);
      }
    ]);
    register_rest_route('v1', '/category/(?P<taxonomy>[\w]+)', [
      'methods' => WP_REST_Server::READABLE,
      'callback' =>  [$this, 'get'],
      'permission_callback' => function ($request) {
        return is_valid_origin($request);
      }
    ]);
    register_rest_route('v1', '/category', [
      'methods' => WP_REST_Server::READABLE,
      'callback' =>  [$this, 'all_get'],
      'permission_callback' => function ($request) {
        return is_valid_origin($request);
      }
    ]);
    register_rest_route('v1', '/category/(?P<taxonomy>[\w]+)/(?P<term_id>[\w]+)', [
      'methods' => WP_REST_Server::READABLE,
      'callback' =>  [$this, 'single_get'],
      'permission_callback' => function ($request) {
        return is_valid_origin($request);
      }
    ]);
    register_rest_route('v1', '/category/(?P<taxonomy>[\w]+)/(?P<term_id>[\w]+)', [
      'methods' => WP_REST_Server::DELETABLE,
      'callback' =>  [$this, 'delete'],
      'permission_callback' => function ($request) {
        return is_valid_origin($request);
      }
    ]);
  }

  public function create($request)
  {
    $is_valide_term = check_is_invalid_text($request['term'], 20, 3, "term_invalid");
    if ($is_valide_term) {
      return $is_valide_term;
    }

    $term = sanitize_text_field($request['term']);
    $taxonomy = sanitize_text_field($request['taxonomy']);
    if (!taxonomy_exists($taxonomy)) {
      return new WP_Error('taxonomy_not_valid', 'A taxonomia não existe :(', ['status' => 401]);
    }
    $description = sanitize_text_field($request['description']);
    $parent = sanitize_text_field($request['parent']);

    $user = wp_get_current_user();
    $user_id = $user->ID;
    $slug = strtolower(str_replace(" ", "", $term)) . "-" . $user_id . $parent;

    $args = [
      'description' => $description,
      'parent' => $parent,
      'slug' => $slug
    ];

    $term_id = wp_insert_term($term, $taxonomy, $args);

    if (is_wp_error($term_id)) {
      $response_error = $term_id;
      return rest_ensure_response($response_error);
    }

    update_term_meta($term_id['term_id'], 'author_id', $user_id);

    $args = [
      'taxonomy' => $taxonomy,
      'slug' => $slug,
      'orderby' => 'term_id',
      'meta_key' => 'author_id',
      'meta_value' => $user_id,
      'hide_empty' => false,
    ];

    $terms = get_terms($args)[0];

    $response = [
      "success" => true,
      "message" => "Pronto, a categoria $terms->name foi criada!",
      "data" => [
        'term_id' => $terms->term_id,
        'name' => $terms->name,
        'slug' => $slug,
        'description' => $terms->description,
        'parent' => $terms->parent,
        'count' => $terms->count,
      ]
    ];

    return rest_ensure_response($response);
  }

  public function all_get($request)
  {
    $user = wp_get_current_user();
    $user_id = $user->ID;
    $args = [
      'orderby' => 'term_id',
      'meta_key' => 'author_id',
      'meta_value' => $user_id,
      'hide_empty' => false,
    ];

    $response = st_get_category($args);

    return rest_ensure_response($response);
  }
  public function get($request)
  {
    $taxonomy = sanitize_text_field($request['taxonomy']);
    if (!taxonomy_exists($taxonomy)) {
      return new WP_Error('taxonomy_not_valid', 'A taxonomia não existe :(', ['status' => 401]);
    }
    $user = wp_get_current_user();
    $user_id = $user->ID;
    $args = [
      'taxonomy' => $taxonomy,
      'orderby' => 'term_id',
      'meta_key' => 'author_id',
      'meta_value' => $user_id,
      'hide_empty' => false,
    ];

    $response = st_get_category($args);

    return rest_ensure_response($response);
  }


  public function single_get($request)
  {
    $taxonomy = sanitize_text_field($request['taxonomy']);
    if (!taxonomy_exists($taxonomy)) {
      return new WP_Error('taxonomy_not_valid', 'A taxonomia não existe :(', ['status' => 401]);
    }

    $term_id = (int)sanitize_text_field($request['term_id']);
    if (empty($term_id) && is_numeric($term_id)) {
      return new WP_Error('term_ID_not_valid', 'O ID informado não  é válido', ['status' => 401]);
    }

    $user = wp_get_current_user();
    $user_id = $user->ID;

    $args = [
      'taxonomy' => $taxonomy,
      'term_taxonomy_id' => $term_id,
      'meta_key' => 'author_id',
      'meta_value' => $user_id,
      'hide_empty' => false,
    ];

    $response = st_get_category($args);

    return rest_ensure_response($response);
  }

  public function update($request)
  {

    return rest_ensure_response(var_dump($request));

    $term = sanitize_text_field($request['term']);
    $taxonomy = sanitize_text_field($request['taxonomy']);
    if (!taxonomy_exists($taxonomy)) {
      return new WP_Error('taxonomy_not_valid', 'A taxonomia não existe :(', ['status' => 401]);
    }
    $term_id = (int)sanitize_text_field($request['term_id']);
    if (empty($term_id) && is_numeric($term_id)) {
      return new WP_Error('term_ID_not_valid', 'O ID informado não  é válido', ['status' => 401]);
    }
    $user = wp_get_current_user();
    $user_id = $user->ID;

    $array_author_id_term = get_term_meta($term_id, 'author_id');
    $author_id_term = (int)array_shift($array_author_id_term);

    if ($user_id !== $author_id_term) {
      return new WP_Error('user_not_allowed', 'Você não pode editar esta categoria, porque não foi você que criou :) ', ['status' => 401]);
    }
    $slug = '';
    if (!empty($term)) {
      $slug = strtolower(str_replace(" ", "", $term)) . "-" . $term_id;
    }

    $parent = (int)sanitize_text_field($request['parent']);
    $termdata = [];
    $termdata = add_in_array_if_is_not_empty($termdata, "name", $term);
    $termdata = add_in_array_if_is_not_empty($termdata, "description", sanitize_text_field($request['description']));
    $termdata = add_in_array_if_is_not_empty($termdata, "parent", $parent);
    $termdata = add_in_array_if_is_not_empty($termdata, "slug", $slug);

    $array_term_id = wp_update_term($term_id, $taxonomy, $termdata);

    if (is_wp_error($array_term_id)) {
      $response_error = $array_term_id;
      return rest_ensure_response($response_error);
    }

    $args = [
      'taxonomy' => $taxonomy,
      'term_taxonomy_id' => $term_id,
      'hide_empty' => false,
    ];

    $terms = get_terms($args)[0];

    $response = [
      "success" => true,
      "message" => "Pronto, a categoria $terms->name foi atualizada!",
      "data" => [
        'term_id' => $terms->term_id,
        'name' => $terms->name,
        'slug' => $terms->slug,
        'description' => $terms->description,
        'parent' => $terms->parent,
        'count' => $terms->count,
      ]
    ];

    return rest_ensure_response($response);
  }

  public function delete($request)
  {
    $taxonomy = sanitize_text_field($request['taxonomy']);
    if (!taxonomy_exists($taxonomy)) {
      return new WP_Error('taxonomy_not_valid', 'A taxonomia não existe :(', ['status' => 401]);
    }
    $term_id = (int)sanitize_text_field($request['term_id']);
    if (empty($term_id) && is_numeric($term_id)) {
      return new WP_Error('term_ID_not_valid', 'O ID informado não  é válido', ['status' => 401]);
    }
    $user = wp_get_current_user();
    $user_id = $user->ID;

    $author_id_term = (int)get_term_meta($term_id, 'author_id')[0];

    if ($user_id !== $author_id_term) {
      return new WP_Error('user_not_allowed', 'Você não pode excluir esta categoria, porque não foi você que criou :) ', ['status' => 401]);
    }
    wp_delete_term($term_id, $taxonomy);
    delete_term_meta($term_id, 'author_id');

    $response = [
      "success" => true,
      "message" => "Pronto, a categoria foi excluida!",
      "data" => []
    ];

    return rest_ensure_response($response);
  }
}
