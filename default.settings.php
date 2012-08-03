<?php
error_reporting(E_ALL & ~E_NOTICE);
date_default_timezone_set('Asia/Shanghai');
set_time_limit(600);  // 10 minutes

// OJ database
define('DB_NAME', 'online_judge');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_HOST', 'localhost');

// User information database (`users`)
define('UDB_NAME', 'online_judge');
define('UDB_USER', 'root');
define('UDB_PASS', '');
define('UDB_HOST', 'localhost');

// Misc.
define('HOST_URL', 'http://localhost');
define('SITE_BASE', '/oj');
define('TITLE_SUFFIX', ' | Online Judge');
