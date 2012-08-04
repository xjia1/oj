<?php
class DashboardController extends ApplicationController
{
  public function index()
  {
    if (!User::can('manage-site')) {
      fMessaging::create('error', 'You are not allowed to view the dashboard.');
      fURL::redirect(Util::getReferer());
    }
    
    if (User::can('view-any-report') or User::can('remove-report')) {
      $this->reports = fRecordSet::build('report', array(), array('id' => 'desc'));
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
      if (!User::can('rejudge-record')) {
        throw new fAuthorizationException('You are not allowed to rejudge records.');
      }
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
      if (!User::can('rejudge-record')) {
        throw new fAuthorizationException('You are not allowed to rejudge records.');
      }
      if ($score < 0) {
        throw new fValidationException('Score cannot be negative.');
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
    try {
      $report = new Report();
      $report->setVisible(fRequest::get('visible', 'integer'));
      $report->setTitle(fRequest::get('title', 'string'));
      $report->setProblemList(fRequest::get('problem_list', 'string'));
      $report->setUserList(fRequest::get('user_list', 'string'));
      $report->setStartDatetime(fRequest::get('start_datetime', 'timestamp'));
      $report->setEndDatetime(fRequest::get('end_datetime', 'timestamp'));
      $report->store();
      fMessaging::create('success', 'Report created successfully.');
    } catch (fException $e) {
      fMessaging::create('error', $e->getMessage());
    }
    fURL::redirect(Util::getReferer());
  }
  
  public function manageReport($id, $action)
  {
    try {
      $report = new Report($id);
      if ($action == 'Show') {
        if (User::can('view-any-report')) {
          $report->setVisible(1);
          $report->store();
          fMessaging::create('success', "Report {$id} showed successfully.");
        } else {
          throw new fAuthorization('You are not allowed to show this report.');
        }
      } else if ($action == 'Hide') {
        if (User::can('view-any-report')) {
          $report->setVisible(0);
          $report->store();
          fMessaging::create('success', "Report {$id} hidden successfully.");
        } else {
          throw new fAuthorization('You are not allowed to hide this report.');
        }
      } else if ($action == 'Remove') {
        if (User::can('remove-report')) {
          $report->delete();
          fMessaging::create('success', "Report {$id} removed successfully.");
        } else {
          throw new fAuthorization('You are not allowed to remove this report.');
        }
      }
    } catch (fException $e) {
      fMessaging::create('error', $e->getMessage());
    }
    fURL::redirect(Util::getReferer());
  }
  
  public function managePermissions($action)
  {
    try {
      $user_name = fRequest::get('user_name');
      $permission_name = fRequest::get('permission_name');
      if ($action == 'Add') {
        $permission = new Permission();
        $permission->setUserName($user_name);
        $permission->setPermissionName($permission_name);
        $permission->store();
        fMessaging::create('success', 'Permission added successfully.');
      } else if ($action == 'Remove') {
        $permission = new Permission(array('user_name' => $user_name, 'permission_name' => $permission_name));
        $permission->delete();
        fMessaging::create('success', 'Permission removed successfully.');
      }
    } catch (fException $e) {
      fMessaging::create('error', $e->getMessage());
    }
    fURL::redirect(Util::getReferer());
  }
  
  public function setVariable()
  {
    try {
      if (fRequest::get('remove', 'boolean')) {
        $variable = new Variable(fRequest::get('name'));
        $variable->delete();
        fMessaging::create('success', 'Variable removed successfully.');
      } else {
        try {
          $variable = new Variable(fRequest::get('name'));
        } catch (fNotFoundException $e) {
          $variable = new Variable();
          $variable->setName(fRequest::get('name'));
        }
        $variable->setValue(fRequest::get('value'));
        $variable->store();
        fMessaging::create('success', 'Variable set successfully.');
      }
    } catch (fException $e) {
      fMessaging::create('error', $e->getMessage());
    }
    fURL::redirect(Util::getReferer());
  }
}
