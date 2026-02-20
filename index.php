<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/autoload.php';
require __DIR__ . '/config.php';
require __DIR__ . '/testi.php';

$app = Application::instance(); 
$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$routerConf = new RouterConfigurator($app);
$routerConf->bootstrap();

$app->setBasePath(BASE_PATH);

$app->add(PageMiddleware::class);
$app->add(MenuFooterMiddleware::class);
$app->add(UIMessageMiddleware::class);
$app->add(AuthMiddleware::class);
$app->add(SessionMiddleware::class);

$page = new Page();
PageConfigurator::instance()->setPage($page);

$app->run();

