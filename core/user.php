<?php

class ST_user
{
  function __construct()
  {
  }

  public function create_table_users()
  {
    global $wpdb;
    $seabratem_table_names = generete_table_name();

    $prefix_wp = $wpdb->prefix;

    $charset_collate = $wpdb->get_charset_collate();

    $st_db_version = get_option('st_db_version');
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    if (true) {
      $query = "CREATE TABLE IF NOT EXISTS `{$seabratem_table_names['plans']}` (
        `ID` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `ID_post` BIGINT(20) UNSIGNED NOT NULL,
        `name` VARCHAR(60) NOT NULL DEFAULT '',
        `slug` VARCHAR(50) NOT NULL DEFAULT '',
        `capacities` TEXT NOT NULL DEFAULT '',
        `description` TEXT NOT NULL DEFAULT '',
        `original_price` DECIMAL(7, 2) NOT NULL,
        `descount_price` DECIMAL(3, 2) NOT NULL,
        `duration` BIGINT(20) UNSIGNED NOT NULL,
        PRIMARY KEY (`ID`)
      ) $charset_collate;";
      dbDelta($query);

      $query = "CREATE TABLE IF NOT EXISTS `{$seabratem_table_names['plans_relationship']}` (
        `ID` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `ID_user` BIGINT(20) UNSIGNED NOT NULL,
        `ID_plan` BIGINT(20) UNSIGNED NOT NULL,
        `duration` BIGINT(20) UNSIGNED NOT NULL,
        `buy_date` DATE NOT NULL,
        `buy_time` TIME NOT NULL,
        `expiration_date` DATE NOT NULL,
        `capacities` TEXT(300) NOT NULL DEFAULT '',
        `is_active` BOOLEAN NOT NULL,
        PRIMARY KEY (`ID`),
        FOREIGN KEY (`ID_user`) REFERENCES `{$prefix_wp}_users`(ID) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (`ID_plan`) REFERENCES `{$seabratem_table_names['plans']}`(ID) ON DELETE CASCADE ON UPDATE CASCADE
      ) $charset_collate;";
      dbDelta($query);

      $query = "CREATE TABLE IF NOT EXISTS `{$seabratem_table_names['users']}` (
        `ID` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `ID_wp_user` BIGINT(20) UNSIGNED NOT NULL,
        `first_name` VARCHAR(60) DEFAULT '',
        `last_name` VARCHAR(100) DEFAULT '',
        `nickname` VARCHAR(50) DEFAULT '',
        `email` VARCHAR(100) NOT NULL DEFAULT '',
        `number_phone` VARCHAR(255) DEFAULT '',
        `description` TEXT(300) DEFAULT '',
        `create_at` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
        `last_access_date` DATE NOT NULL,
        `last_access_time` TIME NOT NULL,
        `have_plan` BOOLEAN,
        `ID_plan` BIGINT(20) UNSIGNED,
        PRIMARY KEY (`ID`),
        FOREIGN KEY (`ID_wp_user`) REFERENCES `{$prefix_wp}_users`(`ID`) ON UPDATE CASCADE ON DELETE CASCADE,
        FOREIGN KEY (`ID_plan`) REFERENCES `{$seabratem_table_names['plans_relationship']}`(`ID`) ON UPDATE CASCADE ON DELETE CASCADE
      ) $charset_collate;";
      dbDelta($query);

      $query = "CREATE TABLE IF NOT EXISTS `{$seabratem_table_names['analytics_session']}` (
        `ID` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `ID_user` BIGINT(20) UNSIGNED NOT NULL,
        `date` DATE NOT NULL,
        `time` TIME NOT NULL,
        `device` TEXT NOT NULL DEFAULT '',
        PRIMARY KEY (`ID`),
        FOREIGN KEY (`ID_user`) REFERENCES `{$prefix_wp}_users`(ID) ON DELETE CASCADE ON UPDATE CASCADE,
      ) $charset_collate;";
      dbDelta($query);
    }
  }
}
