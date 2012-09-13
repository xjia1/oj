<?php
require_once(__DIR__ . '/vendor/flourish.php');
require_once(__DIR__ . '/vendor/slim.php');
require_once(__DIR__ . '/vendor/markdown.php');

require_once(__DIR__ . '/../cache-settings.php');
require_once(__DIR__ . '/init.php');

require_once(__DIR__ . '/models/Permission.php');
require_once(__DIR__ . '/models/Problem.php');
require_once(__DIR__ . '/models/Profile.php');
require_once(__DIR__ . '/models/Record.php');
require_once(__DIR__ . '/models/Registration.php');
require_once(__DIR__ . '/models/Report.php');
require_once(__DIR__ . '/models/User.php');
require_once(__DIR__ . '/models/UserEmail.php');
require_once(__DIR__ . '/models/UserStat.php');
require_once(__DIR__ . '/models/Variable.php');
require_once(__DIR__ . '/models/Vericode.php');

require_once(__DIR__ . '/controllers/ApplicationController.php');
require_once(__DIR__ . '/controllers/HomeController.php');
require_once(__DIR__ . '/controllers/ProblemController.php');
require_once(__DIR__ . '/controllers/RecordController.php');
require_once(__DIR__ . '/controllers/ReportController.php');
require_once(__DIR__ . '/controllers/SubmitController.php');
require_once(__DIR__ . '/controllers/DashboardController.php');
require_once(__DIR__ . '/controllers/UserController.php');
require_once(__DIR__ . '/controllers/PollingController.php');

require_once(__DIR__ . '/helpers/Util.php');
require_once(__DIR__ . '/helpers/Verdict.php');
require_once(__DIR__ . '/helpers/JudgeStatus.php');
require_once(__DIR__ . '/helpers/ReportGenerator.php');
require_once(__DIR__ . '/helpers/BoardTable.php');
require_once(__DIR__ . '/helpers/BoardCacheInvalidator.php');

require_once(__DIR__ . '/routes.php');
