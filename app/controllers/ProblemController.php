<?php
class ProblemController extends ApplicationController
{
  public function index()
  {
    $this->cache_control('private', 5);
    
    if ($pid = fRequest::get('id', 'integer')) {
      Util::redirect('/problem/' . $pid);
    }
    
    $view_any = User::can('view-any-problem');
    $this->page = fRequest::get('page', 'integer', 1);
    $this->title = trim(fRequest::get('title', 'string'));
    $this->author = trim(fRequest::get('author', 'string'));
    $this->problems = Problem::find($view_any, $this->page, $this->title, $this->author);
    
    $this->page_url = SITE_BASE . '/problems?';
    if (!empty($this->title)) {
      $this->page_url .= 'title=' . fHTML::encode($this->title) . '&';
    }
    if (!empty($this->author)) {
      $this->page_url .= 'author='. fHTML::encode($this->author) . '&';
    }
    $this->page_url .= 'page=';
    
    $this->page_records = $this->problems;
    $this->nav_class = 'problems';
    $this->render('problem/index');
  }
  
  public function show($id)
  {
    if (fAuthorization::checkLoggedIn()) {
      $this->cache_control('private', 30);
    } else {
      $this->cache_control('private', 60);
    }
    
    try {
      $this->problem = new Problem($id);
      if ($this->problem->isSecretNow()) {
        if (!User::can('view-any-problem')) {
          throw new fAuthorizationException('题目现在是隐藏状态.');
        }
      }
      $this->nav_class = 'problems';
      $this->render('problem/show');
    } catch (fExpectedException $e) {
      fMessaging::create('warning', $e->getMessage());
      fURL::redirect(Util::getReferer());
    } catch (fUnexpectedException $e) {
      fMessaging::create('error', $e->getMessage());
      fURL::redirect(Util::getReferer());
    }
  }
}
