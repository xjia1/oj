<?php
class BoardTable
{
  private $headers = array();
  private $owners  = array();
  private $scores  = array();
  
  public function __construct($h, $u, $s)
  {
    $this->headers = $h;
    $this->owners  = $u;
    $this->scores  = $s;
  }
  
  public function getHeaders()
  {
    return $this->headers;
  }
  
  public function getRowCount()
  {
    return count($this->owners) - 1;
  }
  
  public function getRow($row)
  {
    $r = array();
    $r[0] = $row; // rank
    
    $id = $text = $this->owners[$row - 1]['id'];
    if (strlen($name = $this->owners[$row - 1]['name'])) {
      $text .= ' ' . $name;
    }
    $id = fHTML::encode($id);
    $text = fHTML::encode($text);
    
    if (empty($id)) {
      $r[1] = $text;
    } else {
      $r[1] = '<a href="'.SITE_BASE."/status?owner={$id}\">{$text}</a>";
    }
    
    $n = count($this->headers);
    for ($i = 2; $i < $n; $i++) {
      if ($this->scores[$row - 1][$i - 2] == '-') {
        $r[$i] = $this->scores[$row - 1][$i - 2];
      } else if ($i < $n - 3) {
        $r[$i] = '<a href="'.SITE_BASE."/status?owner={$id}&problem={$this->headers[$i]}\">{$this->scores[$row - 1][$i - 2]}</a>";
      } else if ($i < $n - 2) {
        $r[$i] = '<a href="'.SITE_BASE."/status?owner={$id}&verdict=1\">{$this->scores[$row - 1][$i - 2]}</a>";
      } else {
        $r[$i] = $this->scores[$row - 1][$i - 2];
      }
    }
    return $r;
  }
  
  public function getFooters()
  {
    $r = $this->getRow($this->getRowCount() + 1);
    $r[0] = '';  // no rank
    return $r;
  }
}
