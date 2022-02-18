<?php

class ST_ratings
{
  function __construct()
  {
  }

  public function create_table_ratings()
  {
    global $wpdb;
    $seabratem_table_names = generete_table_name();

    $prefix_wp = $wpdb->prefix;

    $charset_collate = $wpdb->get_charset_collate();

    $st_db_version = get_option('st_db_version');
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    if (true) {
      $query = "CREATE TABLE IF NOT EXISTS `{$seabratem_table_names['ratings']}` (
        `ID` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `ID_user` BIGINT(20) UNSIGNED NOT NULL,
        `ID_post` BIGINT(20) UNSIGNED NOT NULL,
        `ID_rating_parent` BIGINT(20) UNSIGNED,
        `name_author` VARCHAR(50) NOT NULL DEFAULT '',
        `star` BIGINT(1) UNSIGNED NOT NULL,
        `title` VARCHAR(50) NOT NULL DEFAULT '',
        `description` TEXT(300) DEFAULT '',
        `likes` BIGINT(20) UNSIGNED,
        `deslikes` BIGINT(20) UNSIGNED,
        `is_response` BOOLEAN NOT NULL,
        PRIMARY KEY (`ID`),
        FOREIGN KEY (`ID_user`) REFERENCES `{$prefix_wp}users`(ID) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (`ID_post`) REFERENCES `{$prefix_wp}posts`(ID) ON DELETE CASCADE ON UPDATE CASCADE
      ) $charset_collate;";
      dbDelta($query);
    }
  }
}
