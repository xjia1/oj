<?php
class ProfileController extends ApplicationController 
{
  private static $accepted_cache = NULL;
  private static $failed_cache = NULL;

  private static function calculateAccepted($username)
  {
    if (self::$accepted_cache === NULL) {
      $db = fORMDatabase::retrieve();
      $result = $db -> translatedQuery(
        'SELECT DISTINCT problem_id FROM records WHERE owner=%s AND verdict=%i ORDER BY problem_id', $username, Verdict::AC);
      self::$accepted_cache = array();
      foreach ($result as $row) {
        self::$accepted_cache[] = $row['problem_id'];
      }
    }
    return self::$accepted_cache;
  }

  private static function calculateFailed($username)
  {
    if (self::$failed_cache === NULL) {
      $db = fORMDatabase::retrieve();
      $result = $db -> translatedQuery(
        'SELECT DISTINCT problem_id FROM records WHERE owner=%s AND verdict<>%i AND problem_id NOT IN (SELECT problem_id FROM records WHERE owner=%s AND verdict=%i) ORDER BY problem_id', $username, Verdict::AC, $username, Verdict::AC);
      self::$failed_cache = array();
      foreach ($result as $row) {
        self::$failed_cache[] = $row['problem_id'];
      }
    }
    return self::$failed_cache;
  }

  public function profile($username)
  {
    if (empty($username)) {
      $username = fAuthorization::getUserToken();
    }
    $this->page_url = SITE_BASE . '/profile' . $username;
    $this->solved = self::calculateAccepted($username);
    $this->fails = self::calculateFailed($username);
    $this->username = $username;
    if (!(User::can('edit-any-profile')) and ($username != fAuthorization::getUserToken())) {
      $this->cache_control('public', 300);
    }
    $this->nav_class = 'profile';
    $this->render('user/profile');
  }
}
