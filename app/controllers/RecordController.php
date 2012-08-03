<?php
class RecordController extends ApplicationController
{
  public function index()
  {
    $top = fRequest::get('top', 'integer');
    $this->owner = trim(fRequest::get('owner'));
    $this->problem_id = trim(fRequest::get('problem'));
    $this->language = trim(fRequest::get('language'));
    $this->verdict = trim(fRequest::get('verdict'));
    $this->records = Record::find($top, $this->owner, $this->problem_id, $this->language, $this->verdict);
    $this->top_url = SITE_BASE .
      "/status?owner={$this->owner}&problem={$this->problem_id}&language={$this->language}&verdict={$this->verdict}&top=";
    $this->nav_class = 'status';
    $this->render('record/index');
  }
  
  public function show($id)
  {
    //
  }
}
