<?php
require_once(__DIR__ . '/vendor/flourish.php');
require_once(__DIR__ . '/vendor/slim.php');
require_once(__DIR__ . '/vendor/markdown.php');

require_once(__DIR__ . '/init.php');

//require_once(__DIR__ . '/models/Something.php');

require_once(__DIR__ . '/controllers/ApplicationController.php');
require_once(__DIR__ . '/controllers/HomeController.php');
require_once(__DIR__ . '/controllers/ProblemController.php');
require_once(__DIR__ . '/controllers/RecordController.php');
require_once(__DIR__ . '/controllers/ReportController.php');
require_once(__DIR__ . '/controllers/SubmitController.php');
require_once(__DIR__ . '/controllers/DashboardController.php');

require_once(__DIR__ . '/helpers/Util.php');

require_once(__DIR__ . '/routes.php');
