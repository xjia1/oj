<?php
$title = 'Record #' . $this->record->getId();
$stylesheets = array('verdicts');
include(__DIR__ . '/../layout/header.php');
?>
<div class="page-header">
  <h1>
    Record #<?php echo $this->record->getId(); ?>
    <small class="record<?php echo str_replace(' ', '', $this->record->getResult()); ?>">
      <?php echo $this->record->getResult(); ?>
    </small>
    <small>
      / Score: <?php echo $this->record->getScore(); ?>
      / Time: <?php echo $this->record->getTimeCost(); ?>
      / Memory: <?php echo $this->record->getMemoryCost(); ?>
    </small>
  </h1>
</div>
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
