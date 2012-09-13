<?php
class Profile extends fActiveRecord
{
  protected function configure()
  {
  }
  
  private static $profile_cache;
  
  public static function fetch($username)
  {
    if (self::$profile_cache == NULL) {
      self::$profile_cache = array();
      $profiles = fRecordSet::build('Profile');
      foreach ($profiles as $profile) {
        self::$profile_cache[$profile->getUsername()] = array(
          'realname' => $profile->getRealname(),
          'class_name' => $profile->getClassName(),
          'phone_number' => $profile->getPhoneNumber()
        );
      }
    }
    if (array_key_exists($username, self::$profile_cache)) {
      return self::$profile_cache[$username];
    }
    return array('realname' => '', 'class_name' => '', 'phone_number' => '');
  }
  
  public static function fetchRealName($username)
  {
    $cached_profile = Profile::fetch($username);
    return $cached_profile['realname'];
  }
  
  public static function fetchClassName($username)
  {
    $cached_profile = Profile::fetch($username);
    return $cached_profile['class_name'];
  }
  
  public static function fetchPhoneNumber($username)
  {
    $cached_profile = Profile::fetch($username);
    return $cached_profile['phone_number'];
  }
}
