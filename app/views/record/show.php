<?php
$title = 'Record #' . $this->record->getId();
include(__DIR__ . '/../layout/header.php');
?>
<h1>Record #<?php echo $this->record->getId(); ?></h1>
<div class="row">
  <div class="span9">
    <h2>Judge Message</h2>
    <pre><?php echo fHTML::encode($this->record->getJudgeMessage()); ?></pre>
    <h2>Code</h2>
    <pre class="prettyprint linenums"><?php echo fHTML::encode($this->record->getSubmitCode()); ?></pre>
  </div>
  <ul class="span3">
    <li>
      Owner:
      <?php echo fHTML::encode($this->record->getOwner()); ?>
      <?php echo fHTML::encode(Profile::fetchRealName($this->record->getOwner())); ?>
    </li>
    <li>
      Problem:
      <a href="<?php echo SITE_BASE; ?>/problem/<?php echo $this->record->getProblemId(); ?>">
        <?php echo $this->record->getProblemId(); ?></a>
      (<a href="<?php echo SITE_BASE; ?>/submit?problem=<?php echo $this->record->getProblemId(); ?>">submit</a>)
    </li>
    <li>Language: <?php echo fHTML::encode($this->record->getLanguageName()); ?></li>
    <li>Submit Time: <?php echo $this->record->getSubmitDatetime(); ?></li>
  </ul>
</div>
<?php
include(__DIR__ . '/../layout/footer.php');
