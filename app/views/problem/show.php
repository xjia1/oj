<?php
$title = $this->problem->getTitle();
include(__DIR__ . '/../layout/header.php');
?>
<div class="page-header">
  <h1><?php echo $this->problem->getId(); ?>. <?php echo fHTML::encode($this->problem->getTitle()); ?></h1>
</div>
<div class="row">
  <article class="span9"><?php echo Markdown($this->problem->getDescription()); ?></article>
  <aside class="span3">
    <div class="well">
      <a class="btn btn-primary btn-large" href="<?php echo SITE_BASE; ?>/submit?problem=<?php echo $this->problem->getId(); ?>">提交此题</a>
    </div>
  </aside>
</div>
<script type="text/javascript" src="http://download.acm-project.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
<?php
include(__DIR__ . '/../layout/footer.php');
