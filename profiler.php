<?php
// apd_set_pprof_trace();

$profiler_stats = array(
  'time_start'  => microtime(TRUE),
  'query_num'   => 0,
  'query_time'  => 0.0,
  'statements'  => array()
);

function profiler_log_sql($db, $statement, $query_time, $result)
{
  global $profiler_stats;
  $profiler_stats['query_num'] += 1;
  $profiler_stats['query_time'] += $query_time;
  $profiler_stats['statements'][] = $statement;
}

function profiler_dump()
{
  global $profiler_stats;
  $time_start = $profiler_stats['time_start'];
  $query_num  = $profiler_stats['query_num'];
  $query_time = round($profiler_stats['query_time'], 6);
  $time_cost  = round(microtime(TRUE) - $time_start, 6);
  echo "{$time_cost} sec - {$query_num} queries - {$query_time} sec";
  if (SQL_DEBUG) {
    echo "<pre>" . implode("\n", $profiler_stats['statements']) . "</pre>";
  }
}
