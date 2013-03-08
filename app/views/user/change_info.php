<?php
$title = '修改个人信息';
include(__DIR__ . '/../layout/header.php');
?>
<div class="page-header">
  <h1><?php echo $title; ?></h1>
</div>
<form action="<?php echo SITE_BASE; ?>/change/info" method="POST" class="form-horizontal">
  <fieldset>
    <div class="control-group">
      <label class="control-label" for="realname">姓名 (必填)</label>
      <div class="controls">
        <input type="text" class="input-medium" id="realname" name="realname" placeholder="姓名" value="<?php echo Profile::fetchRealName(fAuthorization::getUserToken()); ?>">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label">性别 (必填)</label>
      <div class="controls">
        <?php $gender = Profile::fetchGender(fAuthorization::getUserToken()); ?>
        <label style="display:inline"><input type="radio" name="gender" value="男"<?php if ($gender == '男') echo ' checked'; ?>> 男</label>
        <label style="display:inline"><input type="radio" name="gender" value="女"<?php if ($gender == '女') echo ' checked'; ?>> 女</label>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="school">学校</label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="school" name="school" placeholder="学校" value="<?php echo Profile::fetchSchool(fAuthorization::getUserToken()); ?>">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="major">专业</label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="major" name="major" placeholder="专业" value="<?php echo Profile::fetchMajor(fAuthorization::getUserToken()); ?>">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="grade">年级</label>
      <div class="controls">
        <input type="number" class="input-small" id="grade" name="grade" value="<?php echo Profile::fetchGrade(fAuthorization::getUserToken()); ?>">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="phone">手机 (必填)</label>
      <div class="controls">
        <input type="text" class="input-medium" id="phone" name="phone" placeholder="手机" value="<?php echo Profile::fetchPhoneNumber(fAuthorization::getUserToken()); ?>">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="qq">QQ</label>
      <div class="controls">
        <input type="text" class="input-medium" id="qq" name="qq" placeholder="QQ" value="<?php echo Profile::fetchQQ(fAuthorization::getUserToken()); ?>">
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
