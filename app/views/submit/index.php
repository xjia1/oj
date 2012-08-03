<?php
$title = 'Submit';
include(__DIR__ . '/../layout/header.php');
?>
<form method="POST" action="<?php echo SITE_BASE; ?>/submit" class="form-horizontal">
  <fieldset>
    <legend>Submit Solution</legend>
    <div class="control-group">
      <label class="control-label" for="problem">Problem ID</label>
      <div class="controls">
        <input class="input-small" type="number" placeholder="Problem ID" id="problem" name="problem" maxlength="20" value="<?php echo fRequest::get('problem'); ?>">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="language">Language</label>
      <div class="controls">
        <select class="input-medium" id="language" name="language">
        <?php
          foreach ($this->languages as $value => $text) {
            fHTML::printOption($text, $value, $this->current_language);
          }
        ?>
        </select>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="code">Code</label>
      <div class="controls">
        <textarea style="width: 860px; font-family: Monaco, Lucida Console, Courier New, Free Monospaced;" id="code" name="code" rows="20"><?php echo $this->code; ?></textarea>
      </div>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Submit</button>
      <a class="btn" href="javascript:history.go(-1);void(0);">Cancel</a>
    </div>
</form>
<?php
include(__DIR__ . '/../layout/footer.php');
