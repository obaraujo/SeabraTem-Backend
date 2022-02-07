<?php

class ST_business
{
  function __construct()
  {
    add_action('init', [$this, 'create']);
  }

  public function create()
  {
    register_post_type("st-business", [
      'label' => 'Negócios',
      'description' => 'Negócios',
      'public' => true,
      'show_ui' => true,
      'capability_type' => 'post',
      'rewrite' => ['slug' => 'negocio'],
      'query_var' => true,
      'supports' => ['custom-fields', 'author', 'title'],
      'publicly_queryable' => true
    ]);
  }
}
