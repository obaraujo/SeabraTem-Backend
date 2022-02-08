<?php

class ST_business
{
  function __construct()
  {
    add_action('init', [$this, 'create_categorys']);
    add_action('init', [$this, 'create']);
  }

  public function create()
  {
    register_post_type('st-business', [
      'label' => 'Negócios',
      'description' => 'Negócios',
      'public' => true,
      'show_ui' => true,
      'capability_type' => 'post',
      'rewrite' => ['slug' => 'negocio'],
      'query_var' => true,
      'taxonomies' => ['category_business'],
      'supports' => ['custom-fields', 'author', 'title'],
      'publicly_queryable' => true
    ]);
  }
  public function create_categorys()
  {
    register_taxonomy(
      'category_business',
      'st-business',
      [
        'hierarchical'          => true,
        'query_var'             => 'category_name',
        'rewrite'               => false,
        'public'                => true,
        'show_ui'               => true,
        'show_admin_column'     => true,
        '_builtin'              => true,
        'capabilities'          => [
          'manage_terms' => 'manage_categories',
          'edit_terms'   => 'edit_categories',
          'delete_terms' => 'delete_categories',
          'assign_terms' => 'assign_categories',
        ],
        'show_in_rest'          => true,
        'rest_base'             => 'categories_business',
        'rest_controller_class' => 'WP_REST_Terms_Controller',
      ]
    );
  }
}
