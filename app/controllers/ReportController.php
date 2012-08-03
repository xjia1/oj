<?php
class ReportController extends ApplicationController
{
  public function index()
  {
    $this->reports = fRecordSet::build('Report', array(), array('id' => 'desc'));
    $this->nav_class = 'reports';
    $this->render('report/index');
  }
  
  public function show($id)
  {
    //
  }
}
