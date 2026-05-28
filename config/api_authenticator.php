<?php

return [
    'check_path'    => env('AUTH_CHECK_PATH', '/api/v1/login'),
    'username_path' => env('AUTH_USERNAME_PATH', 'email'),
    'password_path' => env('AUTH_PASSWORD_PATH', 'password'),
];