<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
echo "PHP is working!<br>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Extensions available: " . implode(', ', get_loaded_extensions()) . "<br>";
echo "Postgres support: " . (extension_loaded('pdo_pgsql') ? 'YES' : 'NO') . "<br>";
echo "VERCEL ENV: " . getenv('VERCEL') . "<br>";
echo "Storage Path Attempt: /tmp/storage<br>";
if (!is_dir('/tmp/storage')) {
    if (mkdir('/tmp/storage', 0755, true)) {
        echo "Successfully created /tmp/storage<br>";
    } else {
        echo "Failed to create /tmp/storage<br>";
    }
} else {
    echo "/tmp/storage already exists<br>";
}
