<?php
$title = '个人信息';
include(__DIR__ . '/../layout/header.php');
?>
<div class="page-header">
  <h1><?php echo $title; ?></h1>
  <!--<a class="btn btn-primary"  href="./change/info">修改信息</a>-->
</div>
<?php
  if (empty($this->username)) $this->username = fAuthorization::getUserToken();
?>
  <ul>
    <li><label class="control-lab"><h3>用户名：<?php echo $this->username; ?></h3></label></li>
    <li><label class="control-lab"><h3>姓名：<?php echo Profile::fetchRealName($this->username); ?></h3></label></li>
    <li><label class="control-lab"><h3>班级：<?php echo Profile::fetchClassName($this->username); ?></h3></label></li>
    <?php if (User::can('view-any-profile') or $this->username==fAuthorization::getUserToken()): ?>
    <li><label class="control-lab"><h3>手机：<?php echo Profile::fetchPhoneNumber($this->username); ?></h3></label></li>
    <li><label class="control-lab"><h3>邮箱：<?php echo UserEmail::fetch($this->username); ?></h3></label></li>
    <?php endif; ?>
    <li><label class="control-lab"><h3>已提交问题：<?php echo UserStat::fetchSubmissions($this->username); ?></h3></label></li>
    <li><label class="control-lab"><h3>尝试过的问题：<?php $tried = UserStat::fetchTried($this->username); echo $tried; ?></h3></label></li>
    <li><label class="control-lab"><h3>已解决问题：<?php $solved = UserStat::fetchSolved($this->username); echo $solved; ?></h3></label></li>
  </ul>
  <table class="table table-bordered table-striped table-condensed">
  <?php $length = 10; ?>
  <?php for ($row = 0 ; $row < $solved/$length; $row++ ): ?>
    <tr>
    <?php  for ($problem_id = $row*$length ; $problem_id<($row+1)*$length && $problem_id<$solved; $problem_id++): ?>
      <td><h3><a href="./problem/<?php echo $this->solved[$problem_id]; ?>"> <?php echo $this->solved[$problem_id]; ?> </a></h3></td>
    <?php endfor; ?>
    <tr>
  <?php endfor; ?>
  </table>
  <ul>
    <li><label class="control-lab"><h3>尝试但未解决的问题：<?php $failed = $tried-$solved; echo $failed; ?></h3></label></li>
  </ul>
  <table class="table table-bordered table-striped table-condensed">
  <?php for ($row = 0 ; $row < $failed/$length; $row++ ): ?>
    <tr>
    <?php  for ($problem_id = $row*$length ; $problem_id<($row+1)*$length && $problem_id<$failed; $problem_id++): ?>
      <td><h3><a href="./problem/<?php echo $this->fails[$problem_id]; ?>"> <?php echo $this->fails[$problem_id]; ?> </a></h3></td>
    <?php endfor; ?>
    <tr>
  <?php endfor; ?>
  </table>
<?php
include(__DIR__ . '/../layout/footer.php');
