<?php
class Profile extends fActiveRecord
{
  protected function configure()
  {
  }
  
  private static $realname_cache;
  
  public static function fetchRealName($username)
  {
    if (self::$realname_cache == NULL) {
      self::$realname_cache = array();
      $profiles = fRecordSet::build('Profile');
      foreach ($profiles as $profile) {
        self::$realname_cache[$profile->getUsername()] = $profile->getRealname();
      }
    }
    if (array_key_exists($username, self::$realname_cache)) {
      return self::$realname_cache[$username];
    }
    return '';
  }
}
