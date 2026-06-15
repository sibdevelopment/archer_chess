<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Fix subfolder install request path
|--------------------------------------------------------------------------
|
| App is served from /projects/archerkids but public/index.php lives inside
| /projects/archerkids/public. We strip the subfolder prefix from REQUEST_URI
| before Laravel captures the request, so routes can stay normal: "/", "/about"
| etc.
|
*/

$mountPath = '/projects/archerkids';

if (isset($_SERVER['REQUEST_URI']) && str_starts_with($_SERVER['REQUEST_URI'], $mountPath)) {
    $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], strlen($mountPath)) ?: '/';
}

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);