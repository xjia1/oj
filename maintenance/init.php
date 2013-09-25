<?php
require_once(__DIR__ . '/../settings.php');

require_once(__DIR__ . '/../app/vendor/flourish.php');
require_once(__DIR__ . '/../app/vendor/markdown.php');

require_once(__DIR__ . '/../cache-settings.php');

fORMDatabase::attach(new fDatabase(DB_TYPE, DB_NAME, DB_USER, DB_PASS, DB_HOST));
if (ENABLE_SCHEMA_CACHING) {
  fORM::enableSchemaCaching($cache);
}

require_once(__DIR__ . '/../app/models/Permission.php');
require_once(__DIR__ . '/../app/models/Problem.php');
require_once(__DIR__ . '/../app/models/Profile.php');
require_once(__DIR__ . '/../app/models/Question.php');
require_once(__DIR__ . '/../app/models/Record.php');
require_once(__DIR__ . '/../app/models/Registration.php');
require_once(__DIR__ . '/../app/models/Report.php');
require_once(__DIR__ . '/../app/models/User.php');
require_once(__DIR__ . '/../app/models/UserEmail.php');
require_once(__DIR__ . '/../app/models/UserStat.php');
require_once(__DIR__ . '/../app/models/Variable.php');
require_once(__DIR__ . '/../app/models/Vericode.php');

require_once(__DIR__ . '/../app/helpers/Util.php');

function script_error($message) {
  trigger_error($message);
  exit;
}

function prompt_silent($prompt) {
  $command = "/usr/bin/env bash -c 'echo OK'";
  if (rtrim(shell_exec($command)) !== 'OK') {
    script_error("Can't invoke bash");
  }
  $command = "/usr/bin/env bash -c 'read -s -p \""
    . addslashes($prompt)
    . "\" mypassword && echo \$mypassword'";
  $password = rtrim(shell_exec($command));
  echo "\n";
  return $password;
}
