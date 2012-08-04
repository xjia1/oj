<?php
$title = 'Sign In';
include(__DIR__ . '/../layout/header.php');
?>
<div class="page-header">
  <h1>Sign In / Register</h1>
</div>
<form action="<?php echo SITE_BASE; ?>/login" method="POST" class="form-horizontal">
  <fieldset>
    <div class="control-group">
      <label class="control-label" for="username">Username</label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="username" name="username" placeholder="Username">
        <p class="help-block">Please enter your username here. Case sensitive.</p>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="password">Password</label>
      <div class="controls">
        <input type="password" class="input-xlarge" id="password" name="password" placeholder="Password">
        <p class="help-block">Please enter your password here. Case sensitive.</p>
      </div>
    </div>
    <div class="form-actions">
      <input type="submit" class="btn btn-primary btn-large" name="action" value="Sign In">
      <input type="submit" class="btn btn-success btn-large" name="action" value="Register">
    </div>
  </fieldset>
</form>
<?php
include(__DIR__ . '/../layout/footer.php');
