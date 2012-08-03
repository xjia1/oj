<?php
class HomeController extends ApplicationController
{
  public function index()
  {
    $this->nav_class = 'home';
    $this->page_title = 'Home';
    $this->page_content = Variable::getString('home-markdown');
    $this->render('home/show_page');
  }
  
  public function showProblemSets()
  {
    $this->nav_class = 'sets';
    $this->page_title = 'Problem Sets';
    $this->page_content = Variable::getString('problem-sets');
    $this->render('home/show_page');
  }
}
