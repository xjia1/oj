<?php
class UserEmail extends fActiveRecord
{
  protected function configure()
  {
    fORM::registerHookCallback($this, 'post::store()', 'UserEmail::invalidateCache');
  }
  
  public static function invalidateCache($object, &$values, &$old_values, &$related_records, &$cache)
  {
    global $cache;
    $cache->delete(static::buildCacheKey($values['username']));
  }
  
  private static function buildCacheKey($username)
  {
    return "email_{$username}";
  }
  
  private static function fetchFromDB($username)
  {
    try {
      $ue = new UserEmail($username);
      return $ue->getEmail();
    } catch (fNotFoundException $e) {
      return NULL;
    }
  }
  
  const NOT_EXIST = -1;
  
  public static function fetch($username)
  {
    global $cache;
    
    $cache_key = static::buildCacheKey($username);
    $cache_value = $cache->get($cache_key);
    if ($cache_value === NULL) {
      // cache miss
      $cache_value = static::fetchFromDB($username);
      if ($cache_value === NULL) {
        // no record in database
        $cache_value = UserEmail::NOT_EXIST;
      }
      $cache->set($cache_key, $cache_value);
    }
    if ($cache_value == UserEmail::NOT_EXIST) {
      return NULL;
    }
    return $cache_value;
  }
}
