<?php
$title = '修改密码';
include(__DIR__ . '/../layout/header.php');
?>
<div class="page-header">
  <h1><?php echo $title; ?></h1>
</div>
<form action="<?php echo SITE_BASE; ?>/change/password" method="POST" class="form-horizontal">
  <fieldset>
    <div class="control-group">
      <label class="control-label" for="old_password">旧密码</label>
      <div class="controls">
        <input type="password" class="input-large" id="old_password" name="old_password" placeholder="旧密码">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="new_password">新密码</label>
      <div class="controls">
        <input type="password" class="input-large" id="new_password" name="new_password" placeholder="新密码">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="repeat_password">重复新密码</label>
      <div class="controls">
        <input type="password" class="input-large" id="repeat_password" name="repeat_password" placeholder="重复新密码">
      </div>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-danger">修改我的密码</button>
      <a class="btn" href="javascript:history.go(-1);void(0);">取消</a>
    </div>
  </fieldset>
</form>
<?php
include(__DIR__ . '/../layout/footer.php');
