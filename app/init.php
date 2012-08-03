<?php
fSession::setPath(SESSIONS_PATH);
fSession::setLength('1 day 2 hours');
fORMDatabase::attach(new fDatabase('mysql', DB_NAME, DB_USER, DB_PASS, DB_HOST));
fAuthorization::setLoginPage(SITE_BASE . '/login');
