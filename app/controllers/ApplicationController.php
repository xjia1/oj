<?php
class ApplicationController
{
  protected function cache_control($type, $time)
  {
    header("Cache-Control: {$type},max-age={$time}");
  }
  
  protected function render($name)
  {
    include(__DIR__ . '/../views/' . $name . '.php');
  }
  
  protected function ajaxReturn($ary)
  {
    echo json_encode($ary);
  }
}
