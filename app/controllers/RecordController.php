<?php
class RecordController extends ApplicationController
{
  public function index()
  {
    if (fAuthorization::checkLoggedIn()) {
      $this->cache_control('private', 2);
    } else {
      $this->cache_control('public', 5);
    }
    $top = fRequest::get('top', 'integer');
    $this->owner = trim(fRequest::get('owner'));
    $this->problem_id = trim(fRequest::get('problem'));
    $this->language = trim(fRequest::get('language'));
    $this->verdict = trim(fRequest::get('verdict'));
    $this->page = fRequest::get('page', 'integer', 1);
    $this->records = Record::find($top, $this->owner, $this->problem_id, $this->language, $this->verdict, $this->page);
    $this->page_records = $this->records;
    $common_url = SITE_BASE .
      "/status?owner={$this->owner}&problem={$this->problem_id}&language={$this->language}&verdict={$this->verdict}";
    $this->top_url = "{$common_url}&top=";
    $this->page_url = "{$common_url}&page=";
    $this->nav_class = 'status';
    $this->render('record/index');
  }
  
  public function show($id)
  {
    try {
      $this->record = new Record($id);
      if (!$this->record->isReadable()) {
        throw new fAuthorizationException('You are not allowed to read this record.');
      }
      $this->nav_class = 'status';
      $this->render('record/show');
    } catch (fExpectedException $e) {
      fMessaging::create('warning', $e->getMessage());
      fURL::redirect(Util::getReferer());
    } catch (fUnexpectedException $e) {
      fMessaging::create('error', $e->getMessage());
      fURL::redirect(Util::getReferer());
    }
  }
}
