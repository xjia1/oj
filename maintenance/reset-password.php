#!/usr/bin/php
<?php
require_once(__DIR__ . '/init.php');

/**
 * @see http://httpd.apache.org/docs/2.2/misc/password_encryptions.html
 */
function hash_password($password) {
  return '{SHA}' . base64_encode(sha1($password, TRUE));
}

count($argv) >= 2 or script_error("Need one argument as the username");
$username = $argv[1];

$password = prompt_silent("Enter New Password: ");
if (strlen($password) < 6) {
  script_error('New password is too short (at least 6 characters)');
}

$repeat_password = prompt_silent("Enter New Password (again): ");
if ($password != $repeat_password) {
  script_error('Repeat password mismatch');
}

try {
  $user = new User($username);
  $user->setPassword(hash_password($password));
  $user->store();
  echo "Password reset successfully\n";
} catch (fNotFoundException $e) {
  script_error("User $username doesn't exist");
}
