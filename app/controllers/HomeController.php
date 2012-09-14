<?php
class HomeController extends ApplicationController
{
  public function index()
  {
    $this->cache_control('private', 300);
    $this->nav_class = 'home';
    $this->page_title = 'Home';
    $this->page_content = Variable::getString('home-markdown');
    $this->render('home/index');
  }
  
  public function showProblemSets()
  {
    $this->cache_control('private', 300);
    $this->nav_class = 'sets';
    $this->page_title = 'Problem Sets';
    $this->page_content = Variable::getString('problem-sets');
    $this->render('home/show_page');
  }
  
  public function showPage($name)
  {
    $this->cache_control('private', 300);
    $this->page_title = Variable::getString("page-title-{$name}");
    $this->page_content = Variable::getString("page-content-{$name}");
    $this->render('home/show_page');
  }
}
