<?php

// Show errors in development
define('CI_ENVIRONMENT', 'development');
error_reporting(E_ALL);
ini_set('display_errors', 1);

use CodeIgniter\Boot;
use Config\Paths;

/*
 *---------------------------------------------------------------
 * CHECK PHP VERSION
 *---------------------------------------------------------------
 */
$minPhpVersion = '8.1'; // If you update this, don't forget to update `spark`.
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    $message = sprintf(
        'Your PHP version must be %s or higher to run CodeIgniter. Current version: %s',
        $minPhpVersion,
        PHP_VERSION,
    );

    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo $message;
    exit(1);
}

/*
 *---------------------------------------------------------------
 * SET THE CURRENT DIRECTORY
 *---------------------------------------------------------------
 */
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

/*
 *---------------------------------------------------------------
 * LOAD CONFIG PATHS AND COMPOSER AUTOLOADER
 *---------------------------------------------------------------
 */
require FCPATH . '../app/Config/Paths.php';       // Load CodeIgniter path config
require FCPATH . '../vendor/autoload.php';        // ✅ Load Composer's autoloader (important for mPDF and other packages)

$paths = new Paths();

/*
 *---------------------------------------------------------------
 * BOOTSTRAP THE APPLICATION
 *---------------------------------------------------------------
 */
require $paths->systemDirectory . '/Boot.php';

exit(Boot::bootWeb($paths));
