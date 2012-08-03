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
    exit();
  }
}
