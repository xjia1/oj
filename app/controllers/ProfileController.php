<?php
class ProfileController extends ApplicationController 
{
  private static $accepted_cache = NULL;

  private static $failed_cache = NULL;

  private static function calculateaccepted()
  {
    if (self::$accepted_cache === NULL) {
      $db = fORMDatabase::retrieve();
      $result = $db -> translatedQuery(
        'SELECT DISTINCT problem_id FROM records WHERE owner=%s AND verdict=%i ORDER BY problem_id',fAuthorization::getUserToken(), Verdict::AC);
      self::$accepted_cache = array();
      foreach ($result as $row) {
        self::$accepted_cache[] = $row['problem_id'];
      }
    }
    return self::$accepted_cache;
  }

  public static function calculatefailed()
  {
    if (self::$failed_cache === NULL) {
      $db = fORMDatabase::retrieve();
      $result = $db -> translatedQuery(
        'SELECT DISTINCT problem_id FROM records WHERE owner=%s AND verdict<>%i AND problem_id NOT IN (SELECT problem_id FROM records WHERE owner=%s AND verdict=%i) ORDER BY problem_id',fAuthorization::getUserToken(), Verdict::AC,fAuthorization::getUserToken(),Verdict::AC);
      self::$failed_cache = array();
      foreach ($result as $row) {
        self::$failed_cache[] = $row['problem_id'];
      }
    }
    return self::$failed_cache;
  }

  public function profile($username)
  {
    $this->cache_control('private', 2);
    $this->page_url = SITE_BASE . '/profile';
    $this->solved = self::calculateaccepted();
    $this->fails = self::calculatefailed();
    $this->username = $username;
    $this->nav_class = 'profile';
    $this->render('user/profile');
  }
}
