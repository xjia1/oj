<?php
class UserStat extends fActiveRecord
{
  protected function configure()
  {
  }

  private static $stat_cache = NULL;

  public static function fetchStat($username)
  {
    if (self::$stat_cache === NULL) {
      self::$stat_cache = array();
      $stats = fRecordSet::build('UserStat');
      foreach ($stats as $stat) {
        self::$stat_cache[$stat->getUsername()] = array(
          'solved' => $stat->getSolved(),
          'tried' => $stat->getTried(),
          'submissions' => $stat->getSubmissions()
        );
      }
    }
    if (array_key_exists($username,self::$stat_cache)) {
        return self::$stat_cache[$username];
    }
    return array('solved' => '0', 'tried' => '0', 'submissions' => '0');
  }

  public static function fetchSolved($username)
  {
    $cached_stat = UserStat::fetchStat($username);
    return $cached_stat['solved'];
  }
  public static function fetchTried($username)
  {
    $cached_stat = UserStat::fetchStat($username);
    return $cached_stat['tried'];
  }
  public static function fetchSubmissions($username)
  {
    $cached_stat = UserStat::fetchStat($username);
    return $cached_stat['submissions'];
  }

}
