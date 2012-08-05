<?php
class Util
{
  public static function currentTime()
  {
    return date('Y-m-d H:i:s');
  }
  
  public static function currentDate()
  {
    return date('Y-m-d');
  }
  
  public static function currentYear()
  {
    return date('Y');
  }
  
  public static function startsWith($haystack, $needle)
  {
    $length = strlen($needle);
    return substr($haystack, 0, $length) === $needle;
  }

  public static function endsWith($haystack, $needle)
  {
    $length = strlen($needle);
    $start  = $length * -1; // negative
    return substr($haystack, $start) === $needle;
  }
  
  public static function ensurePrefix($prefix, $str)
  {
    if (self::startsWith($str, $prefix)) return $str;
    return $prefix . $str;
  }
  
  public static function redirect($path)
  {
    fURL::redirect(SITE_BASE . $path);
  }
  
  public static function getReferer()
  {
    if (empty($_SERVER['HTTP_REFERER'])) {
      return SITE_BASE;
    }
    return $_SERVER['HTTP_REFERER'];
  }
  
  public static function allocateArray($n, $m, $v)
  {
    $a = array();
    for ($i = 0; $i < $n; $i++) {
      $a[$i] = array();
      for ($j = 0; $j < $m; $j++) {
        $a[$i][$j] = $v;
      }
    }
    return $a;
  }
  
  public static function restrictIp($addresses)
  {
    $remote = $_SERVER['REMOTE_ADDR'];
    if (empty($remote) or strstr($addresses, "|{$remote}|") === FALSE) {
      echo $remote;
      exit();
    }
  }
}
