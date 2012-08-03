<?php
class SubmitController extends ApplicationController
{
  public function index()
  {
    $this->current_language = fSession::get('last_language', 0);
    if (fMessaging::check('code', '/submit')) {
      $this->code = fMessaging::retrieve('code', '/submit');
    } else {
      $this->code = '';
    }
    $this->render('submit/index');
  }
  
  public $languages = array('C++', 'C', 'Java');
  
  public function submit($problem_id)
  {
    try {
      $problem = new Problem($problem_id);
      $language = fRequest::get('language', 'integer');
      if (!array_key_exists($language, $this->languages)) {
        throw new fValidationException('Invalid language.');
      }
      fSession::set('last_language', $language);
      $code = trim(fRequest::get('code', 'string'));
      if (strlen($code) == 0) {
        throw new fValidationException('Code cannot be empty.');
      }
      if ($problem->isSecretNow()) {
        if (!User::can('view-any-problem')) {
          throw new fValidationException('Problem is secret now. You are not allowed to submit this problem.');
        }
      }
      
      $record = new Record();
      $record->setOwner(Authorization::getUserToken());
      $record->setProblemId($problem->getId());
      $record->setSubmitCode($code);
      $record->setCodeLanguage($language);
      $record->setSubmitDatetime(Util::currentTime());
      $record->setJudgeStatus(JudgeStatus::PENDING);
      $record->setJudgeMessage('Judging... PROB=' . $problem->getId() . ' LANG=' . $this->languages[$language]);
      $record->setVerdict(Verdict::UNKNOWN);
      $record->store();
      
      Util::redirect('/status');
    } catch (fExpectedException $e) {
      fMessaging::create('error', $e->getMessage());
      fMessaging::create('code', '/submit', fRequest::get('code', 'string'));
      Util::redirect("/submit?problem={$problem_id}");
    }
  }
}
