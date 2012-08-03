<?php
$app = new Slim();

$app->get('/', function () {
  $controller = new HomeController();
  $controller->index();
});

$app->run();
