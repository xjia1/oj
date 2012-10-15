<?php
class Verdict
{
  /**
   * Unknown
   * The solution has not been judged yet.
   */
  const UNKNOWN = 0;

  /**
   * Accepted
   * The solution has produced output that the judge system or 
   * a checker program (commonly referred to as a special judge) 
   * accepts as correct.
   */
  const AC      = 1;

  /**
   * Presentation Error
   * The solution has produced output that is correct in content 
   * but incorrect in format.
   */
  const PE      = 2;

  /**
   * Time Limit Exceeded
   * The solution has run for longer time than permitted. 
   * This means either the time spent on all test cases exceeds 
   * the overall limit or that spent on a single test case exceeds 
   * the per-case limit.
   */
  const TLE     = 3;

  /**
   * Memory Limit Exceeded
   * The solution has consumed more memory than permitted.
   */
  const MLE     = 4;

  /**
   * Wrong Answer
   * The solution has not produced the desired output.
   */
  const WA      = 5;

  /**
   * Runtime Error
   * The solution has caused an unhandled exception 
   * (as defined by the runtime environment) during execution.
   */
  const RE      = 6;

  /**
   * Output Limit Exceeded
   * The solution has produced excessive output.
   */
  const OLE     = 7;

  /**
   * Compile Error
   * The solution cannot be compiled into any program runnable by the judge system.
   */
  const CE      = 8;

  /**
   * System Error
   * The judge system has failed to run the solution.
   */
  const SE      = 9;

  /**
   * Validator Error
   * The checker program has exhibited abnormal behavior 
   * while validating the output produced by the solution.
   */
  const VE      = 10;

  static $NAMES = array(
		'Unknown',
		'Accepted',
		'Presentation Error',
		'Time Limit Exceeded',
		'Memory Limit Exceeded',
		'Wrong Answer',
		'Runtime Error',
		'Output Limit Exceeded',
		'Compile Error',
		'System Error',
		'Validator Error'
  );
  
  static $CHINESE_NAMES = array(
		'未知',
		'正确',
		'格式错误',
		'超过时间限制',
		'超过内存限制',
		'答案错误',
		'运行时错误',
		'超过输出限制',
		'编译错误',
		'系统错误',
		'校验错误'
  );
}
