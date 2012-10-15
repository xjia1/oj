<?php
class Lock
{
  public static function acquire($key, $timeout=5)
  {
    global $memcache;
    while (TRUE) {
      $lock = $memcache->add("lock:{$key}", 1, FALSE, $timeout);
      if ($lock) {
        // not locked yet
        return $key;
      } else {
        // currently locked
        // wait for 10 milliseconds
        usleep(10 * 1000);
      }
    }
  }
  
  public static function release($key)
  {
    global $memcache;
    return $memcache->delete("lock:{$key}");
  }
}
