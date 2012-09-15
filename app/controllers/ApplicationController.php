<?php
class ApplicationController
{
  private static function to_gmt($now = NULL)
  {
    return gmdate('D, d M Y H:i:s', ($now === NULL) ? time() : $now);
  }
  
  protected function cache_control($type, $seconds)
  {
    header_remove('Pragma');
    header("Cache-Control: {$type}, max-age={$seconds}");
    header('Expires: ' . static::to_gmt(time() + $seconds) . ' GMT');
  }
  
  protected function render($name)
  {
    // before output page
    // if the visitor is anonymous
    // do NOT send Set-Cookie to enable caching of Varnish
    if (!fAuthorization::checkLoggedIn()) {
      header_remove('Set-Cookie');
    }
    // then output page
    include(__DIR__ . '/../views/' . $name . '.php');
  }
  
  protected function ajaxReturn($ary)
  {
    echo json_encode($ary);
  }
}
