<?php
class Report extends fActiveRecord
{
  protected function configure()
  {
    //
  }
  
  public function isReadable()
  {
    return $this->getVisible() or User::can('view-any-report');
  }
}
