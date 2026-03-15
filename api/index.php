<?php

// Ensure the SQLite database exists if we are using it
$dbConn = getenv('DB_CONNECTION');
if ($dbConn === 'sqlite') {
    $dbPath = getenv('DB_DATABASE') ?: '/tmp/database.sqlite';
    if (!file_exists($dbPath)) {
        if (!is_dir(dirname($dbPath))) {
            mkdir(dirname($dbPath), 0777, true);
        }
        touch($dbPath);
    }
}

// Forward request to Laravel's index.php
require __DIR__ . '/../public/index.php';

