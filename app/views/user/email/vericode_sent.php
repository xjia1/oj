<?php
$title = 'Email 验证';
include(__DIR__ . '/../../layout/header.php');
?>
<div class="row">
  <div class="span3">
    <img src="<?php echo ASSET_CSS; ?>/img/tickman.jpg">
  </div>
  <div class="span9">
    <div class="page-header">
      <h1>验证邮件发送成功</h1>
    </div>
    <h3>请检查您的收件箱，以收取我们发送给您的验证邮件。</h3>
    <h3>如果您在收件箱中找不到验证邮件，它可能被错误分类到了垃圾邮件中。</h3>
    <h3>谢谢您的配合！ :-)</h3>
  </div>
</div>
<?php
include(__DIR__ . '/../../layout/footer.php');
