<?php

add_action('jwt_auth_expire', function () {
  return time() + 60 * 60 * 24;
});


add_filter('jwt_auth_whitelist', function ($endpoints) {
  $your_endpoints = [
    '/wp-json/custom/v1/webhook/*',
    '/wp-json/custom/v1/otp/*',
    '/wp-json/custom/v1/account/check',
    '/wp-json/custom/v1/register',
  ];

  return array_unique(array_merge($endpoints, $your_endpoints));
});

add_filter('jwt_auth_default_whitelist', function ($default_whitelist) {
  $default_whitelist = [
    ...$default_whitelist,
    '/wp-json/api/v1/user/create',
    '/wp-json/api/v1/user/login',
  ];
  return $default_whitelist;
});
