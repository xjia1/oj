<?php
$title = 'Change Password';
include(__DIR__ . '/../layout/header.php');
?>
<div class="page-header">
  <h1>Change Password</h1>
</div>
<form action="<?php echo SITE_BASE; ?>/change/password" method="POST" class="form-horizontal">
  <fieldset>
    <div class="control-group">
      <label class="control-label" for="old_password">Old Password</label>
      <div class="controls">
        <input type="password" class="input-large" id="old_password" name="old_password" placeholder="Old Password">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="new_password">New Password</label>
      <div class="controls">
        <input type="password" class="input-large" id="new_password" name="new_password" placeholder="New Password">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="repeat_password">Repeat Password</label>
      <div class="controls">
        <input type="password" class="input-large" id="repeat_password" name="repeat_password" placeholder="Repeat Password">
      </div>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-danger">Update My Password</button>
      <a class="btn" href="javascript:history.go(-1);void(0);">Cancel</a>
    </div>
  </fieldset>
</form>
<?php
include(__DIR__ . '/../layout/footer.php');
