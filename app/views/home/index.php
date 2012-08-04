<?php
$title = $this->page_title;
include(__DIR__ . '/../layout/header.php');
?>
<div class="hero-unit">
  <article><?php echo Markdown($this->page_content); ?></article>
  <p class="btn-group">
    <a href="<?php echo SITE_BASE; ?>/sets" class="btn btn-warning btn-large">Problem Sets</a>
    <a href="<?php echo SITE_BASE; ?>/problems" class="btn btn-success btn-large">All Problems</a>
    <a href="<?php echo SITE_BASE; ?>/submit" class="btn btn-danger btn-large">Submit Solution</a>
    <a href="<?php echo SITE_BASE; ?>/status" class="btn btn-primary btn-large">Online Status</a>
  </p>
</div>
<?php
include(__DIR__ . '/../layout/footer.php');
