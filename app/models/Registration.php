<?php
class Registration extends fActiveRecord
{
  protected function configure()
  {
  }
  
  public static function has($username, $report_id)
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
}
