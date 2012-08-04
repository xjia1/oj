<?php
class ReportGenerator
{
  private static function totalTime($r, $st)
  {
    return ($r->getSubmitDatetime()->format('U') - $st->format('U')) / 60;
  }
  
  private static function penaltyCount($r)
  {
    return in_array($r->getVerdict(), array(Verdict::CE, Verdict::SE, Verdict::VE)) ? 0 : 1;
  }
  
  private static function accepted($r)
  {
    return $r->getResult() == Verdict::$NAMES[Verdict::AC];
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
  
  private static function prepareReport($r, $p, $u, &$fac, &$ts, &$score)
  {
    $r_size = count($r);
    $u_size = count($u);
    $p_size = count($p);
    
    for ($i = 0; $i < $u_size; $i++) {
      for ($j = 0; $j < $p_size; $j++) {
        $fac[$i][$j] = -1;
      }
    }
    
    for ($i = $r_size - 1; $i >= 0; $i--) {
      $user_i = array_search($r[$i]->getOwner(), $u);
      $prob_i = array_search($r[$i]->getProblemId(), $p);
      
      $score[$u_size - 1][$prob_i] = $score[$u_size - 1][$prob_i]
                                   - $score[$user_i][$prob_i]
                                   + max($score[$user_i][$prob_i], $r[$i]->getScore());
      $score[$u_size - 1][$p_size + 2] = $score[$u_size - 1][$p_size + 2]
                                       - $score[$user_i][$prob_i]
                                       + max($score[$user_i][$prob_i], $r[$i]->getScore());
      $score[$user_i][$prob_i] = max($score[$user_i][$prob_i], $r[$i]->getScore());
      
      if ($fac[$user_i][$prob_i] == -1) {
        $ts[$user_i][$prob_i] += static::penaltyCount($r[$i]);
        if (static::accepted($r[$i])) {
          $fac[$user_i][$prob_i] = $i;
        }
      }
    }
    
    if ($u_size == 1) {
      for ($i = 0; $i < $p_size; $i++) {
        $score[$u_size - 1][$i] = 0;
      }
      $score[$u_size - 1][$p_size + 2] = 0;
    } else {
      for ($i = 0; $i < $p_size; $i++) {
        $score[$u_size - 1][$i] = round($score[$u_size - 1][$i] / ($u_size - 1));
      }
      $score[$u_size - 1][$p_size + 2] = round($score[$u_size - 1][$p_size + 2] / ($u_size - 1));
    }
  }
  
  private static function genTotalScore($r, $p, $u, $st, $score, $fac, $ts, &$cell)
  {
    $u_size = count($u);
    $p_size = count($p);
    
    for ($i = 0; $i < $u_size - 1; $i++) {
      $tac = $tpe = $tsc = 0;
      for ($j = 0; $j < $p_size; $j++) {
        $tsc += $score[$i][$j];
        if ($fac[$i][$j] != -1) {
          $pe = ($ts[$i][$j] - 1) * 20 + static::totalTime($r[$fac[$i][$j]], $st);
          $tac += 1;
          $tpe += $pe;
          $cell[$i][$j] = '<font color="green">' . $score[$i][$j] . '<br>' . round($pe) . ' / +' . $ts[$i][$j] . '</font>';
        } else if ($score[$i][$j] > 0) {
          $cell[$i][$j] = '<font color="red">' . $score[$i][$j] . '</font>';
        } else {
          $cell[$i][$j] = '-';
        }
      }
      $cell[$i][$p_size] = $tac;
      $cell[$i][$p_size + 1] = round($tpe);
      $cell[$i][$p_size + 2] = $tsc;
    }
    
    for ($i = 0; $i < $p_size; $i++) {
      $cell[$u_size - 1][$i] = $score[$u_size - 1][$i];
    }
    $cell[$u_size - 1][$p_size] = '-';
    $cell[$u_size - 1][$p_size + 1] = '-';
    $cell[$u_size - 1][$p_size + 2] = $score[$u_size - 1][$p_size + 2];
  }
  
  private static function queryRecords($u, $p, $st, $et)
  {
    return fRecordSet::build('Record', array(
      'submit_datetime>=' => $st,
      'submit_datetime<=' => $et,
      'owner=' => $u,
      'problem_id=' => $p
    ), array(
      'submit_datetime' => 'desc'
    ))->getRecords();
  }
  
  public static function scores($p, $u, $st, $et)
  {
    $u_size = count($u);
    $p_size = count($p);
    
    $r = static::queryRecords($u, $p, $st, $et);
    $fac = Util::allocateArray($u_size, $p_size, 0);
    $ts = Util::allocateArray($u_size, $p_size, 0);
    $score = Util::allocateArray($u_size, $p_size + 3, 0);
    static::prepareReport($r, $p, $u, $fac, $ts, $score);
    
    $cell = Util::allocateArray($u_size, $p_size + 3, '');
    static::genTotalScore($r, $p, $u, $st, $score, $fac, $ts, $cell);
    return $cell;
  }
}
