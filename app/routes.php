<?php
$app = new Slim();

$app->get('/', function () {
  Util::redirect('/home');
});

$app->get('/email/verify', function () {
  $controller = new UserController();
  $controller->emailVerify();
});

$app->post('/email/verify', function () {
  $controller = new UserController();
  $controller->sendVericode();
});

$app->get('/email/verify/sent', function () {
  $controller = new UserController();
  $controller->vericodeSent();
});

$app->get('/email/vericode/:id/:vericode', function ($id, $vericode) {
  $controller = new UserController();
  $controller->checkVericode($id, $vericode);
});

$app->get('/login', function () {
  $controller = new UserController();
  $controller->showLoginPage();
});

$app->post('/login', function () {
  $controller = new UserController();
  $controller->login();
});

$app->get('/logout', function () {
  fAuthorization::requireLoggedIn();
  $controller = new UserController();
  $controller->logout();
});

$app->get('/change/info', function () {
  fAuthorization::requireLoggedIn();
  $controller = new UserController();
  $controller->changeInfo();
});

$app->post('/change/info', function () {
  fAuthorization::requireLoggedIn();
  $controller = new UserController();
  $controller->updateInfo();
});

$app->get('/change/password', function () {
  fAuthorization::requireLoggedIn();
  $controller = new UserController();
  $controller->changePassword();
});

$app->post('/change/password', function () {
  fAuthorization::requireLoggedIn();
  $controller = new UserController();
  $controller->updatePassword();
});

$app->get('/home', function () {
  $controller = new HomeController();
  $controller->index();
});

$app->get('/sets', function () {
  $controller = new HomeController();
  $controller->showProblemSets();
});

$app->get('/page/:name', function ($name) {
  $controller = new HomeController();
  $controller->showPage($name);
});

$app->get('/problems', function () {
  $controller = new ProblemController();
  $controller->index();
});

$app->get('/submit', function () {
  fAuthorization::requireLoggedIn();
  User::requireEmailVerified();
  $controller = new SubmitController();
  $controller->index();
});

$app->post('/submit', function () {
  fAuthorization::requireLoggedIn();
  $controller = new SubmitController();
  $controller->submit(fRequest::get('problem', 'integer'));
});

$app->get('/status', function () {
  $controller = new RecordController();
  $controller->index();
});

$app->get('/ranklist', function () {
  $controller = new UserController();
  $controller->ranklist();
});

$app->get('/contests', function () {
  User::requireEmailVerified();
  $controller = new ReportController();
  $controller->index();
});

$app->get('/reports', function () {
  fAuthorization::requireLoggedIn();
  User::requireEmailVerified();
  $controller = new ReportController();
  $controller->index();
});

$app->get('/dashboard', function () {
  fAuthorization::requireLoggedIn();
  $controller = new DashboardController();
  $controller->index();
});

$app->post('/dashboard/problems', function () {
  fAuthorization::requireLoggedIn();
  $controller = new DashboardController();
  $controller->manageProblem(fRequest::get('id', 'integer'), fRequest::get('action', 'string'));
});

$app->post('/dashboard/rejudge', function () {
  fAuthorization::requireLoggedIn();
  $controller = new DashboardController();
  $controller->rejudge(fRequest::get('id', 'integer'));
});

$app->post('/dashboard/manjudge', function () {
  fAuthorization::requireLoggedIn();
  $controller = new DashboardController();
  $controller->manjudge(fRequest::get('id', 'integer'), fRequest::get('score', 'integer'));
});

$app->post('/reports', function () {
  fAuthorization::requireLoggedIn();
  $controller = new DashboardController();
  $controller->createReport();
});

$app->post('/dashboard/reports', function () {
  fAuthorization::requireLoggedIn();
  $controller = new DashboardController();
  $controller->manageReport(fRequest::get('id', 'integer'), fRequest::get('action', 'string'));
});

$app->post('/dashboard/permissions', function () {
  fAuthorization::requireLoggedIn();
  $controller = new DashboardController();
  $controller->managePermission(fRequest::get('action', 'string'));
});

$app->post('/set/variable', function () {
  fAuthorization::requireLoggedIn();
  $controller = new DashboardController();
  $controller->setVariable();
});

$app->get('/problem', function () {
  $controller = new ProblemController();
  $controller->show(fRequest::get('id', 'integer'));
});

$app->get('/problem/:id', function ($id) {
  $controller = new ProblemController();
  $controller->show($id);
});

$app->get('/record', function () {
  fAuthorization::requireLoggedIn();
  User::requireEmailVerified();
  $controller = new RecordController();
  $controller->show(fRequest::get('id', 'integer'));
});

$app->get('/record/:id', function ($id) {
  fAuthorization::requireLoggedIn();
  User::requireEmailVerified();
  $controller = new RecordController();
  $controller->show($id);
});

$app->get('/report', function () {
  fAuthorization::requireLoggedIn();
  User::requireEmailVerified();
  $controller = new ReportController();
  $controller->show(fRequest::get('id', 'integer'));
});

$app->get('/report/:id', function ($id) {
  fAuthorization::requireLoggedIn();
  User::requireEmailVerified();
  $controller = new ReportController();
  $controller->show($id);
});

$app->get('/contest/:id', function ($id) {
  User::requireEmailVerified();
  $controller = new ReportController();
  $controller->show($id);
});

$app->get('/contest/:id/register', function ($id) {
  Util::redirect("/contest/{$id}");
});

$app->post('/contest/:id/register', function ($id) {
  fAuthorization::requireLoggedIn();
  User::requireEmailVerified();
  $controller = new ReportController();
  $controller->newRegistration($id);
});

$app->post('/contest/:id/question', function ($id) {
  fAuthorization::requireLoggedIn();
  User::requireEmailVerified();
  $controller = new ReportController();
  $controller->newQuestion($id);
});

$app->get('/polling/:secret', function ($secret) {
  if (JUDGER_SECRET != $secret) exit();
  
  $controller = new PollingController();
  $opcode = fRequest::get('opcode', 'string');
  if ($opcode == 'fetchRecord') {
    $controller->fetchRecord();
  } else if ($opcode == 'fetchTimestamp') {
    $controller->fetchTimestamp();
  } else {
    echo -1;
  }
});

$app->post('/polling/:secret', function ($secret) {
  if (JUDGER_SECRET != $secret) exit();
  
  $controller = new PollingController();
  $controller->updateJudgeStatus();
});

$app->run();
