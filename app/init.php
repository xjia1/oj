<?php
require(__DIR__ . '/translate.php');
fText::registerComposeCallback('pre', 'translate');
fSession::setBackend($cache, 'OJSESS');
fSession::setLength('1 day');
fSession::open(); // it clears all headers and will be destroyed if not necessary
fORMDatabase::attach(new fDatabase(DB_TYPE, DB_NAME, DB_USER, DB_PASS, DB_HOST));
if (ENABLE_SCHEMA_CACHING) {
  fORM::enableSchemaCaching($cache);
}
fORMDatabase::retrieve()->registerHookCallback('run', 'profiler_log_sql');
fAuthorization::setLoginPage(SITE_BASE . '/login');
