<?php
$title = '#' . $this->record->getId();
$stylesheets = array('verdicts');
include(__DIR__ . '/../layout/header.php');
?>
<div class="page-header">
  <h1>
    <?php echo $title; ?>
    <small class="record<?php echo str_replace(' ', '', $this->record->getResult()); ?>">
      <?php echo $this->record->getTranslatedResult(); ?>
    </small>
    <small>
      / 分数：<?php echo $this->record->getScore(); ?>
      / 时间：<?php echo $this->record->getTimeCost(); ?>
      / 内存：<?php echo $this->record->getMemoryCost(); ?>
    </small>
  </h1>
</div>
<div class="row">
  <div class="span9">
    <pre><?php echo fHTML::encode($this->record->getJudgeMessage()); ?></pre>
    <pre class="prettyprint linenums"><?php echo fHTML::encode($this->record->getSubmitCode()); ?></pre>
  </div>
  <ul class="span3">
    <li>
      提交者：
      <?php echo fHTML::encode($this->record->getOwner()); ?>
      <?php echo fHTML::encode(Profile::fetchRealName($this->record->getOwner())); ?>
    </li>
    <li>
      题目编号：
      <a href="<?php echo SITE_BASE; ?>/problem/<?php echo $this->record->getProblemId(); ?>">
        <?php echo $this->record->getProblemId(); ?></a>
      <a href="<?php echo SITE_BASE; ?>/submit?problem=<?php echo $this->record->getProblemId(); ?>" class="icon-repeat"></a>
    </li>
    <li>语言：<?php echo fHTML::encode($this->record->getLanguageName()); ?></li>
    <li>提交时间：<?php echo $this->record->getSubmitDatetime(); ?></li>
  </ul>
</div>
<?php
include(__DIR__ . '/../layout/footer.php');
