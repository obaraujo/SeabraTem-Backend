<?php
class ST_businessAPI
{
  function __construct()
  {
    add_action('rest_api_init', [$this, 'register_routes_api_business']);
  }

  public function register_routes_api_business()
  {
    register_rest_route('v1', '/business/create', [
      'methods' => WP_REST_Server::CREATABLE,
      'callback' =>  [$this, 'create'],
      'permission_callback' => function ($request) {
        return is_valid_origin($request);
      }
    ]);
    register_rest_route('v1', '/business', [
      'methods' => WP_REST_Server::EDITABLE,
      'callback' =>  [$this, 'update'],
      'permission_callback' => function ($request) {
        return is_valid_origin($request);
      }
    ]);
    register_rest_route('v1', '/business/pass', [
      'methods' => WP_REST_Server::EDITABLE,
      'callback' =>  [$this, 'update_pass'],
      'permission_callback' => function ($request) {
        return is_valid_origin($request);
      }
    ]);
    register_rest_route('v1', '/business', [
      'methods' => WP_REST_Server::READABLE,
      'callback' =>  [$this, 'get'],
      'permission_callback' => function ($request) {
        return is_valid_origin($request);
      }
    ]);
    register_rest_route('v1', '/business', [
      'methods' => WP_REST_Server::DELETABLE,
      'callback' =>  [$this, 'delete'],
      'permission_callback' => function ($request) {
        return is_valid_origin($request);
      }
    ]);
  }

  public function create($request)
  {
  }

  public function get($request)
  {
  }

  public function update($request)
  {
  }

  public function delete($request)
  {
  }
}
