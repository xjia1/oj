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
}
