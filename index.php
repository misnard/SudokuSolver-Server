<?php
require 'vendor/autoload.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

use SudokuServer\Controller\MainController as MainController;

/**
 * @todo : clean index file to migrate into a better controller
 */

$baseUrl = 'http://'.$_SERVER['HTTP_HOST'];
$requestUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$requestString = substr($requestUrl, strlen($baseUrl));
$urlParams = explode('.', explode('/', $requestString)[1])[0];

if (!$urlParams) {
    $urlParams = 'index';
}

$actionName = strtolower($urlParams).'Action';

$MainController = new MainController($actionName, $_POST);


