<?php
class User extends fActiveRecord
{
  protected function configure()
  {
    //
  }
  
  public static function can($permission_name)
  {
    return Permission::contains(fAuthorization::getUserToken(), $permission_name);
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
