<?php
class Report extends fActiveRecord
{
  protected function configure()
  {
    //
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
}
