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

  public function create_table_products()
  {
    global $wpdb;
    $seabratem_table_names = generete_table_name();

    $prefix_wp = $wpdb->prefix;

    $charset_collate = $wpdb->get_charset_collate();

    $st_db_version = get_option('st_db_version');
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    if (true) {
      $query = "CREATE TABLE IF NOT EXISTS `{$seabratem_table_names['products']}` (
        `ID` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `ID_category` BIGINT(20) UNSIGNED NOT NULL,
        `ID_business` BIGINT(20) UNSIGNED NOT NULL,
        `ID_user` BIGINT(20) UNSIGNED NOT NULL,
        `ID_product_parent` BIGINT(20) UNSIGNED DEFAULT NULL,
        `name` varchar(100) NOT NULL DEFAULT '',
        `description` text(300) NOT NULL DEFAULT '',
        `original_price` DECIMAL(7, 2) NOT NULL,
        `discount_percent` DECIMAL(3, 2) DEFAULT NULL,
        `stock` BIGINT(20) UNSIGNED DEFAULT NULL,
        `is_variation` BOOLEAN,
        `is_active` BOOLEAN,
        PRIMARY KEY(`ID`),
        FOREIGN KEY(`ID_category`) REFERENCES `{$seabratem_table_names['categories']}`(`ID`) ON UPDATE CASCADE ON DELETE CASCADE,
        FOREIGN KEY(`ID_business`) REFERENCES `{$seabratem_table_names['business']}`(`ID`) ON UPDATE CASCADE ON DELETE CASCADE,
        FOREIGN KEY(`ID_user`) REFERENCES `{$prefix_wp}users`(`ID`) ON UPDATE CASCADE ON DELETE CASCADE
      ) $charset_collate;";
      dbDelta($query);

      $query = "CREATE TABLE IF NOT EXISTS `{$seabratem_table_names['demands']}` (
        `ID` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `ID_user_buyer` BIGINT(20) UNSIGNED NOT NULL,
        `date` DATE NOT NULL,
        `time` TIME NOT NULL,
        `confirmed` BOOLEAN,
        `status` VARCHAR(11) DEFAULT '',
        `final_price` DECIMAL(7, 2),
        `ID_business` BIGINT(20) UNSIGNED NOT NULL,
        PRIMARY KEY(`ID`),
        FOREIGN KEY(`ID_user_buyer`) REFERENCES `{$prefix_wp}users`(`ID`) ON UPDATE CASCADE ON DELETE CASCADE,
        FOREIGN KEY(`ID_business`) REFERENCES `{$prefix_wp}posts`(`ID`) ON UPDATE CASCADE ON DELETE CASCADE
      ) $charset_collate;";
      dbDelta($query);
      update_option('st_db_version', $query);

      $query = "CREATE TABLE IF NOT EXISTS `{$seabratem_table_names['analytics_product_views']}` (
        `ID` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `ID_post` BIGINT(20) UNSIGNED NOT NULL,
        `ID_user` BIGINT(20) UNSIGNED NOT NULL,
        `date` DATE NOT NULL,
        `time` TIME NOT NULL,
        `duration` BIGINT(20) UNSIGNED NOT NULL,
        PRIMARY KEY(`ID`),
        FOREIGN KEY(`ID_post`) REFERENCES `{$seabratem_table_names['products']}`(`ID`) ON UPDATE CASCADE ON DELETE CASCADE,
        FOREIGN KEY(`ID_user`) REFERENCES `{$prefix_wp}users`(`ID`) ON UPDATE CASCADE ON DELETE CASCADE
      ) $charset_collate;";
      dbDelta($query);

      $query = "CREATE TABLE IF NOT EXISTS `{$seabratem_table_names['demands_relationship']}` (
        `ID` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `ID_product` BIGINT(20) UNSIGNED NOT NULL,
        `ID_demand` BIGINT(20) UNSIGNED NOT NULL,
        `price_unity` DECIMAL(7, 2),
        `final_price` DECIMAL(7, 2),
        `amount` BIGINT(20) UNSIGNED NOT NULL,
        `discount` BIGINT(20) UNSIGNED DEFAULT NULL,
        `product_name` VARCHAR(50) NOT NULL DEFAULT '',
        `variation` VARCHAR(50) DEFAULT NULL DEFAULT '',
        PRIMARY KEY(`ID`),
        FOREIGN KEY(`ID_demand`) REFERENCES `{$seabratem_table_names['demands']}`(`ID`) ON UPDATE CASCADE ON DELETE CASCADE,
        FOREIGN KEY(`ID_product`) REFERENCES `{$seabratem_table_names['products']}`(`ID`) ON UPDATE CASCADE ON DELETE CASCADE
      ) $charset_collate;";
      dbDelta($query);
    }
  }
}
