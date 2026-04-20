<?php
// Function to load .env variables manually since we aren't using Composer/phpdotenv
function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}

// Load .env from project root
loadEnv(__DIR__ . '/../.env');

// Set error reporting based on environment
$appEnv = getenv('APP_ENV') ?: 'production';
if ($appEnv === 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

define('BASE_URL', getenv('BASE_URL') ?: 'http://localhost/EducationAgencyManagementSystem/');

function base_url($path = '')
{
    return BASE_URL . ltrim($path, '/');
}


$servername = getenv('DB_HOST') ?: "localhost";
$username = getenv('DB_USER') ?: "root";
$password = getenv('DB_PASS') ?: "";
$dbname = getenv('DB_NAME') ?: "unipath_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    if ($appEnv === 'development') {
        die("Connection failed: " . mysqli_connect_error());
    } else {
        die("Database connection failed. Please try again later.");
    }
}
?>