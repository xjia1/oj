<?php
$app = new Slim();

$app->get('/', function () {
  Util::redirect('/home');
});

$app->get('/home', function () {
  $controller = new HomeController();
  $controller->index();
});

$app->get('/sets', function () {
  $controller = new HomeController();
  $controller->showProblemSets();
});

$app->get('/problems', function () {
  $controller = new ProblemController();
  $controller->index();
});

$app->get('/submit', function () {
  $controller = new SubmitController();
  $controller->index();
});

$app->post('/submit', function () {
  $controller = new SubmitController();
  $controller->submit(fRequest::get('problem', 'integer'));
});

$app->get('/status', function () {
  $controller = new RecordController();
  $controller->index();
});

$app->get('/reports', function () {
  $controller = new ReportController();
  $controller->index();
});

$app->get('/dashboard', function () {
  $controller = new DashboardController();
  $controller->index();
});

$app->get('/problem', function () {
  Util::redirect('/problem/' . fRequest::get('id', 'integer'));
});

$app->get('/problem/:id', function ($id) {
  $controller = new ProblemController();
  $controller->show($id);
});

$app->get('/record', function () {
  Util::redirect('/record/' . fRequest::get('id', 'integer'));
});

$app->get('/record/:id', function ($id) {
  $controller = new RecordController();
  $controller->show($id);
});

$app->get('/report', function () {
  Util::redirect('/report/' . fRequest::get('id', 'integer'));
});

$app->get('/report/:id', function ($id) {
  $controller = new ReportController();
  $controller->show($id);
});

$app->run();
