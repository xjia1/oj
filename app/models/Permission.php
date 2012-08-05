<?php
class Permission extends fActiveRecord
{
  protected function configure()
  {
  }
  
  private static $permission_cache = array();
  
  public static function contains($user_name, $permission_name)
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
}
