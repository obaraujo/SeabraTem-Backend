<?php

/**
 *  Seabra Tem in Wordpress
 * 
 * @package   Seabra_Tem
 * @version   1.0.0
 * @package   seabratem
 * @link      https://github.com/obaraujo/seabratem
 * @author    Vinícius Araújo <baraujo@stagon.in>
 * @copyright 2022 Vinícius Araújo
 *
 * Plugin Name: Seabra Tem Backend
 * Plugin URI: http://stagon.in/seabratem
 * Description: Plugin base do backend da Seabra Tem.
 * Author: Stagon | O Baraujo
 * Version: 1.0.0
 * Author URI: https://stagon.in
 * Text Domain:  seabratem
 * Domain Path:  /languages/
 * Requires PHP: 7.4
 */
if (!defined('ABSPATH')) {
  exit;
}

require_once(plugin_dir_path(__FILE__) . 'core/business.php');
require_once(plugin_dir_path(__FILE__) . 'core/products.php');
require_once(plugin_dir_path(__FILE__) . 'core/categories.php');
require_once(plugin_dir_path(__FILE__) . 'core/ratings.php');
require_once(plugin_dir_path(__FILE__) . 'core/user.php');

if (!class_exists('SeabraTemBackend')) {
  class SeabraTemBackend
  {
    function __construct()
    {
      $this->define_constants();
      require_once(ST_PATH . 'functions.php');
      require_once(ST_PATH . 'functions/jwt_config.php');
      require_once(ST_PATH . 'functions/category.php');
      require_once(ST_PATH . 'functions/business.php');
      require_once(ST_PATH . 'functions/product.php');
      require_once(ST_PATH . 'functions/st-db.php');
      require_once(ST_PATH . 'core/products.php');
      $ST_Products = new ST_products();
      $ST_business = new ST_business();
      require_once(ST_PATH . 'api/users.php');
      $ST_users = new ST_users();
      require_once(ST_PATH . 'api/business.php');
      $ST_businessAPI = new ST_businessAPI();
      require_once(ST_PATH . 'api/category.php');
      $ST_categoryAPI = new ST_categoryAPI();
    }

    public function define_constants()
    {
      define('ST_PATH', plugin_dir_path(__FILE__));
      define('ST_URL', plugin_dir_url(__FILE__));
    }

    public static function activate()
    {
      update_option('rewrite_rules', '');

      $ST_business = new ST_business();
      $ST_users = new ST_user();
      $ST_products = new ST_products();
      $ST_categories = new ST_categories();
      $ST_ratings = new ST_ratings();

      $ST_ratings->create_table_ratings();
      $ST_categories->create_table_categories();
      $ST_users->create_table_users();
      $ST_business->create_table_business();
      $ST_products->create_table_products();
    }

    public static function deactivate()
    {
      flush_rewrite_rules();

      global $wpdb;
      $query = "DROP TABLE";
      $number_tables = count(generete_table_name());
      $counter = 0;
      foreach (array_reverse(generete_table_name()) as $key => $value) {
        if ($number_tables - 1 > $counter) {
          $query .= " `{$value}`,";
        } else {
          $query .= " `{$value}`;";
        }
        $counter++;
      }
      // update_option('st_db_version', $query);
      $wpdb->query($query);
    }
    public static function uninstall()
    {
    }
  }
}

if (class_exists('SeabraTemBackend')) {
  register_activation_hook(__FILE__, ['SeabraTemBackend', 'activate']);
  register_deactivation_hook(__FILE__, ['SeabraTemBackend', 'deactivate']);
  register_uninstall_hook(__FILE__, ['SeabraTemBackend', 'uninstall']);
  $SeabraTemBackend = new SeabraTemBackend();
}
