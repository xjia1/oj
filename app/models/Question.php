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
}
