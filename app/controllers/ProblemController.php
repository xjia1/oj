<?php
class ProblemController extends ApplicationController
{
  public function index()
  {
    if ($pid = fRequest::get('id', 'integer')) {
      Util::redirect('/problem/' . $pid);
    }
    
    $view_any = User::can('view-any-problem');
    $this->page = fRequest::get('page', 'integer', 1);
    $this->title = trim(fRequest::get('title', 'string'));
    $this->author = trim(fRequest::get('author', 'string'));
    $this->problems = Problem::find($view_any, $this->page, $this->title, $this->author);
    $this->page_url = SITE_BASE . "/problems?title={$this->title}&author={$this->author}&page=";
    $this->nav_class = 'problems';
    $this->render('problem/index');
  }
  
  public function show($id)
  {
    try {
      $this->problem = new Problem($id);
      $view_any = User::can('view-any-problem');
      if (!$view_any and $this->problem->isSecretNow()) {
        throw new fValidationException('Problem is secret.');
      }
      $this->render('problem/show');
    } catch (fExpectedException $e) {
      fMessaging::create('warning', $e->getMessage());
      fURL::redirect(Util::getReferer());
    }
  }
}
