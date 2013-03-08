<?php
$title = 'Sign Up';
include(__DIR__ . '/../layout/header.php');
?>
<div class="page-header">
  <h1>注册</h1>
</div>
<form action="<?php echo SITE_BASE; ?>/login" method="POST" class="form-horizontal">
  <fieldset>
    <div class="control-group">
      <label class="control-label" for="username">用户名 (必填)</label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="username" name="username" placeholder="用户名">
        <p class="help-block">请在此输入您的用户名（大小写敏感）。<font color="red">在校学生请填写学号。</font></p>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="password">密码 (必填)</label>
      <div class="controls">
        <input type="password" class="input-xlarge" id="password" name="password" placeholder="密码">
        <p class="help-block">请在此输入您的密码（大小写敏感，可以使用特殊字符）。</p>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="realname">姓名 (必填)</label>
      <div class="controls">
        <input type="text" class="input-medium" id="realname" name="realname" placeholder="姓名">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label">性别 (必填)</label>
      <div class="controls">
        <label style="display:inline"><input type="radio" name="gender" value="男"> 男</label>
        <label style="display:inline"><input type="radio" name="gender" value="女"> 女</label>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="school">学校</label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="school" name="school" placeholder="学校">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="major">专业</label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="major" name="major" placeholder="专业">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="grade">年级</label>
      <div class="controls">
        <input type="number" class="input-small" id="grade" name="grade" value="<?php echo date('Y'); ?>">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="phone">手机 (必填)</label>
      <div class="controls">
        <input type="text" class="input-medium" id="phone" name="phone" placeholder="手机">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="qq">QQ</label>
      <div class="controls">
        <input type="text" class="input-medium" id="qq" name="qq" placeholder="QQ">
      </div>
    </div>
    <div class="form-actions">
      <input type="submit" class="btn btn-success btn-large" name="action" value="注册">
    </div>
  </fieldset>
</form>
<?php
include(__DIR__ . '/../layout/footer.php');
