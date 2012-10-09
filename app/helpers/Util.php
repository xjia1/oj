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
  
  public static function contains($any, $str)
  {
    for ($i = 0; $i < strlen($str); $i++) {
      for ($j = 0; $j < strlen($any); $j++) {
        if ($str[$i] == $any[$j]) {
          return true;
        }
      }
    }
    return false;
  }
  
  public static function sendTextMail($from_user, $from_email, $to, $subject, $message, $reply_user='', $reply_email='')
  {
    $from_user = "=?UTF-8?B?" . base64_encode($from_user) . "?=";
    $subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
    $headers   = array();
    $headers[] = "MIME-Version: 1.0";
    $headers[] = "Content-type: text/plain; charset=UTF-8";
    $headers[] = "From: {$from_user} <{$from_email}>";
    if (strlen($reply_user) and strlen($reply_email)) {
      $reply_user = "=?UTF-8?B?" . base64_encode($reply_user) . "?=";
      $headers[] = "Reply-To: {$reply_user} <{$reply_email}>";
    }
    $headers[] = "Subject: {$subject}";
    $headers[] = "X-Mailer: PHP/" . phpversion();
    return mail($to, $subject, $message, implode("\r\n", $headers), "-f {$from_user}");
  }
  
  public static function sendHtmlMail($from_user, $from_email, $to, $subject, $message, $reply_user='', $reply_email='')
  {
    $html = '<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>' . $subject . '</title>
</head>
<body>' . $message . '</body>
</html>';
    $from_user = "=?UTF-8?B?" . base64_encode($from_user) . "?=";
    $subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
    $headers   = array();
    $headers[] = "MIME-Version: 1.0";
    $headers[] = "Content-type: text/html; charset=utf-8";
    $headers[] = "From: {$from_user} <{$from_email}>";
    if (strlen($reply_user) and strlen($reply_email)) {
      $reply_user = "=?UTF-8?B?" . base64_encode($reply_user) . "?=";
      $headers[] = "Reply-To: {$reply_user} <{$reply_email}>";
    }
    $headers[] = "Subject: {$subject}";
    $headers[] = "X-Mailer: PHP/" . phpversion();
    return mail($to, $subject, $html, implode("\r\n", $headers), "-f {$from_user}");
  }
}
