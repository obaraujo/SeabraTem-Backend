<?php
function delete_all_categorys()
{
  $taxs = get_taxonomies();
  $array_taxs = [];
  foreach ($taxs as $tax => $data) {
    array_push($array_taxs, $data);
  }

  $args = [
    'taxonomy' => $array_taxs,
    'meta_key' => 'author_id',
    'meta_value' => wp_get_current_user()->ID,
    'hide_empty' => false,
  ];
  $terms = get_terms($args);
  foreach ($terms as $term => $data) {
    wp_delete_term($data->term_id, $data->taxonomy);
    delete_term_meta($data->term_id, 'author_id');
  }
}

function st_get_category($args_search)
{
  $terms = get_terms($args_search);

  if (empty($terms)) {
    return new WP_Error('category_not_exists', 'A categoria buscada não foi encontrada!', ['status' => 401]);
  }
  $response = [];
  foreach ($terms as $term => $data) {
    $response[$data->slug] = [
      'term_id' => $data->term_id,
      'name' => $data->name,
      'slug' => $data->slug,
      'description' => $data->description,
      'parent' => $data->parent,
      'count' => $data->count,
    ];
  }

  return $response;
}
