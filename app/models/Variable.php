<?php
class Variable extends fActiveRecord
{
  protected function configure()
  {
    fORM::registerHookCallback($this, 'post::store()', 'Variable::invalidateCache');
  }
  
  public static function invalidateCache($object, &$values, &$old_values, &$related_records, &$cache)
  {
    global $cache;
    $cache->delete(static::buildCacheKey($values['name']));
  }
  
  private static function buildCacheKey($name)
  {
    return "var_{$name}";
  }
  
  private static function fetch($name, $default)
  {
    global $cache;
    
    $cache_key = static::buildCacheKey($name);
    $cache_value = $cache->get($cache_key);
    if ($cache_value === NULL) {
      try {
        $var = new Variable($name);
        $cache_value = $var->getValue();
      } catch (fNotFoundException $e) {
        $cache_value = $default;
      }
      $cache->set($cache_key, $cache_value);
    }
    return $cache_value;
  }
  
  public static function getString($name, $default='')
  {
    return '' . static::fetch($name, $default);
  }

  public static function getInteger($name, $default=0)
  {
    return 0 + static::fetch($name, $default);
  }
}
