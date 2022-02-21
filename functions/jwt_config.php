<?php

add_action('jwt_auth_expire', function () {
  return time() + 60 * 60 * 24;
});

add_filter('jwt_auth_whitelist', function ($endpoints) {
  $your_endpoints = [];

  return array_unique(array_merge($endpoints, $your_endpoints));
});

add_filter('jwt_auth_default_whitelist', function ($default_whitelist) {
  $default_whitelist = [
    // ...$default_whitelist,
    '/api/v1/user/create',
    '/api/v1/user/login',
    '/api/v1/tests'
  ];
  return $default_whitelist;
});
