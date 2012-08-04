<?php
class User extends fActiveRecord
{
  protected function configure()
  {
    //
  }
  
  private static $permission_cache = array();
  
  public static function can($permission_name)
  {
    if (array_key_exists($permission_name, self::$permission_cache)) {
      return $permission_cache[$permission_name];
    }
    return $permission_cache[$permission_name] = Permission::contains(fAuthorization::getUserToken(), $permission_name);
  }
  
  private static $accepted_cache;
  
  public static function hasAccepted($problem)
  {
    if (self::$accepted_cache == null) {
      $db = fORMDatabase::retrieve();
      $result = $db->translatedQuery(
        'SELECT DISTINCT problem_id FROM records WHERE owner=%s AND verdict=%i', fAuthorization::getUserToken(), Verdict::AC);
      $result->unescape(array('problem_id' => 'integer'));
      self::$accepted_cache = array();
      foreach ($result as $row) {
        self::$accepted_cache[] = $row['problem_id'];
      }
    }
    return in_array($problem->getId(), self::$accepted_cache);
  }
}
