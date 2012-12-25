<?php
$title = 'Sign In';
include(__DIR__ . '/../layout/header.php');
?>
<div class="page-header">
  <h1>登录 / 注册</h1>
</div>
<form action="<?php echo SITE_BASE; ?>/login" method="POST" class="form-horizontal">
  <fieldset>
    <div class="control-group">
      <label class="control-label" for="username">用户名</label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="username" name="username" placeholder="用户名">
        <p class="help-block">请在此输入您的用户名（大小写敏感）。在校学生请填写学号。</p>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="password">密码</label>
      <div class="controls">
        <input type="password" class="input-xlarge" id="password" name="password" placeholder="密码">
        <p class="help-block">请在此输入您的密码（大小写敏感，可以使用特殊字符）。</p>
      </div>
    </div>
    <div class="form-actions">
      <input type="submit" class="btn btn-primary btn-large" name="action" value="登录">
      <input type="submit" class="btn btn-success btn-large" name="action" value="注册">
    </div>
  </fieldset>
</form>
<?php
include(__DIR__ . '/../layout/footer.php');
