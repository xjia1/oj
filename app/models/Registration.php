<?php
class Registration extends fActiveRecord
{
  protected function configure()
  {
    fORM::registerHookCallback($this, 'post::store()', 'Registration::invalidateCache');
  }
  
  public static function invalidateCache($object, &$values, &$old_values, &$related_records, &$cache)
  {
    global $cache;
    
    $cache_key = static::buildCacheKey($values['username'], $values['report_id']);
    $cache->delete($cache_key);

    $cache_key = Report::buildRegistrantsCountCacheKey($values['report_id']);
    $cache->delete($cache_key);
  }
  
  private static function fetchAndCheck($username, $report_id)
  {
    try {
      $registration = new Registration(array(
        'username' => $username,
        'report_id' => $report_id
      ));
      return true;
    } catch (fNotFoundException $e) {
      return false;
    }
  }
  
  private static function buildCacheKey($username, $report_id)
  {
    return "reg_{$username}_{$report_id}";
  }
  
  public static function has($username, $report_id)
  {
    global $cache;
    
    $cache_key = static::buildCacheKey($username, $report_id);
    $cache_value = $cache->get($cache_key);
    if ($cache_value === NULL) {
      // cache miss
      $cache_value = static::fetchAndCheck($username, $report_id);
      $cache->set($cache_key, $cache_value);
    }
    return $cache_value;
  }
}
