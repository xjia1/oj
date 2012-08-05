<?php
class Report extends fActiveRecord
{
  protected function configure()
  {
  }
  
  public function isReadable()
  {
    return $this->getVisible() or User::can('view-any-report');
  }
  
  public function getProblems()
  {
    return preg_split('/[\s,]+/', trim($this->getProblemList()));
  }
  
  public function getUsernames()
  {
    return preg_split('/[\s,]+/', trim($this->getUserList()));
  }
  
  public function getUserPairs()
  {
    $pairs = array();
    foreach ($this->getUsernames() as $username) {
      $pairs[] = array('id' => $username, 'name' => Profile::fetchRealName($username));
    }
    return $pairs;
  }
  
  public function getElapsedRatio()
  {
    $st = $this->getStartDatetime()->format('U');
    $et = $this->getEndDatetime()->format('U');
    $ts = new fTimestamp();
    $now = min($ts->format('U'), $et);
    return round(100 * ($now - $st + 1) / ($et - $st + 1));
  }
  
  public function getDuration()
  {
    return $this->getStartDatetime()->getFuzzyDifference($this->getEndDatetime(), TRUE);
  }
}
