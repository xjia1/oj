<?php
class Question extends fActiveRecord
{
  const ABOUT_CONTEST_HIDDEN = -1;
  const ABOUT_SYSTEM_HIDDEN  = -2;
  const ABOUT_PROBLEM_HIDDEN = -3;
  
  const ABOUT_CONTEST = 1;
  const ABOUT_SYSTEM  = 2;
  const ABOUT_PROBLEM = 3;
  
  protected function configure()
  {
  }
  
  public function getCategoryName()
  {
    if (abs($this->getCategory()) == Question::ABOUT_CONTEST) {
      $name = '关于比赛';
    } else if (abs($this->getCategory()) == Question::ABOUT_SYSTEM) {
      $name = '关于系统使用';
    } else {
      $problem_id = $this->getProblemId();
      $problem_url = SITE_BASE . "/problem/{$problem_id}";
      $name = "题目：<a href=\"{$problem_url}\">{$problem_id}</a>";
    }
    if ($this->getCategory() < 0) {
      $name .= ' *';
    }
    return $name;
  }
}
