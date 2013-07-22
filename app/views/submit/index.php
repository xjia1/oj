<?php
$title = '提交';
include(__DIR__ . '/../layout/header.php');
?>
<div class="page-header">
  <h1><?php echo $title; ?></h1>
</div>
<form method="POST" action="<?php echo SITE_BASE; ?>/submit" class="form-horizontal">
  <fieldset>
    <div class="control-group">
      <label class="control-label" for="problem">题目编号</label>
      <div class="controls">
        <input class="input-small" type="number" placeholder="题目编号" id="problem" name="problem" maxlength="20" value="<?php echo fRequest::get('problem'); ?>">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="language">语言</label>
      <div class="controls">
        <input type="hidden" id="language" name="language" value="">
        <div class="btn-group" data-toggle="buttons-radio" >
          <?php foreach (static::$languages as $value => $text): ?>
            <input type="button" class="btn btn-primary" value="<?php echo $text; ?>" onclick="$('#language').val('<?php echo $value; ?>')">
          <?php endforeach ; ?>
        </div>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="code">代码</label>
      <div class="controls">
        <textarea style="width: 860px; font-family: Monaco, Lucida Console, Courier New, Free Monospaced;" id="code" name="code" rows="20"><?php echo fHTML::encode($this->code); ?></textarea>
      </div>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary">提交</button>
      <a class="btn" href="javascript:history.go(-1);void(0);">取消</a>
    </div>
</form>
<?php
include(__DIR__ . '/../layout/footer.php');
