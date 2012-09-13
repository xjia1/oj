<?php
class BoardCacheInvalidator
{
  public static function invalidate($username, $problem_id, $submit_time)
  {
    global $cache;
    
    $affected_reports = fRecordSet::build('Report', array(
      'problem_list~' => $problem_id,
      'user_list~' => $username,
      'start_datetime<=' => $submit_time,
      'end_datetime>=' => $submit_time
    ));
    
    foreach ($affected_reports as $report) {
      $cache->delete($report->getBoardCacheKey());
    }
  }
  
  public static function invalidateByReport($report) {
    global $cache;
    $cache->delete($report->getBoardCacheKey());
  }
}
