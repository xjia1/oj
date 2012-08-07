<?php
error_reporting(E_ALL & ~E_NOTICE);
date_default_timezone_set('Asia/Shanghai');
set_time_limit(600);  // 10 minutes

define('SQL_DEBUG', FALSE);

// OJ database
define('DB_NAME', 'online_judge');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_HOST', 'localhost');

// Judger secret
define('JUDGER_SECRET', 'secret');

// Ranklist secret
define('RANKLIST_SECRET', 'secret');

// Misc.
define('HOST_URL', 'http://localhost');
define('SITE_BASE', '/oj');
define('TITLE_SUFFIX', ' | Online Judge');

define('SESSIONS_PATH', '/tmp');
