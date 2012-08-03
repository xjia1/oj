<?php
class Variable extends fActiveRecord
{
  protected function configure()
  {
    //
  }
  
  public static function getString($name)
  {
    try {
      $var = new Variable($name);
      return $var->getValue();
    } catch (fNotFoundException $e) {
      return '';
    }
  }
}
