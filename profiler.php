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

function profiler_render_begin()
{
  global $profiler_stats;
  $profiler_stats['render_time'] = microtime(TRUE);
}

function profiler_instrument_begin($inst_id)
{
  global $profiler_stats;
  if (array_key_exists($inst_id, $profiler_stats)) {
    $profiler_stats[$inst_id]['last'] = microtime(TRUE);
  } else {
    $profiler_stats[$inst_id] = array(
      'time' => 0.0,
      'last' => microtime(TRUE)
    );
  }
}

function profiler_instrument_end($inst_id)
{
  global $profiler_stats;
  if (array_key_exists($inst_id, $profiler_stats)) {
    $last = $profiler_stats[$inst_id]['last'];
    $duration = microtime(TRUE) - $last;
    $profiler_stats[$inst_id]['time'] += $duration;
  }
}

function profiler_instrument_read($inst_id)
{
  global $profiler_stats;
  if (array_key_exists($inst_id, $profiler_stats))
    return round($profiler_stats[$inst_id]['time'], 4);
  return '';
}

function profiler_dump()
{
  global $profiler_stats;
  $time_start = $profiler_stats['time_start'];
  $query_num  = $profiler_stats['query_num'];
  $query_time = round($profiler_stats['query_time'], 4);
  $time_cost  = round(microtime(TRUE) - $time_start, 4);
  $render_time = round(microtime(TRUE) - $profiler_stats['render_time'], 4);
  $instruments = profile_instrument_read('fetchRealName').' '.profiler_instrument_read('Record::getTimeCost').' '.profiler_instrument_read('Record::getMemoryCost').' '.profiler_instrument_read('Record::getScore');
  echo "{$time_cost} - render ${render_time} - instru ${instruments} - {$query_num} queries - {$query_time} @ " . INSTANCE_NAME;
  if (SQL_DEBUG) {
    echo "<pre>" . implode("\n", $profiler_stats['statements']) . "</pre>";
  }
}
