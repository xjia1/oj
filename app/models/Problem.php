<?php
class Problem extends fActiveRecord
{
  protected function configure()
  {
    //
  }
  
  public static function find($view_any, $page, $title, $author)
  {
    $conditions = array();
		if (!$view_any) {
			$conditions['secret_before<='] = Util::currentTime();
		}
		if (strlen($title)) {
		  $conditions['title~'] = $title;
		}
		if (strlen($author)) {
			$conditions['author~'] = $author;
		}
		$limit = Variable::getInteger('problems-per-page', 10);
		return fRecordSet::build('Problem', $conditions, array('id' => 'asc'), $limit, $page);
  }
  
  private static function populateCountCache(&$count_cache, $result)
  {
    $count_cache = array();
    foreach ($result as $row) {
      $count_cache[$row['problem_id']] = $row['count'];
    }
  }
  
  private static $accept_count_cache;
  private static $submit_count_cache;
  
  private static function ensureCountCaches()
  {
    $db = fORMDatabase::retrieve();
    if (self::$accept_count_cache == null) {
      $result = $db->translatedQuery(
        'SELECT problem_id, COUNT(1) AS count FROM records WHERE verdict=%i GROUP BY problem_id', Verdict::AC);
      $result->unescape(array('problem_id' => 'integer', 'count' => 'integer'));
      static::populateCountCache(self::$accept_count_cache, $result);
    }
    if (self::$submit_count_cache == null) {
      $result = $db->translatedQuery('SELECT problem_id, COUNT(1) AS count FROM records GROUP BY problem_id');
      $result->unescape(array('problem_id' => 'integer', 'count' => 'integer'));
      static::populateCountCache(self::$submit_count_cache, $result);
    }
  }
  
  private function getCachedCount(&$count_cache)
  {
    if (array_key_exists($this->getId(), $count_cache)) {
      return $count_cache[$this->getId()];
    }
    return 0;
  }
  
  public function getAcceptCount()
  {
    static::ensureCountCaches();
    return $this->getCachedCount(self::$accept_count_cache);
  }
  
  public function getSubmitCount()
  {
    static::ensureCountCaches();
    return $this->getCachedCount(self::$submit_count_cache);
  }
  
  public function getRatio()
  {
    $submit = $this->getSubmitCount();
    if ($submit == 0) {
      return 0;
    }
    $accept = $this->getAcceptCount();
    return round(100 * $accept / $submit);
  }
  
  public function isSecretNow()
  {
    return Util::currentTime() < $this->getSecretBefore();
  }
}
