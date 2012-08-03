<?php
class Variable extends fActiveRecord
{
  protected function configure()
  {
    //
  }
  
  public static function getString($name, $default='')
  {
    try {
      $var = new Variable($name);
      return '' . $var->getValue();
    } catch (fNotFoundException $e) {
      return $default;
    }
  }

  public static function getInteger($name, $default=0)
  {
    try {
      $var = new Variable($name);
      return 0 + $var->getValue();
    } catch (fNotFoundException $e) {
      return $default;
    }
  }
}
