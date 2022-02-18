<?php

class ST_categories
{
  function __construct()
  {
  }

  public function create_table_categories()
  {
    global $wpdb;
    $seabratem_table_names = generete_table_name();

    $prefix_wp = $wpdb->prefix;

    $charset_collate = $wpdb->get_charset_collate();

    $st_db_version = get_option('st_db_version');
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    if (true) {
      $query = "CREATE TABLE IF NOT EXISTS `{$seabratem_table_names['categories']}` (
        `ID` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `ID_user` BIGINT(20) UNSIGNED NOT NULL,
        `name` VARCHAR(50) NOT NULL DEFAULT '',
        `slug` VARCHAR(50) NOT NULL DEFAULT '',
        `taxonomy` VARCHAR(100) NOT NULL DEFAULT '',
        `description` TEXT(300) DEFAULT '',
        `count_used` BIGINT(20),
        PRIMARY KEY (`ID`),
        FOREIGN KEY (`ID_user`) REFERENCES `{$prefix_wp}users`(ID) ON DELETE CASCADE ON UPDATE CASCADE
      ) $charset_collate;";
      dbDelta($query);
    }
  }
}
