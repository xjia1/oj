<?php
fSession::setBackend($cache, 'OJSESS');
fSession::setLength('1 day');
fORMDatabase::attach(new fDatabase(DB_TYPE, DB_NAME, DB_USER, DB_PASS, DB_HOST));
if (ENABLE_SCHEMA_CACHING) {
  fORM::enableSchemaCaching($cache);
}
fORMDatabase::retrieve()->registerHookCallback('run', 'profiler_log_sql');
fAuthorization::setLoginPage(SITE_BASE . '/login');
