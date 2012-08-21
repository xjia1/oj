<?php
$title = 'Email Verification';
include(__DIR__ . '/../../layout/header.php');
?>
<div class="page-header">
  <h1>Email Verification</h1>
</div>
<form action="<?php echo SITE_BASE; ?>/email/verify" method="POST" class="form-horizontal">
  <fieldset>
    <div class="control-group">
      <label class="control-label" for="email">Your Email Address</label>
      <div class="controls">
        <input type="email" class="input-large" id="email" name="email" placeholder="yourname@somesite.com">
        <p class="help-block">Please enter your email address above.</p>
        <p class="help-block">You are allowed to have multiple accounts and share email address among them.</p>
        <p class="help-block">We will send you an email with a hyperlink for verification shortly.</p>
        <p class="help-block">You must own this email address in order to receive our verification email.</p>
      </div>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-success">Verify My Email Address</button>
    </div>
  </fieldset>
</form>
<?php
include(__DIR__ . '/../../layout/footer.php');
