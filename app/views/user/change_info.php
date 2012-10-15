<?php
$title = '修改个人信息';
include(__DIR__ . '/../layout/header.php');
?>
<div class="page-header">
  <h1><?php echo $title; ?></h1>
</div>
<?php  $UserToken = fAuthorization::getUserToken(); ?>
<form action="<?php echo SITE_BASE; ?>/change/info" method="POST" class="form-horizontal">
  <fieldset>
    <div class="control-group">
      <label class="control-label" for="realname">姓名</label>
      <div class="controls">
        <input type="text" class="input-large" id="realname" name="realname" placeholder="姓名" value="<?php echo Profile::fetchRealName($UserToken); ?>">
        <p class="help-block">ACM队成员请在姓名后加星号“*”，比如“张三*”。</p>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="class_name">班级</label>
      <div class="controls">
        <input type="text" class="input-large" id="class_name" name="class_name" placeholder="班级" value="<?php echo Profile::fetchClassName($UserToken); ?>">
        <p class="help-block">比如“F0903028”。</p>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="phone_number">手机</label>
      <div class="controls">
        <input type="text" class="input-large" id="phone_number" name="phone_number" placeholder="手机" value="<?php echo Profile::fetchPhoneNumber($UserToken); ?>">
        <p class="help-block">比如“15212345678”。（此项仅管理员可见）</p>
      </div>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-success">修改我的个人信息</button>
      <a class="btn" href="javascript:history.go(-1);void(0);">取消</a>
    </div>
  </fieldset>
</form>
<?php
include(__DIR__ . '/../layout/footer.php');
