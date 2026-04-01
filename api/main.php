<?php

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$index = __DIR__ . '/../public/index.php';

if (!file_exists($index)) {
    die("Error: Entry point not found at $index. Check file structure.");
}

// Forward Vercel requests to Laravel entry point.
require $index;
