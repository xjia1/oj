<?php
class ReportController extends ApplicationController
{
  public function index()
  {
    if (fAuthorization::checkLoggedIn()) {
      $this->cache_control('private', 5);
    } else {
      $this->cache_control('private', 10);
    }
    
    $conditions = array();
    if (!User::can('view-any-report')) {
      $conditions['visible='] = TRUE;
    }
    $this->reports = fRecordSet::build('Report', $conditions, array('id' => 'desc'));
    $this->nav_class = 'reports';
    $this->render('report/index');
  }
  
  public function show($id)
  {
    $this->cache_control('private', Variable::getInteger('status-refresh', 30));
    
    try {
      $this->report = new Report($id);
      if (!$this->report->isReadable()) {
        throw new fAuthorizationException('你没有查看此比赛的权限.');
      }
      
      global $cache;
      
      $this->board = $cache->get($this->report->getBoardCacheKey());
      
      if ($this->board === NULL) {
        $p  = $this->report->getProblems();
        $un = $this->report->getUsernames();
        $up = $this->report->getUserPairs();
        
        $un[] = '';
        $up[] = array('id' => '', 'name' => '平均');
        
        $st = $this->report->getStartDatetime();
        $et = $this->report->getEndDatetime();
        
        $this->board = new BoardTable(ReportGenerator::headers($p), $up, ReportGenerator::scores($p, $un, $st, $et));
       
        $cache->set($this->report->getBoardCacheKey(), $this->board, 10);
      }
      
      $this->nav_class = 'reports';
      $this->render('report/show');
    } catch (fExpectedException $e) {
      fMessaging::create('warning', $e->getMessage());
      fURL::redirect(Util::getReferer());
    } catch (fUnexpectedException $e) {
      fMessaging::create('error', $e->getMessage());
      fURL::redirect(Util::getReferer());
    }
  }
  
  public function newRegistration($id)
  {
    try {
      $report = new Report($id);
      if (!$report->isRegistrable()) {
        throw new fValidationException('不可注册.');
      }
      $registration = new Registration();
      $registration->setUsername(fAuthorization::getUserToken());
      $registration->setReportId($report->getId());
      $registration->store();
      BoardCacheInvalidator::invalidateByReport($report);
      fMessaging::create('success', '注册成功.');
    } catch (fExpectedException $e) {
      fMessaging::create('warning', $e->getMessage());
    } catch (fUnexpectedException $e) {
      fMessaging::create('error', $e->getMessage());
    }
    fURL::redirect(Util::getReferer());
  }
  
  public function newQuestion($id)
  {
    try {
      $report = new Report($id);
      if (!$report->allowQuestion()) {
        throw new fValidationException('不允许提问.');
      }
      $category = fRequest::get('category', 'integer');
      if ($category == 0) {
        throw new fValidationException('请选择一个分类.');
      }
      $question = new Question();
      $question->setUsername(fAuthorization::getUserToken());
      $question->setReportId($report->getId());
      if ($category < 0) {
        $question->setCategory($category);
      } else {
        $question->setCategory(Question::ABOUT_PROBLEM_HIDDEN);
        $problem = new Problem($category);
        $question->setProblemId($problem->getId());
      }
      $question->setAskTime(new fTimestamp());
      $question->setQuestion(trim(fRequest::get('question')));
      if (strlen($question->getQuestion()) < 10) {
        throw new fValidationException('问题过短 (最少 10 字节).');
      }
      if (strlen($question->getQuestion()) > 500) {
        throw new fValidationException('问题过长 (最大 500 字节).');
      }
      $question->store();
      fMessaging::create('success', 'Question saved.');
    } catch (fExpectedException $e) {
      fMessaging::create('warning', $e->getMessage());
    } catch (fUnexpectedException $e) {
      fMessaging::create('error', $e->getMessage());
    }
    Util::redirect("/contest/{$id}");
  }
  
  public function replyQuestion($id)
  {
    try {
      $question = new Question($id);
      $report = new Report($report_id = $question->getReportId());
      if (!$report->allowAnswer()) {
        throw new fValidationException('没有权限回答问题的权限.');
      }
      $question->setAnswerTime(new fTimestamp());
      $question->setAnswer(trim(fRequest::get('reply')));
      $question->store();
      fMessaging::create('success', '问题已回答.');
    } catch (fExpectedException $e) {
      fMessaging::create('warning', $e->getMessage());
    } catch (fUnexpectedException $e) {
      fMessaging::create('error', $e->getMessage());
    }
    Util::redirect("/contest/{$report_id}");
  }
  
  public function toggleQuestionVisibility($id)
  {
    try {
      $question = new Question($id);
      $report = new Report($report_id = $question->getReportId());
      if (!$report->allowAnswer()) {
        throw new fValidationException('没有权限更改此问题可见度.');
      }
      $question->setCategory(-$question->getCategory());
      $question->store();
      fMessaging::create('success', '可见度已更改.');
    } catch (fExpectedException $e) {
      fMessaging::create('warning', $e->getMessage());
    } catch (fUnexpectedException $e) {
      fMessaging::create('error', $e->getMessage());
    }
    Util::redirect("/contest/{$report_id}");
  }
}
