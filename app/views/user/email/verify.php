<?php
$title = 'Email 验证';
include(__DIR__ . '/../../layout/header.php');
?>
<div class="page-header">
  <h1><?php echo $title; ?></h1>
</div>
<form action="<?php echo SITE_BASE; ?>/email/verify" method="POST" class="form-horizontal">
  <fieldset>
    <div class="control-group">
      <label class="control-label" for="email">您的电子邮件地址</label>
      <div class="controls">
        <input type="email" class="input-large" id="email" name="email" placeholder="yourname@somesite.com">
        <p class="help-block">请在上面的文本框中输入您的电子邮件地址。</p>
        <p class="help-block">您可以使用同一电子邮件地址来验证多个帐户。</p>
        <p class="help-block">我们会发给您一封带有验证链接的电子邮件。</p>
        <p class="help-block">请务必确保您拥有该电子邮件地址，以便收取验证邮件。</p>
      </div>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-success">验证我的电子邮件地址</button>
    </div>
  </fieldset>
</form>
<?php
include(__DIR__ . '/../../layout/footer.php');
