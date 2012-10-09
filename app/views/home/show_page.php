<?php
$title = $this->page_title;
include(__DIR__ . '/../layout/header.php');
?>
<div class="page-header">
  <h1><?php echo fHTML::encode($title); ?></h1>
</div>
<article><?php echo Markdown($this->page_content); ?></article>
<?php
include(__DIR__ . '/../layout/footer.php');
