<?php
class Permission extends fActiveRecord
{
  protected function configure()
  {
    fORM::registerHookCallback($this, 'post::store()', 'Permission::invalidateCache');
  }
  
  public static function invalidateCache($object, &$values, &$old_values, &$related_records, &$cache)
  {
    global $cache;
    $cache->delete(static::buildCacheKey($values['user_name'], $values['permission_name']));
  }
  
  private static function buildCacheKey($user_name, $permission_name)
  {
    return "perm_{$user_name}_{$permission_name}";
  }
  
  private static $permission_cache = array();
  
  private static function fetchFromDB($user_name, $permission_name)
  {
    if (!array_key_exists($user_name, self::$permission_cache)) {
      self::$permission_cache[$user_name] = array();
      $permissions = fRecordSet::build('Permission', array('user_name=' => $user_name));
      foreach ($permissions as $permission) {
        self::$permission_cache[$user_name][] = $permission->getPermissionName();
      }
    }
    return in_array($permission_name, self::$permission_cache[$user_name]);
  }
  
  public static function contains($user_name, $permission_name)
  {
    global $cache;
    
    $cache_key = static::buildCacheKey($user_name, $permission_name);
    $cache_value = $cache->get($cache_key);
    if ($cache_value === NULL) {
      // cache miss
      $cache_value = static::fetchFromDB($user_name, $permission_name);
      $cache->set($cache_key, $cache_value);
    }
    return $cache_value;
  }
}
