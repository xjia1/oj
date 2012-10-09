<?php
class ReportGenerator
{
  private static function totalTime($record, $start_time)
  {
    return ($record->getSubmitDatetime()->format('U') - $start_time->format('U')) / 60;
  }
  
  private static function penaltyCount($record)
  {
    return in_array($record->getVerdict(), array(Verdict::CE, Verdict::SE, Verdict::VE)) ? 0 : 1;
  }
  
  private static function accepted($record)
  {
    return $record->getResult() == Verdict::$NAMES[Verdict::AC];
  }
   
  public static function headers($problem_ids)
  {
    $headers = array();
    $length = count($problem_ids) + 5;
    $headers[0] = '#';
    $headers[1] = 'Who';
    for ($i = 0; $i < count($problem_ids); $i++) {
      // start from $headers[0+2]
      $headers[$i + 2] = $problem_ids[$i];
    }
    $headers[$length - 3] = 'Accepts';
    $headers[$length - 2] = 'Penalty';
    $headers[$length - 1] = 'Score';
    return $headers;
  }
  
  private static function prepareReport($records, $problem_ids, $usernames, &$first_accepted_index, &$num_trial, &$score)
  {
    $r_size = count($records);
    $u_size = count($usernames);
    $p_size = count($problem_ids);
    
    $row_average = $u_size - 1;

    $col_accepts = $p_size;
    $col_penalty = $col_accepts + 1;
    $col_score   = $col_penalty + 1;

    for ($i = 0; $i < $r_size; $i++) {
      $user_i = array_search($records[$i]->getOwner(),     $usernames);
      $prob_i = array_search($records[$i]->getProblemId(), $problem_ids);

      if ($score[$user_i][$prob_i] === NULL) {
        $score[$user_i][$prob_i] = $records[$i]->getScore();
      } else {
        $score[$user_i][$prob_i] =
          max($score[$user_i][$prob_i], $records[$i]->getScore());
      }
      
      if ($first_accepted_index[$user_i][$prob_i] === NULL) {
        // haven't accepted yet

        // count the penalty
        $num_trial[$user_i][$prob_i] += static::penaltyCount($records[$i]);

        // now if the current record is accepted
        if (static::accepted($records[$i])) {
          $first_accepted_index[$user_i][$prob_i] = $i;
        }
      }
    }
    
    if ($u_size === 1) {
      // only the "average" as a username
      for ($prob_i = 0; $prob_i < $p_size; $prob_i++) {
        $score[$row_average][$prob_i] = 0;
      }
      $score[$row_average][$col_score] = 0;
    }
    else {
      // this is the normal case, calculate average scores
      for ($prob_i = 0; $prob_i < $p_size; $prob_i++) {
        $sum = 0;
        for ($user_i = 0; $user_i < $row_average; $user_i++) {
          $sum += $score[$user_i][$prob_i];
        }
        $score[$row_average][$prob_i] = round($sum / ($u_size - 1));
      }
      // calculate average total score
      $sum = 0;
      for ($user_i = 0; $user_i < $row_average; $user_i++) {
        $sum += $score[$user_i][$col_score];
      }
      $score[$row_average][$col_score] = round($sum / ($u_size - 1));
    }
  }
  
  private static function genTotalScore($records, $problem_ids, $usernames, $start_time, $score, $first_accepted_index, $num_trial, &$cell)
  {
    $u_size = count($usernames);
    $p_size = count($problem_ids);

    $col_accepts = $p_size;
    $col_penalty = $col_accepts + 1;
    $col_score   = $col_penalty + 1;

    for ($user_i = 0; $user_i < $u_size - 1; $user_i++) {
      $tot_accepts = 0;
      $tot_penalty = 0;
      $tot_score   = 0;

      for ($prob_i = 0; $prob_i < $p_size; $prob_i++) {
        $tot_score += $score[$user_i][$prob_i];

        if ($first_accepted_index[$user_i][$prob_i] !== NULL) {
          // accepted
          $penalty =
            ($num_trial[$user_i][$prob_i] - 1) * 20
            + static::totalTime($records[$first_accepted_index[$user_i][$prob_i]], $start_time);
          
          $tot_accepts += 1;
          $tot_penalty += $penalty;

          $cell[$user_i][$prob_i] =
            '<font color="green">' .
            round($penalty) . '<br>' .
            '(-' . $num_trial[$user_i][$prob_i] . ')' .
            '</font>';
        }
        else if ($score[$user_i][$prob_i] === NULL) {
          // haven't tried yet
          $cell[$user_i][$prob_i] = '';
        }
        else {
          // tried but failed
          $cell[$user_i][$prob_i] =
            '<font color="red">' .
            '(-' . $num_trial[$user_i][$prob_i] . ')' .
            '</font>';
        }
      }

      $cell[$user_i][$col_accepts] = $tot_accepts;
      $cell[$user_i][$col_penalty] = round($tot_penalty);
      $cell[$user_i][$col_score]   = $tot_score;
    }

    $row_average = $u_size - 1;

    for ($prob_i = 0; $prob_i < $p_size; $prob_i++) {
      $cell[$row_average][$prob_i] = $score[$row_average][$prob_i];
    }
    $cell[$row_average][$col_accepts] = '-';
    $cell[$row_average][$col_penalty] = '-';
    $cell[$row_average][$col_score]   = $score[$row_average][$col_score];
  }
  
  private static function queryRecords($usernames, $problem_ids, $start_time, $end_time)
  {
    return fRecordSet::build('Record', array(
      'submit_datetime>=' => $start_time,
      'submit_datetime<=' => $end_time,
      'owner=' => $usernames,
      'problem_id=' => $problem_ids
    ), array(
      'submit_datetime' => 'asc'
    ))->getRecords();
  }
  
  public static function scores($problem_ids, $usernames, $start_time, $end_time)
  {
    $u_size = count($usernames);
    $p_size = count($problem_ids);
    
    $records = static::queryRecords($usernames, $problem_ids, $start_time, $end_time);

    function notDone($record) 
    {
      return $record->getJudgeStatus() != JudgeStatus::DONE;
    }

    $records = array_filter($records, "notDone");

    $first_accepted_index = Util::allocateArray($u_size, $p_size, NULL);
    $num_trial            = Util::allocateArray($u_size, $p_size, 0);
    $score                = Util::allocateArray($u_size, $p_size + 3, NULL);

    static::prepareReport($records, $problem_ids, $usernames, $first_accepted_index, $num_trial, $score);
    
    $cell = Util::allocateArray($u_size, $p_size + 3, '');
    static::genTotalScore($records, $problem_ids, $usernames, $start_time, $score, $first_accepted_index, $num_trial, $cell);
    return $cell;
  }
}
