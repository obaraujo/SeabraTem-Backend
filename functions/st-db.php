<?php
function generete_table_name()
{
  global $wpdb;

  $prefix_wp = $wpdb->prefix;
  $table_prefix = $prefix_wp . 'st_';
  $table_names = [
    "business" => $table_prefix . "business",
    "links" => $table_prefix . "links",
    "analystics_likes" => $table_prefix . "analystics_likes",
    "analystics_clickLinks" => $table_prefix . "analystics_clickLinks",
    "analystics_business_views" => $table_prefix . "analystics_business_views",
    "categories" => $table_prefix . "categories",
  ];

  return $table_names;
}
