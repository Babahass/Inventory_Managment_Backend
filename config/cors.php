<?php

return [

    // Apply CORS to all API routes
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    // Explicit production domains & local development
    'allowed_origins' => [
        'http://localhost:3000',
        'http://127.0.0.1:3000',
        'https://inventory-managment-frontend-nu.vercel.app'
        // Add your exact production Vercel domain here (without trailing slash):
        // 'https://your-app-name.vercel.app',
    ],

    // Automatically matches any Vercel domain and preview deploy branch
    'allowed_origins_patterns' => [
        '#^https://.*\.vercel\.app$#',
    ],

    'allowed_headers' => ['Content-Type', 'Authorization', 'Accept', 'X-Requested-With', 'X-XSRF-TOKEN'],

    'exposed_headers' => [],

    'max_age' => 86400, // cache preflight for 24 hours

    // false — we use token auth (Authorization: Bearer <token>), not session cookies
    'supports_credentials' => false,

];