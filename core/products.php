<?php

class ST_products
{
  function __construct()
  {
    add_action('init', [$this, 'create']);
  }

  public function create()
  {
    register_post_type("st-product", [
      'label' => 'Produtos',
      'description' => 'Produtos',
      'public' => true,
      'show_ui' => true,
      'capability_type' => 'post',
      'rewrite' => ['slug' => 'produto'],
      'query_var' => true,
      'supports' => ['custom-fields', 'author', 'title'],
      'publicly_queryable' => true,
      'hierarchical' => true,
    ]);
  }
}
