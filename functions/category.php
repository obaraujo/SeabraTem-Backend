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
