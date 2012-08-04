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
    try {
      $this->record = new Record($id);
      if (!$this->record->isReadable()) {
        throw new fValidationException('You are not allowed to read this record.');
      }
      $this->nav_class = 'status';
      $this->render('record/show');
    } catch (fExpectedException $e) {
      fMessaging::create('warning', $e->getMessage());
      fURL::redirect(Util::getReferer());
    }
  }
}
