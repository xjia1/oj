<?php
class Permission extends fActiveRecord
{
  protected function configure()
  {
  }
  
  public static function contains($user_name, $permission_name)
  {
    try {
      fRecordSet::build('Permission', array(
        'user_name=' => $user_name,
        'permission_name=' => $permission_name
      ))->tossIfEmpty();
      return true;
    } catch (fEmptySetException $e) {
      return false;
    }
  }
}
