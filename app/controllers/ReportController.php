<?php
class ReportController extends ApplicationController
{
  public function index()
  {
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
    try {
      $this->report = new Report($id);
      if (!$this->report->isReadable()) {
        throw new fValidationException('You are not allowed to view this report.');
      }
      $this->nav_class = 'reports';
      $this->render('report/show');
    } catch (fExpectedException $e) {
      fMessaging::create('warning', $e->getMessage());
      fURL::redirect(Util::getReferer());
    }
  }
}
