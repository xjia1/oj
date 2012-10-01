<?php
$title = '个人信息';
include(__DIR__ . '/../layout/header.php');
?>
<div class="page-header">
  <h1><?php echo $title; ?></h1>
  <!--<a class="btn btn-primary"  href="./change/info">修改信息</a>-->
</div>
  <dl>
    <dt><label class="control-lab"><h3>姓名：<?php echo Profile::fetchRealName(fAuthorization::getUserToken()); ?></h3></dt>

    <dt><label class="control-lab"><h3>班级：<?php echo Profile::fetchClassName(fAuthorization::getUserToken()); ?></h3></dt>

    <dt><label class="control-lab"><h3>手机：<?php echo Profile::fetchPhoneNumber(fAuthorization::getUserToken()); ?></h3></dt>

    <dt><label class="control-lab"><h3>邮箱：<?php echo UserEmail::fetch(fAuthorization::getUserToken()); ?></h3></dt>

    <td><label class="control-lab"><h3>已解决问题：<?php $solved = UserStat::fetchSolved(fAuthorization::getUserToken()); echo $solved; ?></h3></dt>
  </dl>
  <table>
  <?php
    $length = 10;
  	for ($row = 0 ; $row < $solved/$length; $row++ ) {
  	  echo '<tr>';
		  for ($problem_id = $row*$length ; $problem_id<($row+1)*$length && $problem_id<$solved; $problem_id++) {
        echo '<td><h3><a href="./problem/'.$this->solved[$problem_id].'">'.$this->solved[$problem_id].'</a>&nbsp;&nbsp;&nbsp;&nbsp;</h3></td>';
	    }
  		echo '</tr>';
  	}
  ?>
  </table>
  <dl>
    <dt><label class="control-lab"><h3>尝试过的问题：<?php $tried = UserStat::fetchTried(fAuthorization::getUserToken()); echo $tried; ?></h3></dt>

    <dt><label class="control-lab"><h3>尝试但未解决的问题：<?php $failed = $tried-$solved; echo $failed; ?></h3></dt>
  </dl>
  <table>
  <?php
    $length = 10;
  	for ($row = 0 ; $row < $failed/$length; $row++ ) {
  		echo '<tr>';
		  for ($problem_id = $row*$length ; $problem_id<($row+1)*$length && $problem_id<$failed; $problem_id++) {
	  			echo '<td><h3><a href="./problem/'.$this->fails[$problem_id].'">'.$this->fails[$problem_id].'</a>&nbsp;&nbsp;&nbsp;&nbsp;</h3></td>';
	  	}
  		echo '</tr>';
  	}
  ?>
  </table>
  <dl>
    <dt><label class="control-lab"><h3>已提交问题：<?php echo UserStat::fetchSubmissions(fAuthorization::getUserToken()); ?></h3></dt>
  </dl>
<?php
include(__DIR__ . '/../layout/footer.php');
