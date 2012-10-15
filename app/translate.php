<?php
function translate($string)
{
  static $translations = array(
    'right now' => '刚刚',
    'at the same time' => '同时',
    'second' => '秒',
    'seconds' => '秒',
    'minute' => '分钟',
    'minutes' => '分钟',
    'hour' => '小时',
    'hours' => '小时',
    'day' => '天',
    'days' => '天',
    'week' => '周',
    'weeks' => '周',
    'month' => '月',
    'months' => '月',
    'year' => '年',
    'years' => '年',
    '%1$s %2$s' => '%1$s %2$s',
    '%1$s %2$s from now' => '%1$s %2$s from now',
    '%1$s %2$s ago' => '%1$s%2$s前',
    '%1$s %2$s after' => '%1$s %2$s after',
    '%1$s %2$s before' => '%1$s %2$s before'
  );
  
  if (isset($translations[$string])) {
    return $translations[$string];
  }
  return $string;
}

function T($english)
{
  static $translations = array(
    'chinese' => array(

	//app/controllers/DashboardController.php
      'You are not allowed to view the dashboard.' => '你没有进入控制台的权限。',
	'Problem title is not specified in problem.conf'=>'题目名称在 problem.conf 中没有指出。',
	'Problem author is not specified in problem.conf'=>'题目作者在 problem.conf 中没有指出。',
	'Problem case count is not specified in problem.conf'=>'题目数据组数在 problem.conf 中没有指出。',
	'Problem case score is not specified in problem.conf'=>'题目各组数据分数在 problem.conf 中没有指出。',
	'Problem time limit is not specified in problem.conf'=>'题目时间限制在 problem.conf 中没有指出。',
	'Problem memory limit is not specified in problem.conf'=>'题目内存限制在 problem.conf 中没有指出。',
	'Problem secret-before time is not specified in problem.conf'=>'题目隐藏截止时间在 problem.conf 中没有指出。',
	'You are not allowed to manage problems.'=>'你没有管理题目的权限。',
	'All problems refreshed successfully.'=>'所有题目成功刷新。',
	'You are not allowed to rejudge records.'=>'你没有重判记录的权限。',
	'Score cannot be negative.'=>'分数不能为负。',
	'You are not allowed to create reports.'=>'你没有创建比赛的权限。',
	'Report created successfully.'=>'比赛创建成功。',
	'You are not allowed to show this report.'=>'你没有显示此比赛的权限。',
	'You are not allowed to hide this report.'=>'你没有隐藏此比赛的权限。',
	'You are not allowed to remove this report.'=>'你没有删除此比赛的权限。',
	'Permission added successfully.'=>'权限添加成功。',
	'You are not allowed to add permissions.'=>'你没有添加权限的权限。',
	'Permission removed successfully.'=>'权限删除成功。',
	'You are not allowed to remove permissions.'=>'你没有删除权限的权限。',
	'Variable removed successfully.'=>'变量删除成功。',
	'Variable set successfully.'=>'变量设置成功。',

	//app/controllers/ProblemController.php
	'Problem is secret now.'=>'题目现在处于隐藏状态。',

	//app/controllers/RecordController.php
	'You are not allowed to read this record.'=>'你没有查看此记录的权限。',

	//app/controllers/ReportController.php
	'You are not allowed to view this report.'=>'你没有查看此比赛的权限。',
	'This contest is not registrable.'=>'比赛不可注册。',
	'Registered successfully.'=>'注册成功。',
	'Not allowed to ask question.'=>'不允许提问。',
	'Please choose a category.'=>'请选择一个分类。',
	'Question too short (minimum 10 bytes).'=>'问题过短 (最少 10 字节)。',
	'Question too long (maximum 500 bytes).'=>'问题过长 (最大 500 字节)。',
	'Question saved.'=>'问题已保存。',
	'Not allowed to answer question.'=>'没有回答问题的权限。',
	'Question answered.'=>'问题已回答。',
	'Not allowed to toggle question visibility.'=>'没有更改此问题可见度的权限。',
	'Visibility toggled.'=>'可见度已更改。',

	//app/controllers/UserController.php
	'Logged in successfully.'=>'登录成功。',
	'Password mismatch.'=>'密码错误。',
	'Username is too short.'=>'用户名过短。',
	'Username is too long.'=>'用户名过长。',
	'Password is too short.'=>'密码过短。',
	'Username is illegal.'=>'用户名包含非法字符。',
	'User already exists.'=>'用户已存在。',
	'Registered successfully.'=>'注册成功。',
	'Logged out successfully.'=>'注销成功。',
	'Information updated successfully.'=>'信息更新成功。',
	'Old password is too short.'=>'旧密码过短。',
	'New password is too short.'=>'新密码过短。',
	'Repeat password mismatch.'=>'两次输入密码不相同。',
	'Old password mismatch.'=>'旧密码错误。',
	'Password updated successfully.'=>'密码更改成功。',
	'Invalid email address.'=>'邮箱地址无效。',
	'Invalid verification code.'=>'验证码无效。',
	'Your email address is verified successfully.'=>'你的邮箱已成功验证。',
	'Email verification failed: '=>'邮箱验证失败: ',
	
	//app/controllers/SubmitController.php
	'Code cannot be empty.'=>'代码不能为空。',
	'Problem is secret now. You are not allowed to submit this problem.'=>'问题现在是隐藏状态，你不能提交此题目。'，
	
		
	//app/models/User.php
	'Click here to verify your email address.'=>'点击此处验证你的邮箱地址。',
	'You are required to verify your email address before doing this action.'=>'请先验证你的邮箱地址。'
	
	
	)
  );
  $language = 'chinese';  // 这里先固定为 chinese，以后会修改成根据用户来决定语言
  if (isset($translations[$language][$english])) {
    return $translations[$language][$english];
  }
  return $english;
}
