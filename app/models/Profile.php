<?php
class Profile extends fActiveRecord
{
  protected function configure()
  {
    //
  }
  
  public static function fetchRealName($username)
  {
    try {
      $profile = new Profile($username);
      return $profile->getRealname();
    } catch (fNotFoundException $e) {
      return '';
    }
  }
}
