<?php
class DashboardController extends ApplicationController
{
  public function index()
  {
    if (!User::can('manage-site')) {
      fMessaging::create('error', T('You are not allowed to view the dashboard.'));
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
  
  private function gitPullOriginMaster()
  { 
    $data_base_dir = Variable::getString('data-base-path');
    if (!is_dir($data_base_dir)) {
      throw new fValidationException(T('Data base directory %s does not exist.'),$data_base_dir);
    }
    $pwd = getcwd();
    chdir($data_base_dir);
    $output = array();
    exec('git pull origin master 2>&1', $output, $retval);
    chdir($pwd);
    if ($retval != 0) {
      throw new fValidationException("<pre>{$data_base_dir}$ git pull origin master\n" . implode("\n", $output) . '</pre>');
    }
  }
  
  private function showProblem($id, $ignore_git=FALSE)
  {
    try {
      $problem = new Problem($id);
      if ($problem->exists()) {
        throw new fValidationException(T('Problem %s already exists.'),$id);
      }
    } catch (fNotFoundException $e) {
      // fall through
    }
    
    $data_base_dir = Variable::getString('data-base-path');
    if (!is_dir($data_base_dir)) {
      throw new fValidationException(T('Data base directory %s does not exist.'),$data_base_dir);
    }
    
    if (!$ignore_git) {
      $this->gitPullOriginMaster();
    }
    
    $problem_dir = "{$data_base_dir}/problems/{$id}";
    if (!is_dir($problem_dir)) {
      throw new fValidationException(T('Problem directory %s does not exist.'),$problem_dir);
    }
    
    $problem_conf = "{$problem_dir}/problem.conf";
    if (!is_file($problem_conf)) {
      throw new fValidationException(T('Problem configuration file %s does not exist.'),$problem_conf);
    }
    
    $problem_text = "{$problem_dir}/problem.text";
    if (!is_file($problem_text)) {
      throw new fValidationException(T('Problem description file %s does not exist.'),$problem_text);
    }
    
    $data_dir = "{$problem_dir}/data";
    if (!is_dir($data_dir)) {
      throw new fValidationException(T('Problem %s does not have a data directory at %s.'),$id,$data_dir);
    }
    
    $properties_content = file_get_contents($problem_conf);
    $ini_content = str_replace(': ', ' = ', $properties_content);
    $ini = parse_ini_string($ini_content);
    if (!array_key_exists('title', $ini) or empty($ini['title'])) {
      throw new fValidationException(T('Problem title is not specified in problem.conf'));
    }
    if (!array_key_exists('author', $ini)) {
      throw new fValidationException(T('Problem author is not specified in problem.conf'));
    }
    if (!array_key_exists('case_count', $ini) or empty($ini['case_count'])) {
      throw new fValidationException(T('Problem case count is not specified in problem.conf'));
    }
    if (!array_key_exists('case_score', $ini) or empty($ini['case_score'])) {
      throw new fValidationException(T('Problem case score is not specified in problem.conf'));
    }
    if (!array_key_exists('time_limit', $ini) or empty($ini['time_limit'])) {
      throw new fValidationException(T('Problem time limit is not specified in problem.conf'));
    }
    if (!array_key_exists('memory_limit', $ini) or empty($ini['memory_limit'])) {
      throw new fValidationException(T('Problem memory limit is not specified in problem.conf'));
    }
    if (!array_key_exists('secret_before', $ini) or empty($ini['secret_before'])) {
      throw new fValidationException(T('Problem secret-before time is not specified in problem.conf'));
    }
    
    if (empty($ini['author'])) {
      $ini['author'] = '-';
    }
    
    $problem = new Problem();
    $problem->setId($id);
    $problem->setTitle($ini['title']);
    $problem->setDescription(file_get_contents($problem_text));
    $problem->setAuthor($ini['author']);
    $problem->setCaseCount($ini['case_count']);
    $problem->setCaseScore($ini['case_score']);
    $problem->setTimeLimit($ini['time_limit']);
    $problem->setMemoryLimit($ini['memory_limit']);
    $problem->setSecretBefore($ini['secret_before']);
    $problem->validate();
    
    for ($t = 1; $t <= $problem->getCaseCount(); $t++) {
      $input = "{$data_dir}/$t.in";
      if (!is_file($input)) {
        throw new fValidationException(T('Case input file %s is not found in %s.'),$input,$data_dir);
      }
      $output = "{$data_dir}/$t.out";
      if (!is_file($output)) {
        throw new fValidationException(T('Case out file %s is not found in %s.'),$output,$data_dir);
      }
    }
    
    $problem->store();
  }
  
  private function hideProblem($id)
  {
    $problem = new Problem($id);
    $problem->delete();
  }
  
  private function refreshProblem($id)
  {
    $db = fORMDatabase::retrieve();
    try {
      $db->query('BEGIN');
      $this->hideProblem($id);
      $this->showProblem($id);
      $db->query('COMMIT');
    } catch (fException $e) {
      $db->query('ROLLBACK');
      throw $e;
    }
  }
  
  private function refreshAllProblems()
  {
    set_time_limit(0);
    $db = fORMDatabase::retrieve();
    try {
      $db->query('BEGIN');
      $this->gitPullOriginMaster();
      $problems = fRecordSet::build('Problem');
      foreach ($problems as $problem) {
        $id = $problem->getId();
        $this->hideProblem($id);
        $this->showProblem($id, TRUE);  // ignore git
      }
      $db->query('COMMIT');
    } catch (fExpectedException $e) {
      $db->query('ROLLBACK');
      throw new fExpectedException($id . ': ' . $e->getMessage());
    } catch (fUnexpectedException $e) {
      $db->query('ROLLBACK');
      throw new fUnexpectedException($id . ': ' . $e->getMessage());
    }
  }
  
  public function manageProblem($id, $action)
  {
    try {
      if (!User::can('manage-site')) {
        throw new fAuthorizationException(T('You are not allowed to manage problems.'));
      }
      if ($action == 'Show') {
        $this->showProblem($id);
        fMessaging::create('success', T('Problem %s showed successfully.',$id));
      } else if ($action == 'Hide') {
        $this->hideProblem($id);
        fMessaging::create('success', T('Problem %s hidden successfully.',$id));
      } else if ($action == 'Refresh') {
        $this->refreshProblem($id);
        fMessaging::create('success', T('Problem %s refreshed successfully.',$id));
      } else if ($action == 'Refresh All' and User::can('refresh-all')) {
        $this->refreshAllProblems();
        fMessaging::create('success', T('All problems refreshed successfully.'));
      }
    } catch (fException $e) {
      fMessaging::create('error', T($e->getMessage()));
    }
    fURL::redirect(Util::getReferer());
  }
  
  public function rejudge($id)
  {
    try {
      if (!User::can('rejudge-record')) {
        throw new fAuthorizationException(T('You are not allowed to rejudge records.'));
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
      fMessaging::create('success', T('Record %s rejudged.',$id));
    } catch (fException $e) {
      fMessaging::create('error', T($e->getMessage()));
    }
    fURL::redirect(Util::getReferer());
  }
  
  public function manjudge($id, $score)
  {
    try {
      if (!User::can('rejudge-record')) {
        throw new fAuthorizationException(T('You are not allowed to rejudge records.'));
      }
      if ($score < 0) {
        throw new fValidationException(T('Score cannot be negative.'));
      }
      $record = new Record($id);
      $record->manjudge($score);
      $record->store();
      fMessaging::create('success', T('Record %s manually judged.',$id));
    } catch (fException $e) {
      fMessaging::create('error', T($e->getMessage()));
    }
    fURL::redirect(Util::getReferer());
  }
  
  public function createReport()
  {
    try {
      if (!User::can('create-report')) {
        throw new fAuthorizationException(T('You are not allowed to create reports.'));
      }
      $report = new Report();
      $report->setVisible(fRequest::get('visible', 'integer'));
      $report->setTitle(fRequest::get('title', 'string'));
      $report->setProblemList(fRequest::get('problem_list', 'string'));
      $report->setUserList(fRequest::get('user_list', 'string'));
      $report->setStartDatetime(fRequest::get('start_time', 'timestamp'));
      $report->setEndDatetime(fRequest::get('end_time', 'timestamp'));
      $report->store();
      fMessaging::create('success', T('Report created successfully.'));
    } catch (fException $e) {
      fMessaging::create('error', T($e->getMessage()));
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
          fMessaging::create('success', T('Report %s showed successfully.',$id));
        } else {
          throw new fAuthorizationException(T('You are not allowed to show this report.'));
        }
      } else if ($action == 'Hide') {
        if (User::can('view-any-report')) {
          $report->setVisible(0);
          $report->store();
          fMessaging::create('success', T('Report %s hidden successfully.',$id));
        } else {
          throw new fAuthorizationException(T('You are not allowed to hide this report.'));
        }
      } else if ($action == 'Remove') {
        if (User::can('remove-report')) {
          $report->delete();
          fMessaging::create('success', T('Report %s removed successfully.',$id));
        } else {
          throw new fAuthorizationException(T('You are not allowed to remove this report.'));
        }
      }
    } catch (fException $e) {
      fMessaging::create('error', T($e->getMessage()));
    }
    fURL::redirect(Util::getReferer());
  }
  
  public function managePermission($action)
  {
    try {
      $user_name = fRequest::get('user_name');
      $permission_name = fRequest::get('permission_name');
      if ($action == 'Add') {
        if (User::can('add-permission')) {
          $permission = new Permission();
          $permission->setUserName($user_name);
          $permission->setPermissionName($permission_name);
          $permission->store();
          fMessaging::create('success', T('Permission added successfully.'));
        } else {
          throw new fAuthorizationException(T('You are not allowed to add permissions.'));
        }
      } else if ($action == 'Remove') {
        if (User::can('remove-permission')) {
          $permission = new Permission(array('user_name' => $user_name, 'permission_name' => $permission_name));
          $permission->delete();
          fMessaging::create('success', T('Permission removed successfully.'));
        } else {
          throw new fAuthorizationException(T('You are not allowed to remove permissions.'));
        }
      }
    } catch (fException $e) {
      fMessaging::create('error', T($e->getMessage()));
    }
    fURL::redirect(Util::getReferer());
  }
  
  public function setVariable()
  {
    try {
      if (fRequest::get('remove', 'boolean')) {
        $variable = new Variable(fRequest::get('name'));
        $variable->delete();
        fMessaging::create('success', T('Variable removed successfully.'));
      } else {
        try {
          $variable = new Variable(fRequest::get('name'));
        } catch (fNotFoundException $e) {
          $variable = new Variable();
          $variable->setName(fRequest::get('name'));
        }
        $variable->setValue(fRequest::get('value'));
        $variable->store();
        fMessaging::create('success', T('Variable set successfully.'));
      }
    } catch (fException $e) {
      fMessaging::create('error', T($e->getMessage()));
    }
    fURL::redirect(Util::getReferer());
  }
}
