<?php
class DashboardController extends ApplicationController
{
  public function index()
  {
    if (!User::can('manage-site')) {
      fMessaging::create('error', 'You are not allowed to view the dashboard.');
      fURL::redirect(Util::getReferer());
    }
    
    if (User::can('add-permission') and User::can('remove-permission')) {
      $this->permissions = fRecordSet::build('Permission');
    }
    
    if (User::can('list-variables')) {
      $this->variables = fRecordSet::build('Variable');
    }
    
    if (User::can('set-variable')) {
      if (strlen($edit = fRequest::get('edit'))) {
        $this->setvar_name = $edit;
        $this->setvar_value = Variable::getString($edit);
        $this->setvar_remove = FALSE;
      } else if (strlen($remove = fRequest::get('remove'))) {
        $this->setvar_name = $remove;
        $this->setvar_value = Variable::getString($remove);
        $this->setvar_remove = TRUE;
      } else {
        $this->setvar_name = '';
        $this->setvar_value = '';
        $this->setvar_remove = FALSE;
      }
    }
    
    $this->nav_class = 'dashboard';
    $this->render('dashboard/index');
  }
  
  public function manageProblem($id)
  {
    //
  }
  
  public function rejudge($id)
  {
    try {
      $old_record = new Record($id);
      $new_record = new Record();
      $new_record->setOwner($old_record->getOwner());
      $new_record->setProblemId($old_record->getProblemId());
      $new_record->setSubmitCode($old_record->getSubmitCode());
      $new_record->setCodeLanguage($old_record->getCodeLanguage());
      $new_record->setSubmitDatetime($old_record->getSubmitDatetime());
      $new_record->setJudgeStatus(JudgeStatus::PENDING);
      $new_record->setJudgeMessage('Rejudging... PROB=' . $old_record->getProblemId() . ' LANG=' . $old_record->getLanguageName());
      $new_record->setVerdict(Verdict::UNKNOWN);
      $new_record->store();
      fMessaging::create('success', "Record {$id} rejudged.");
    } catch (fException $e) {
      fMessaging::create('error', $e->getMessage());
    }
    fURL::redirect(Util::getReferer());
  }
  
  public function manjudge($id, $score)
  {
    try {
      if ($score < 0) {
        throw new fValidationException('Score can not be negative.');
      }
      $record = new Record($id);
      $record->manjudge($score);
      $record->store();
      fMessaging::create('success', "Record {$id} manually judged.");
    } catch (fException $e) {
      fMessaging::create('error', $e->getMessage());
    }
    fURL::redirect(Util::getReferer());
  }
  
  public function createReport()
  {
    //
  }
  
  public function manageReport($id)
  {
    //
  }
  
  public function managePermissions()
  {
    //
  }
  
  public function setVariable()
  {
    //
  }
}
