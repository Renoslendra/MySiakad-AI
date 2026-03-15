<?php

// Ensure the SQLite database exists if we are using it
if (env('DB_CONNECTION') === 'sqlite') {
    $dbPath = env('DB_DATABASE', '/tmp/database.sqlite');
    if (!file_exists($dbPath)) {
        touch($dbPath);
        // Run migrations if needed - though usually better done in build step
        // For now, we just ensure the file exists so Laravel doesn't crash
    }
}

// Forward request to Laravel's index.php
require __DIR__ . '/../public/index.php';

