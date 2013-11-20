<?php
class Report extends fActiveRecord
{
  protected function configure()
  {
  }

  public function isHomework()
  {
    return (stripos($this->getTitle(), 'homework') !== FALSE)
        or (strpos($this->getTitle(), '作业') !== FALSE);
  }
  
  public function isReadable()
  {
    return $this->getVisible() or User::can('view-any-report');
  }
  
  public function isRunning()
  {
    return $this->isStarted() and !$this->isFinished();
  }
  
  public function isStarted()
  {
    return $this->getStartDatetime()->lte(new fTimestamp());
  }
  
  public function isFinished()
  {
    return $this->getEndDatetime()->lt(new fTimestamp());
  }
  
  /**
   * (1) user_list 为空才开放注册
   * (2) 注册截止比赛结束时
   * (3) 只能注册一次
   */
  public function isRegistrable()
  {
    if (strlen(trim($this->getUserList())) > 0) {
      return FALSE;
    }
    if ($this->getEndDatetime()->lt(new fTimestamp())) {
      return FALSE;
    }
    if (Registration::has(fAuthorization::getUserToken(), $this->getId())) {
      return FALSE;
    }
    return TRUE;
  }
  
  public function getProblems()
  {
    return preg_split('/[\s,]+/', trim($this->getProblemList()));
  }
  
  private function checkAuthor($username)
  {
    foreach ($this->getProblems() as $problem_id) {
      try {
        $problem = new Problem($problem_id);
        if ($problem->getAuthor() == $username) {
          return TRUE;
        }
      } catch (Exception $e) {
        continue;
      }
    }
    return FALSE;
  }

  public function countRegistrants()
  {
    return count($this->getUsernames());
  }

  /**
   * 如果 user_list 为空，则统计 registrations
   * 否则只统计 user_list
   */
  public function getUsernames()
  {
    if (strlen(trim($this->getUserList())) == 0) {
      return $this->registeredUsers();
    }
    return preg_split('/[\s,]+/', trim($this->getUserList()));
  }
  
  private function registeredUsers()
  {
    $usernames = array();
    $registrations = fRecordSet::build('Registration', array('report_id=' => $this->getId()));
    foreach ($registrations as $registration) {
      $usernames[] = $registration->getUsername();
    }
    return $usernames;
  }
  
  private function checkRegistered($username)
  {
    return in_array($username, $this->getUsernames());
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
    return max(round(100 * ($now - $st + 1) / ($et - $st + 1)), 0);
  }
  
  public function getDuration()
  {
    return $this->getStartDatetime()->getFuzzyDifference($this->getEndDatetime(), TRUE);
  }
  
  public function getBoardCacheKey()
  {
    return 'report_' . $this->getId() . '_board';
  }
  
  /**
   *  - 管理员和题目作者可以看到所有提问
   *  - 普通用户只能看到被回复的提问，以及自己的提问
   */
  public function fetchQuestions()
  {
    if ($this->allowAnswer()) {
      $conditions = array('report_id=' => $this->getId());
    } else {
      $conditions = array(
        'report_id=' => $this->getId(),
        'category>|username=' => array(0, fAuthorization::getUserToken())
      );
    }
    return fRecordSet::build('Question', $conditions);
  }
  
  public function allowAnswer()
  {
    return User::isSuper() or $this->checkAuthor(fAuthorization::getUserToken());
  }
  
  public function allowQuestion()
  {
    return $this->isRunning() and (User::isSuper() or $this->checkRegistered(fAuthorization::getUserToken()));
  }
}
