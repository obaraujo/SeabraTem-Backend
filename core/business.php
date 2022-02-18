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
        'hierarchical'  => true,
        'query_var'     => 'category_name',
        'rewrite'       => false,
        'public' => true,
        'show_ui'       => true,
        'show_admin_column'     => true,
        '_builtin'      => true,
        'capabilities'  => [
          'manage_terms' => 'manage_categories',
          'edit_terms'   => 'edit_categories',
          'delete_terms' => 'delete_categories',
          'assign_terms' => 'assign_categories',
        ],
        'show_in_rest'  => true,
        'rest_base'     => 'categories_business',
        'rest_controller_class' => '{$prefix_wp}REST_Terms_Controller',
      ]
    );
  }

  public function create_table_business()
  {
    global $wpdb;
    $seabratem_table_names = generete_table_name();

    $prefix_wp = $wpdb->prefix;

    $charset_collate = $wpdb->get_charset_collate();

    $st_db_version = get_option('st_db_version');
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    if (true) {
      $query = "CREATE TABLE IF NOT EXISTS `{$seabratem_table_names['business']}`(
        `ID` BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
        `ID_post` BIGINT(20) UNSIGNED NOT NULL,
        `ID_user` BIGINT(20) UNSIGNED NOT NULL,
        `name` VARCHAR (50) NOT NULL,
        `description` TEXT(300) DEFAULT NULL,
        `slogan` VARCHAR (50) DEFAULT NULL,
        `ID_category` BIGINT(20) UNSIGNED NOT NULL,
        `latitude` DECIMAL DEFAULT NULL,
        `longitude` DECIMAL DEFAULT NULL,
        `content` TEXT DEFAULT NULL,
        FOREIGN KEY (`ID_post`) REFERENCES `{$prefix_wp}posts`(`ID`),
        FOREIGN KEY (`ID_user`) REFERENCES `{$prefix_wp}users`(`ID`)
        FOREIGN KEY (`ID_category`) REFERENCES `{$seabratem_table_names['categories']}`(`ID`) ON UPDATE CASCADE ON DELETE CASCADE
      ) $charset_collate;";
      dbDelta($query);

      $query = "CREATE TABLE IF NOT EXISTS `{$seabratem_table_names['links']}`(
        `ID` BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
        `ID_post` BIGINT(20) UNSIGNED NOT NULL,
        `title` VARCHAR(30) DEFAULT NULL,
        `link` VARCHAR(30) NOT NULL,
        `type` VARCHAR(30) DEFAULT NULL,
        FOREIGN KEY (`ID_post`) REFERENCES `{$prefix_wp}posts`(`ID`) ON UPDATE CASCADE ON DELETE CASCADE
      ) $charset_collate;";
      dbDelta($query);

      $query = "CREATE TABLE IF NOT EXISTS `{$seabratem_table_names['analystics_likes']}` (
        `ID` BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
        `ID_post` BIGINT(20) UNSIGNED NOT NULL,
        `ID_user` BIGINT(20) UNSIGNED NOT NULL,
        `date` DATE NOT NULL,
        `time` TIME NOT NULL,
        FOREIGN KEY (`ID_post`) REFERENCES `{$prefix_wp}posts`(`ID`) ON UPDATE CASCADE ON DELETE CASCADE,
        FOREIGN KEY (`ID_user`) REFERENCES `{$prefix_wp}users`(`ID`) ON UPDATE CASCADE ON DELETE CASCADE
      ) $charset_collate;";
      dbDelta($query);

      $query = "CREATE TABLE IF NOT EXISTS `{$seabratem_table_names['analystics_clickLinks']}` (
        `ID` BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
        `ID_link` BIGINT(20) UNSIGNED NOT NULL,
        `ID_user` BIGINT(20) UNSIGNED NOT NULL,
        `date` DATE NOT NULL,
        `time` TIME NOT NULL,
        `type` VARCHAR(30) DEFAULT NULL,
        FOREIGN KEY (`ID_link`) REFERENCES `{$seabratem_table_names['links']}` (`ID`) ON UPDATE CASCADE ON DELETE CASCADE,
        FOREIGN KEY (`ID_user`) REFERENCES `{$prefix_wp}users` (`ID`) ON UPDATE CASCADE ON DELETE CASCADE
      ) $charset_collate;";
      dbDelta($query);

      $query = "CREATE TABLE IF NOT EXISTS `{$seabratem_table_names['analystics_business_views']}` (
        `ID` BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
        `ID_post` BIGINT(20) UNSIGNED NOT NULL,
        `ID_user` BIGINT(20) UNSIGNED NOT NULL,
        `date` DATE NOT NULL,
        `time` TIME NOT NULL,
        `duration` INT DEFAULT NULL,
        FOREIGN KEY (`ID_post`) REFERENCES `{$prefix_wp}posts`(`ID`) ON UPDATE CASCADE ON DELETE CASCADE,
        FOREIGN KEY (`ID_user`) REFERENCES `{$prefix_wp}users`(`ID`) ON UPDATE CASCADE ON DELETE CASCADE
      ) $charset_collate;";

      dbDelta($query);

      update_option('st_db_version', $query);
    }
  }
}
