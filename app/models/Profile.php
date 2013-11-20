<?php
class Profile extends fActiveRecord
{
  protected function configure()
  {
    fORM::registerHookCallback($this, 'post::store()', 'Profile::invalidateCache');
  }

  public static function invalidateCache($object, &$values, &$old_values, &$related_records, &$cache)
  {
    global $cache;
    $cache->delete(static::buildRealNameCacheKey($values['username']));
  }

  private static function buildRealNameCacheKey($username)
  {
    return "Profile:realname:$username";
  }

  private static $profile_cache = NULL;
  
  public static function fetch($username)
  {
    if (self::$profile_cache === NULL) {
      self::$profile_cache = array();
      $profiles = fRecordSet::build('Profile');
      foreach ($profiles as $profile) {
        self::$profile_cache[$profile->getUsername()] = array(
          'realname' => $profile->getRealname(),
          'class_name' => $profile->getClassName(),
          'phone_number' => $profile->getPhoneNumber(),
          'gender' => $profile->getGender(),
          'school' => $profile->getSchool(),
          'major' => $profile->getMajor(),
          'grade' => $profile->getGrade(),
          'qq' => $profile->getQq()
        );
      }
    }
    if (array_key_exists($username, self::$profile_cache)) {
      return self::$profile_cache[$username];
    }
    return array('realname' => '', 'class_name' => '', 'phone_number' => '',
      'gender' => '', 'school' => '', 'major' => '', 'grade' => '', 'qq' => '');
  }
  
  public static function fetchRealName($username)
  {
    profiler_instrument_begin('fetchRealName');
    $result = Profile::fetchRealName_($username);
    profiler_instrument_end('fetchRealName');
    return $result;
  }

  public static function fetchRealName_($username)
  {
    global $cache;
    $cache_key = static::buildRealNameCacheKey($username);
    $cache_value = $cache->get($cache_key);
    if ($cache_value === NULL) {
      $php_cached_profile = Profile::fetch($username);
      $cache_value = $php_cached_profile['realname'];
      $cache->set($cache_key, $cache_value);
    }
    return $cache_value;
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

  public static function fetchGender($username)
  {
    $cached_profile = Profile::fetch($username);
    return $cached_profile['gender'];
  }

  public static function fetchSchool($username)
  {
    $cached_profile = Profile::fetch($username);
    return $cached_profile['school'];
  }

  public static function fetchMajor($username)
  {
    $cached_profile = Profile::fetch($username);
    return $cached_profile['major'];
  }

  public static function fetchGrade($username)
  {
    $cached_profile = Profile::fetch($username);
    return $cached_profile['grade'];
  }

  public static function fetchQQ($username)
  {
    $cached_profile = Profile::fetch($username);
    return $cached_profile['qq'];
  }
}
