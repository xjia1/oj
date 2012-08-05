<?php
class PollingController extends ApplicationController
{
  private function getPendingRecord()
  {
    return fRecordSet::build('Record', array(
      'judge_status=' => JudgeStatus::PENDING
    ), array(
      'id' => 'asc'
    ), 1)->getRecord(0);
  }
  
  public function fetchRecord()
  {
    try {
      if (($r = $this->getPendingRecord()) == NULL) {
        throw new fExpectedException('No pending record.');
      }
		  $p = $r->getProblem();
		  $r->setJudgeStatus(JudgeStatus::WAITING);
		  $r->store();
		  echo fJSON::encode(array(
		    'id'            =>  $r->getId(),
		    'problem_id'    =>  $p->getId(),
		    'code_language' =>  $r->getLanguageName(),
		    'code'          =>  base64_encode($r->getSubmitCode()),
		    'memoryLimit'   =>  $p->getMemoryLimit(),
		    'timeLimit'     =>  $p->getTimeLimit(),
		    'caseScore'     =>  $p->getCaseScore(),
		    'caseCount'     =>  $p->getCaseCount(),
		    'Timestamp'     =>  $p->getLastModified()
		  ));
		} catch (fException $e) {
		  echo -1;
		}
  }
  
  public function fetchTimestamp()
  {
    try {
      $p = new Problem(fRequest::get('pid', 'integer'));
      echo $p->getLastModified();
    } catch (fException $e) {
      echo -1;
    }
  }
  
  public function updateJudgeStatus()
  {
    try {
      $op = strtolower(trim(fRequest::get('status', 'string')));
      $judge_message = base64_decode(fRequest::get('judgeMessage', 'string'));
      $verdict = fRequest::get('verdict', 'integer');
      $id = fRequest::get('id', 'integer');
      $r = new Record($id);
    
      if ($op == 'running') {
        $r->setJudgeStatus(JudgeStatus::RUNNING);
        $r->setJudgeMessage($r->getJudgeMessage() . "\n{$judge_message}");
        $r->store();
      } else if ($op == 'done') {
        $r->setJudgeStatus(JudgeStatus::DONE);
        if (!empty($judge_message)) {
          $r->setJudgeMessage($judge_message);
        }
        $r->setVerdict($verdict);
        $r->store();
      }
      
      echo "{$op}\n";
      echo "{$judge_message}\n";
      echo "{$verdict}\n";
      echo "{$id}\n";
    } catch (fException $e) {
      echo -1;
    }
  }
}
