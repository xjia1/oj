<?php
function translate($string)
{
  static $translations = array(
    'right now' => '刚刚',
    'at the same time' => '同时',
    'second' => '秒',
    'seconds' => '秒',
    'minute' => '分钟',
    'minutes' => '分钟',
    'hour' => '小时',
    'hours' => '小时',
    'day' => '天',
    'days' => '天',
    'week' => '周',
    'weeks' => '周',
    'month' => '月',
    'months' => '月',
    'year' => '年',
    'years' => '年',
    '%1$s %2$s' => '%1$s %2$s',
    '%1$s %2$s from now' => '%1$s %2$s from now',
    '%1$s %2$s ago' => '%1$s%2$s前',
    '%1$s %2$s after' => '%1$s %2$s after',
    '%1$s %2$s before' => '%1$s %2$s before'
  );
  
  if (isset($translations[$string])) {
    return $translations[$string];
  }
  return $string;
}
