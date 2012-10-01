<?php
class ProfileController extends ApplicationController 
{

  private static $accepted_cache = NULL;

  private static $failed_cache = NULL;

  public static function accepted()
  {
    if (self::$accepted_cache === NULL) {
      $db = fORMDatabase::retrieve();
      $result = $db -> translatedQuery(
        'SELECT DISTINCT problem_id FROM records WHERE owner=%s AND verdict=%i ORDER BY problem_id',fAuthorization::getUserToken(), Verdict::AC);
      //$result->unescape(array('problem_id' => 'integer'));
      self::$accepted_cache = array();
      foreach ($result as $row) {
        self::$accepted_cache[] = $row['problem_id'];
      }
    }
    return self::$accepted_cache;
  }

  public static function failed()
  {
    if (self::$failed_cache === NULL) {
      $db = fORMDatabase::retrieve();
      $result = $db -> translatedQuery(
        'SELECT DISTINCT problem_id FROM records WHERE owner=%s AND verdict<>%i AND problem_id NOT IN (SELECT problem_id FROM records WHERE owner=%s AND verdict=%i) ORDER BY problem_id',fAuthorization::getUserToken(), Verdict::AC,fAuthorization::getUserToken(),Verdict::AC);
      //$result->unescape(array('problem_id' => 'integer'));
      self::$failed_cache = array();
      foreach ($result as $row) {
        self::$failed_cache[] = $row['problem_id'];
      }
    }
    return self::$failed_cache;
  }

  public function profile()
  {
    $this->cache_control('private', 2);
    $this->page_url = SITE_BASE . '/profile';
    $this->solved = self::accepted();
    $this->fails = self::failed();
    $this->nav_class = 'profile';
    $this->render('user/profile');
  }
}
