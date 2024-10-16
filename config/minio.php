<?php

return [
    'server' => env('MINIO_SERVER', 'http://localhost:9000'),
    'access_key' => env('MINIO_ACCESS_KEY', 'minioadmin'),
    'secret_key' => env('MINIO_SECRET_KEY', 'minioadmin'),
];
