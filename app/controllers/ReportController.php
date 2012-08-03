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
    //
  }
}
