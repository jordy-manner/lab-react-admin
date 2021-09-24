<?php

use App\App;
use Pollen\Http\Request;
use Pollen\Kernel\KernelInterface;

error_reporting(E_ALL);
ini_set('display_errors', 1);

defined('START_TIME') ?: define('START_TIME', microtime(true));

require_once dirname(__DIR__) . '/vendor/autoload.php';

$app = new App(dirname(__DIR__));

$kernel = $app->get(KernelInterface::class);
$response = $kernel->handle($request = Request::getFromGlobals());

$kernel->send($response);
$kernel->terminate($request, $response);

