<?php
class Record extends fActiveRecord
{
  protected function configure()
  {
    fORM::registerHookCallback($this, 'post::store()', 'Record::invalidateBoardCache');
  }
  
  public static function invalidateBoardCache($object, &$values, &$old_values, &$related_records, &$cache)
  {
    BoardCacheInvalidator::invalidate($values['owner'], $values['problem_id'], $values['submit_datetime']);
  }
  
  public static function find($top, $owner, $problem_id, $language, $verdict)
  {
    $conditions = array();
    if (!empty($top)) {
      $conditions['id<='] = $top;
    }
    if (strlen($owner)) {
      $conditions['owner='] = $owner;
    }
    if (strlen($problem_id)) {
      $conditions['problem_id='] = $problem_id;
    }
    if (!empty($language)) {
      $conditions['code_language='] = $language - 1;
    }
    if (!empty($verdict)) {
      $conditions['verdict='] = $verdict;
    }
		$limit = Variable::getInteger('records-per-page', 50);
		return fRecordSet::build('Record', $conditions, array('id' => 'desc'), $limit);
  }
  
  public function getLanguageName()
  {
    return SubmitController::$languages[$this->getCodeLanguage()];
  }
  
  private static $regexPattern = "/\\w+\\s\\(Time:\\s(?P<time>\\d+)ms,\\sMemory:\\s(?P<memory>\\d+)kb\\)/";
  private static $acceptPattern = "/Accepted\\s\\(Time:\\s(\\d+)ms,\\sMemory:\\s(\\d+)kb\\)/";
  private static $manjudgePattern = "/Manually\\s+judged\\s+to\\s+(?P<score>\\d+)\\s+points./";
  
  public function getTimeCost()
  {
    if (preg_match_all(self::$regexPattern, $this->getJudgeMessage(), $matches)) {
      return array_sum($matches['time']) . 'ms';
    }
    return '-';
  }
  
  public function getMemoryCost()
  {
    if (preg_match_all(self::$regexPattern, $this->getJudgeMessage(), $matches)) {
      return array_sum($matches['memory']) . 'kb';
    }
    return '-';
  }
  
  public function getResult()
  {
    if ($this->getJudgeStatus() == JudgeStatus::DONE) {
      return Verdict::$NAMES[$this->getVerdict()];
    }
    return JudgeStatus::$NAMES[$this->getJudgeStatus()];
  }
  
  public function isReadable()
  {
    return fAuthorization::getUserToken() == $this->getOwner() or User::can('view-any-record');
  }
  
  private function getManjudgeScore()
  {
    if (preg_match(self::$manjudgePattern, $this->getJudgeMessage(), $matches)) {
      return $matches['score'];
    }
    return NULL;
  }
  
  public function manjudge($score)
  {
    $append_msg = "Manually judged to {$score} points.";
    $manjudge_score = $this->getManjudgeScore();
    if ($manjudge_score == NULL) {
      $this->setJudgeMessage($this->getJudgeMessage() . "\n{$append_msg}");
    } else {
      $this->setJudgeMessage(preg_replace(self::$manjudgePattern, $append_msg, $this->getJudgeMessage()));
    }
  }
  
  private function getScore_()
  {
    try {
      $manjudge_score = $this->getManjudgeScore();
      if ($manjudge_score != NULL) {
        return $manjudge_score;
      }
      $accept_num = preg_match_all(self::$acceptPattern, $this->getJudgeMessage(), $matches);
      return $accept_num * $this->getProblem()->getCaseScore();
    } catch (fException $e) {
      return 0;
    }
  }
  
  private $score = NULL;
  
  public function getScore()  // cached
  {
    if ($this->score == NULL) {
      $this->score = $this->getScore_();
    }
    return $this->score;
  }
  
  public function getProblem()
  {
    return new Problem($this->getProblemId());
  }
}
