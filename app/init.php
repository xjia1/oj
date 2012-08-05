<?php
fSession::setPath(SESSIONS_PATH);
fSession::setLength('1 day 2 hours');
fORMDatabase::attach(new fDatabase('mysql', DB_NAME, DB_USER, DB_PASS, DB_HOST));
if (function_exists('apc_cache_info') && APC_ENABLED && ENABLE_SCHEMA_CACHING) {
  fORM::enableSchemaCaching(new fCache('apc'));
}
fORMDatabase::retrieve()->registerHookCallback('run', 'profiler_log_sql');
fAuthorization::setLoginPage(SITE_BASE . '/login');
