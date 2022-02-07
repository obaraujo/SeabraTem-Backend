<?php

/**
 *  Seabra Tem in Wordpress
 * 
 * @package Seabra_Tem
 * @version 1.0.0
 * @package   seabratem
 * @link      https://github.com/obaraujo/seabratem
 * @author    Vinícius Araújo <baraujo@stagon.in>
 * @copyright 2022 Vinícius Araújo
 *
 * Plugin Name: Seabra Tem 
 * Plugin URI: http://stagon.in//seabratem
 * Description: Plugin base da arquitetura da Seabra Tem. Responsável por servir o Backend da Seabra Tem
 * Author: Stagon | O Baraujo
 * Version: 1.0.0
 *Author URI: https://stagon.in
 * Text Domain:  seabratem
 * Domain Path:  /languages/
 * Requires PHP: 7.4
 */
if (!defined('ABSPATH')) {
  exit;
}

if (!class_exists('SeabraTem')) {
  class SeabraTem
  {
    function __construct()
    {
      $this->define_constants();
      require_once(ST_PATH . 'functions.php');
      require_once(ST_PATH . 'custom-post-type/products.php');
      $ST_Products = new ST_products();
      require_once(ST_PATH . 'custom-post-type/business.php');
      $ST_business = new ST_business();
      require_once(ST_PATH . 'api/users.php');
      $ST_users = new ST_Users();
      require_once(ST_PATH . 'api/products.php');
      $ST_ProductsAPI = new ST_ProductsAPI();
    }

    public function define_constants()
    {
      define('ST_PATH', plugin_dir_path(__FILE__));
      define('ST_URL', plugin_dir_url(__FILE__));
    }

    public static function activate()
    {
      update_option('rewrite_rules', '');
    }
    public static function deativate()
    {
      flush_rewrite_rules();
    }
    public static function uninstall()
    {
    }
  }
}

if (class_exists('SeabraTem')) {
  register_activation_hook(__FILE__, ['SeabraTem', 'activate']);
  register_deactivation_hook(__FILE__, ['SeabraTem', 'deactivate']);
  register_uninstall_hook(__FILE__, ['SeabraTem', 'uninstall']);
  $seabratem = new SeabraTem();
}