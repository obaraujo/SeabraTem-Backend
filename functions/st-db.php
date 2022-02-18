<?php
function generete_table_name()
{
  global $wpdb;

  $prefix_wp = $wpdb->prefix;
  $table_prefix = $prefix_wp . 'st_';
  $table_names = [
    "ratings" => $table_prefix . "ratings",
    "categories" => $table_prefix . "categories",
    "plans" => $table_prefix . "plans",
    "plans_relationship" => $table_prefix . "plans_relationship",
    "users" => $table_prefix . "users",
    "analytics_sessions" => $table_prefix . "analytics_sessions",
    "business" => $table_prefix . "business",
    "links" => $table_prefix . "links",
    "analystics_likes" => $table_prefix . "analystics_likes",
    "analystics_clickLinks" => $table_prefix . "analystics_clickLinks",
    "analystics_business_views" => $table_prefix . "analystics_business_views",
    "products" => $table_prefix . "products",
    "demands" => $table_prefix . "demands",
    "analytics_product_views" => $table_prefix . "analytics_product_views",
    "demands_relationship" => $table_prefix . "demands_relationship",
  ];

  return $table_names;
}
